<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\Discount;
use App\Models\Residence;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class BookingController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request, $id)
    {
        try {
            // Check if this is an activity booking
            $isActivity = $request->route()->getName() === 'bookings.create.activity';
            
            Log::info('Creating booking', [
                'is_activity' => $isActivity,
                'id' => $id,
                'route_name' => $request->route()->getName()
            ]);
            
            if ($isActivity) {
                $item = Activity::with(['provider', 'category'])->findOrFail($id);
            } else {
                $item = Residence::with(['provider', 'category'])->findOrFail($id);
            }

            Log::info('Item found', [
                'item_type' => get_class($item),
                'item_id' => $item->id,
                'item_title' => $item->title
            ]);

            return view('bookings.create', compact('item'));
        } catch (\Exception $e) {
            Log::error('Error in create booking', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Storing booking', [
                'request_data' => $request->except(['files']),
                'has_files' => $request->hasFile('files')
            ]);

            $type = $request->bookable_type;
            
            // Validation rules
            $rules = [
                'bookable_type' => 'required|in:App\Models\Residence,App\Models\Activity',
                'bookable_id' => 'required|integer',
                'start_date' => 'required|date|after:today',
                'discount_code' => 'nullable|string',
            ];

            if ($type === 'App\Models\Residence') {
                $rules = array_merge($rules, [
                    'end_date' => 'required|date|after:start_date',
                    'booking_data.full_name' => 'required|string|max:255',
                    'booking_data.phone' => 'required|string|max:255',
                    'booking_data.emergency_contact' => 'required|string|max:255',
                    'booking_data.emergency_phone' => 'required|string|max:255',
                    'booking_data.occupation' => 'required|string|max:255',
                    'files.ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'files.agreement' => 'nullable|file|mimes:pdf|max:2048',
                ]);
            } else {
                $rules = array_merge($rules, [
                    'booking_data.full_name' => 'required|string|max:255',
                    'booking_data.phone' => 'required|string|max:255',
                    'booking_data.university' => 'nullable|string|max:255',
                    'booking_data.major' => 'nullable|string|max:255',
                    'booking_data.student_id' => 'nullable|string|max:255',
                    'booking_data.motivation' => 'nullable|string|max:1000',
                ]);
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Log::error('Validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                return back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            // Get the bookable item
            $bookableModel = $request->bookable_type;
            $bookable = $bookableModel::findOrFail($request->bookable_id);

            Log::info('Bookable item found', [
                'bookable_type' => get_class($bookable),
                'bookable_id' => $bookable->id,
                'bookable_title' => $bookable->title
            ]);

            // Check availability
            if ($type === 'App\Models\Residence') {
                if ($bookable->available_rooms <= 0) {
                    throw new \Exception('Kamar tidak tersedia.');
                }
                
                // Calculate total amount for residence
                $startDate = \Carbon\Carbon::parse($request->start_date);
                $endDate = \Carbon\Carbon::parse($request->end_date);
                
                $totalAmount = $this->calculateResidenceAmount($bookable, $startDate, $endDate);
            } else {
                if ($bookable->current_participants >= $bookable->max_participants) {
                    throw new \Exception('Kegiatan sudah penuh.');
                }
                
                if ($bookable->registration_deadline < now()) {
                    throw new \Exception('Pendaftaran sudah ditutup.');
                }
                
                $totalAmount = $bookable->price;
            }

            // Handle file uploads
            $uploadedFiles = [];
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $key => $file) {
                    if ($file) {
                        $path = $file->store('bookings/' . Auth::id(), 'public');
                        $uploadedFiles[$key] = $path;
                    }
                }
            }

            // Apply discount if provided
            $discountAmount = 0;
            if ($request->filled('discount_code')) {
                $discount = $this->applyDiscount($request->discount_code, $bookable, $totalAmount);
                if ($discount) {
                    $discountAmount = $discount['amount'];
                }
            }

            $finalAmount = $totalAmount - $discountAmount;

            // Create booking
            $booking = Booking::create([
                'booking_code' => 'INF-' . strtoupper(Str::random(8)),
                'user_id' => Auth::id(),
                'bookable_id' => $request->bookable_id,
                'bookable_type' => $request->bookable_type,
                'booking_data' => $request->booking_data,
                'files' => !empty($uploadedFiles) ? $uploadedFiles : null,
                'booking_date' => now(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date ?? null,
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'notes' => $request->notes,
                'status' => Booking::STATUS_WAITING_PROVIDER_APPROVAL
            ]);

            // Create transaction if amount > 0
            if ($finalAmount > 0) {
                Transaction::create([
                    'transaction_code' => 'TRX-' . strtoupper(Str::random(8)),
                    'booking_id' => $booking->id,
                    'user_id' => Auth::id(),
                    'type' => 'payment',
                    'method' => 'bank_transfer', // Default method
                    'amount' => $finalAmount,
                    'status' => 'pending',
                ]);
            }

            // Create notification for provider
            $bookable->provider->notifications()->create([
                'title' => 'Booking Baru',
                'message' => 'Ada booking baru untuk ' . $bookable->title,
                'type' => 'new_booking',
                'data' => [
                    'booking_id' => $booking->id,
                ],
                'action_url' => route('provider.bookings.show', $booking),
            ]);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Booking berhasil dibuat! Menunggu persetujuan dari penyedia.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Booking creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function cancel(Request $request, Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if booking can be cancelled (2 days before start date)
        $startDate = \Carbon\Carbon::parse($booking->start_date);
        if ($startDate->diffInDays(now()) < 2) {
            return back()->withErrors(['error' => 'Booking hanya dapat dibatalkan 2 hari sebelum tanggal mulai.']);
        }

        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string|min:10|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason,
                'cancelled_at' => now(),
            ]);

            // Create refund transaction if payment was made
            $transaction = Transaction::where('booking_id', $booking->id)
                ->where('type', 'payment')
                ->where('status', 'success')
                ->first();

            if ($transaction) {
                Transaction::create([
                    'transaction_code' => 'RFD-' . strtoupper(Str::random(8)),
                    'booking_id' => $booking->id,
                    'user_id' => Auth::id(),
                    'type' => 'refund',
                    'method' => $transaction->method,
                    'amount' => $transaction->amount,
                    'status' => 'pending',
                ]);
            }

            // Update availability
            if ($booking->bookable_type === 'App\Models\Activity') {
                $booking->bookable->decrement('current_participants');
            }

            DB::commit();

            return back()->with('success', 'Booking berhasil dibatalkan. Proses refund akan diproses dalam 3-5 hari kerja.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    private function calculateResidenceAmount($residence, $startDate, $endDate)
    {
        $days = $startDate->diffInDays($endDate);
        
        switch ($residence->price_period) {
            case 'daily':
                return $residence->price * $days;
            case 'weekly':
                $weeks = ceil($days / 7);
                return $residence->price * $weeks;
            case 'monthly':
                $months = ceil($days / 30);
                return $residence->price * $months;
            case 'yearly':
                $years = ceil($days / 365);
                return $residence->price * $years;
            default:
                return $residence->price;
        }
    }

    private function applyDiscount($code, $bookable, $amount)
    {
        $discount = Discount::where('code', $code)
            ->where('is_active', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('discountable_id', $bookable->id)
            ->where('discountable_type', get_class($bookable))
            ->first();

        if (!$discount) {
            return null;
        }

        // Check usage limit
        if ($discount->usage_limit && $discount->used_count >= $discount->usage_limit) {
            return null;
        }

        // Check minimum amount
        if ($discount->min_amount && $amount < $discount->min_amount) {
            return null;
        }

        // Calculate discount amount
        if ($discount->type === 'percentage') {
            $discountAmount = ($amount * $discount->value) / 100;
            if ($discount->max_discount) {
                $discountAmount = min($discountAmount, $discount->max_discount);
            }
        } else {
            $discountAmount = $discount->value;
        }

        // Update usage count
        $discount->increment('used_count');

        return [
            'discount' => $discount,
            'amount' => $discountAmount
        ];
    }

    public function show(Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['bookable', 'transactions']);

        return view('bookings.show', compact('booking'));
    }

    public function checkDiscount(Request $request)
    {
        $code = $request->discount_code;
        $bookableType = $request->bookable_type;
        $bookableId = $request->bookable_id;
        $amount = $request->amount;

        $bookableModel = $bookableType;
        $bookable = $bookableModel::findOrFail($bookableId);

        $discount = $this->applyDiscount($code, $bookable, $amount);

        if ($discount) {
            return response()->json([
                'valid' => true,
                'discount_amount' => $discount['amount'],
                'final_amount' => $amount - $discount['amount'],
                'message' => 'Kode diskon berhasil diterapkan!'
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Kode diskon tidak valid atau sudah tidak berlaku.'
        ]);
    }

    public function confirmPayment(Request $request, Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'payment_method' => 'required|in:bank_transfer,e_wallet',
            'bank_name' => 'required_if:payment_method,bank_transfer|string',
            'account_number' => 'required_if:payment_method,bank_transfer|string',
            'e_wallet_type' => 'required_if:payment_method,e_wallet|in:gopay,ovo,dana,linkaja',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            // Upload payment proof
            $paymentProof = $request->file('payment_proof')->store('payments/' . Auth::id(), 'public');

            // Update transaction
            $transaction = Transaction::where('booking_id', $booking->id)
                ->where('type', 'payment')
                ->where('status', 'pending')
                ->first();

            if (!$transaction) {
                throw new \Exception('Transaction not found');
            }

            $transaction->update([
                'payment_proof' => $paymentProof,
                'payment_method' => $request->payment_method,
                'payment_details' => json_encode([
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'e_wallet_type' => $request->e_wallet_type,
                ]),
                'status' => 'waiting_confirmation',
            ]);

            DB::commit();
            return back()->with('success', 'Bukti pembayaran berhasil diunggah. Tim kami akan memverifikasi pembayaran Anda.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function history()
    {
        $bookings = Booking::with(['bookable', 'transactions'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.history', compact('bookings'));
    }

    public function downloadFile(Booking $booking, $fileType)
    {
        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $files = json_decode($booking->files, true);
        
        if (!isset($files[$fileType])) {
            abort(404);
        }

        $path = $files[$fileType];
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->download($path);
    }

    public function reschedule(Request $request, Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if booking can be rescheduled (3 days before start date)
        $startDate = \Carbon\Carbon::parse($booking->start_date);
        if ($startDate->diffInDays(now()) < 3) {
            return back()->withErrors(['error' => 'Booking hanya dapat diubah jadwal 3 hari sebelum tanggal mulai.']);
        }

        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|after:today',
            'end_date' => 'required_if:bookable_type,App\Models\Residence|date|after:start_date',
            'reason' => 'required|string|min:10|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $oldStartDate = $booking->start_date;
            $oldEndDate = $booking->end_date;

            $booking->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date ?? null,
                'reschedule_reason' => $request->reason,
                'rescheduled_at' => now(),
            ]);

            // Recalculate amount if needed
            if ($booking->bookable_type === 'App\Models\Residence') {
                $startDate = \Carbon\Carbon::parse($request->start_date);
                $endDate = \Carbon\Carbon::parse($request->end_date);
                
                $totalAmount = $this->calculateResidenceAmount($booking->bookable, $startDate, $endDate);
                $finalAmount = $totalAmount - $booking->discount_amount;

                $booking->update([
                    'total_amount' => $totalAmount,
                    'final_amount' => $finalAmount,
                ]);

                // Create new transaction if amount changed
                if ($finalAmount > $booking->transactions->where('type', 'payment')->sum('amount')) {
                    Transaction::create([
                        'transaction_code' => 'TRX-' . strtoupper(Str::random(8)),
                        'booking_id' => $booking->id,
                        'user_id' => Auth::id(),
                        'type' => 'payment',
                        'method' => 'bank_transfer',
                        'amount' => $finalAmount - $booking->transactions->where('type', 'payment')->sum('amount'),
                        'status' => 'pending',
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', 'Jadwal booking berhasil diubah.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}