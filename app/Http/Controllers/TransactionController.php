<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $transactions = Transaction::with(['booking.bookable'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        // Check if user owns this transaction
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load(['booking.bookable']);

        return view('transactions.show', compact('transaction'));
    }

    public function payment(Request $request, Transaction $transaction)
    {
        // Check if user owns this transaction
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if transaction is still pending
        if ($transaction->status !== 'pending') {
            return back()->withErrors(['error' => 'Transaksi tidak dapat diproses.']);
        }

        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:bank_transfer,e_wallet,credit_card',
            'payment_proof' => 'required_if:payment_method,bank_transfer|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'account_number' => 'required_if:payment_method,e_wallet|string|max:255',
            'account_name' => 'required_if:payment_method,e_wallet|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $paymentData = [
                'payment_method' => $request->payment_method,
                'submitted_at' => now(),
            ];

            // Handle payment proof upload for bank transfer
            if ($request->payment_method === 'bank_transfer' && $request->hasFile('payment_proof')) {
                $path = $request->file('payment_proof')->store('payments/' . Auth::id(), 'public');
                $paymentData['payment_proof'] = $path;
            }

            // Handle e-wallet data
            if ($request->payment_method === 'e_wallet') {
                $paymentData['account_number'] = $request->account_number;
                $paymentData['account_name'] = $request->account_name;
            }

            // Update transaction
            $transaction->update([
                'method' => $request->payment_method,
                'payment_data' => json_encode($paymentData),
                'status' => $request->payment_method === 'bank_transfer' ? 'pending' : 'success',
                'paid_at' => $request->payment_method !== 'bank_transfer' ? now() : null,
                'notes' => $request->notes,
            ]);

            // If payment is automatically successful (e-wallet, credit card)
            if ($transaction->status === 'success') {
                $this->processSuccessfulPayment($transaction);
            }

            DB::commit();

            $message = $request->payment_method === 'bank_transfer' 
                ? 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.'
                : 'Pembayaran berhasil diproses!';

            return redirect()->route('transactions.show', $transaction)->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function cancel(Transaction $transaction)
    {
        // Check if user owns this transaction
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if transaction can be cancelled
        if (!in_array($transaction->status, ['pending'])) {
            return back()->withErrors(['error' => 'Transaksi tidak dapat dibatalkan.']);
        }

        DB::beginTransaction();
        try {
            $transaction->update([
                'status' => 'cancelled',
                'notes' => 'Dibatalkan oleh user pada ' . now()->format('d/m/Y H:i'),
            ]);

            // Cancel related booking if exists
            if ($transaction->booking) {
                $transaction->booking->update([
                    'status' => 'cancelled',
                    'cancellation_reason' => 'Pembayaran dibatalkan',
                    'cancelled_at' => now(),
                ]);

                // Update availability for activities
                if ($transaction->booking->bookable_type === 'App\Models\Activity') {
                    $transaction->booking->bookable->decrement('current_participants');
                }
            }

            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function downloadInvoice(Transaction $transaction)
    {
        // Check if user owns this transaction
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if transaction is successful
        if ($transaction->status !== 'success') {
            return back()->withErrors(['error' => 'Invoice hanya tersedia untuk transaksi yang berhasil.']);
        }

        $transaction->load(['booking.bookable', 'user']);

        // Generate PDF invoice (you can use libraries like DomPDF or TCPDF)
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('transactions.invoice', compact('transaction'));
        
        return $pdf->download('invoice-' . $transaction->transaction_code . '.pdf');
    }

    public function retry(Transaction $transaction)
    {
        // Check if user owns this transaction
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if transaction can be retried
        if (!in_array($transaction->status, ['failed', 'cancelled'])) {
            return back()->withErrors(['error' => 'Transaksi tidak dapat diulang.']);
        }

        // Reset transaction status
        $transaction->update([
            'status' => 'pending',
            'payment_data' => null,
            'payment_reference' => null,
            'paid_at' => null,
            'notes' => 'Transaksi diulang pada ' . now()->format('d/m/Y H:i'),
        ]);

        return redirect()->route('transactions.show', $transaction)->with('success', 'Transaksi berhasil direset. Silakan lakukan pembayaran kembali.');
    }

    private function processSuccessfulPayment(Transaction $transaction)
    {
        // Update booking status
        if ($transaction->booking) {
            $transaction->booking->update(['status' => 'confirmed']);

            // For activities, increment participant count
            if ($transaction->booking->bookable_type === 'App\Models\Activity') {
                $transaction->booking->bookable->increment('current_participants');
            }

            // For residences, decrement available rooms
            if ($transaction->booking->bookable_type === 'App\Models\Residence') {
                $transaction->booking->bookable->decrement('available_rooms');
            }
        }

        // Create notification for user
        $user = $transaction->user;
        $user->notifications()->create([
            'title' => 'Pembayaran Berhasil',
            'message' => 'Pembayaran untuk booking ' . $transaction->booking->booking_code . ' telah berhasil diproses.',
            'type' => 'payment_success',
            'data' => json_encode([
                'transaction_id' => $transaction->id,
                'booking_id' => $transaction->booking_id,
            ]),
            'action_url' => route('bookings.show', $transaction->booking),
        ]);
    }

    public function getBankAccounts()
    {
        // Return available bank accounts for transfer
        $bankAccounts = [
            [
                'bank' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => 'PT INFOMA INDONESIA',
            ],
            [
                'bank' => 'Mandiri',
                'account_number' => '0987654321',
                'account_name' => 'PT INFOMA INDONESIA',
            ],
            [
                'bank' => 'BNI',
                'account_number' => '1122334455',
                'account_name' => 'PT INFOMA INDONESIA',
            ],
        ];

        return response()->json($bankAccounts);
    }

    public function checkPaymentStatus(Transaction $transaction)
    {
        // Check if user owns this transaction
        if ($transaction->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => $transaction->status,
            'paid_at' => $transaction->paid_at,
            'payment_reference' => $transaction->payment_reference,
        ]);
    }
}