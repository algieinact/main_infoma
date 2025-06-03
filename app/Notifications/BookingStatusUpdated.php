<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Booking $booking,
        protected string $status,
        protected ?string $reason = null
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = match($this->status) {
            'provider_approved' => 'Booking Anda telah disetujui oleh provider.',
            'provider_rejected' => 'Booking Anda telah ditolak oleh provider.',
            default => 'Status booking Anda telah diperbarui.',
        };

        $mail = (new MailMessage)
            ->subject('Update Status Booking')
            ->greeting('Halo ' . $notifiable->name)
            ->line($message)
            ->line('Kode Booking: ' . $this->booking->booking_code)
            ->line('Item: ' . $this->booking->bookable->title);

        if ($this->reason) {
            $mail->line('Alasan: ' . $this->reason);
        }

        return $mail->action('Lihat Detail Booking', route('bookings.show', $this->booking))
            ->line('Terima kasih telah menggunakan layanan kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'status' => $this->status,
            'reason' => $this->reason,
            'message' => match($this->status) {
                'provider_approved' => 'Booking Anda telah disetujui oleh provider.',
                'provider_rejected' => 'Booking Anda telah ditolak oleh provider.',
                default => 'Status booking Anda telah diperbarui.',
            },
        ];
    }
} 