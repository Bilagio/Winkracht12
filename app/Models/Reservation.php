<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'instructor_id',
        'lesson_package_id',
        'location_id',
        'date',
        'time',
        'participants',
        'additional_participants',
        'total_price',
        'status',
        'payment_status',
        'invoice_number',
        'notes',
        'payment_reference',
        'payment_method',
        'payment_confirmed_at',
        'payment_confirmed_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'payment_confirmed_at' => 'datetime',
        'additional_participants' => 'array',
    ];

    /**
     * Get the user that owns the reservation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the instructor for the reservation.
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the lesson package associated with this reservation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lessonPackage()
    {
        return $this->belongsTo(LessonPackage::class);
    }

    /**
     * Get the location associated with this reservation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Generate a unique invoice number
     *
     * @return string
     */
    public static function generateInvoiceNumber()
    {
        $year = date('Y');
        $month = date('m');
        $latestInvoice = self::whereNotNull('invoice_number')
            ->where('invoice_number', 'like', "INV-$year$month-%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        $sequence = 1;
        if ($latestInvoice) {
            $parts = explode('-', $latestInvoice->invoice_number);
            $sequence = intval(end($parts)) + 1;
        }

        return "INV-$year$month-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the cancellation request for this reservation (if exists)
     */
    public function cancellation()
    {
        return $this->hasOne(Cancellation::class);
    }

    /**
     * Check if the reservation is eligible for cancellation
     */
    public function canBeCancelled()
    {
        // Can only cancel if status is confirmed or pending
        if (!in_array($this->status, ['confirmed', 'pending'])) {
            return false;
        }
        
        // Check if the reservation date is in the future
        return $this->date->isFuture();
    }

    /**
     * Check if the reservation has a pending cancellation request
     */
    public function hasPendingCancellation()
    {
        return $this->cancellation && $this->cancellation->status === 'pending';
    }

    /**
     * Check if this reservation is a rescheduled one
     */
    public function isRescheduled()
    {
        return !is_null($this->original_reservation_id);
    }
}
