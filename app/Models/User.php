<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomVerifyEmail;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'notification_preferences',
        'address',
        'city',
        'date_of_birth',
        'bsn',
        'mobile',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notification_preferences' => 'array',
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Get default notification preferences
     *
     * @return array
     */
    public static function getDefaultNotificationPreferences()
    {
        return [
            'email_reminders' => true,
            'email_marketing' => false,
            'email_weather_alerts' => true,
            'reminder_days_before' => 2,
        ];
    }

    /**
     * Check if the user is a customer.
     *
     * @return bool
     */
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    /**
     * Check if the user is an instructor.
     *
     * @return bool
     */
    public function isInstructor()
    {
        return $this->role === 'instructor';
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Get reservations made by this user (as customer)
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }

    /**
     * Get lessons assigned to this user (as instructor)
     */
    public function instructorLessons()
    {
        return $this->hasMany(Reservation::class, 'instructor_id');
    }

    /**
     * Send the email verification notification.
     * We'll keep this method for new registrations only.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
    
    /**
     * Manually mark email as verified for existing users.
     * 
     * @return bool
     */
    public function markEmailAsVerifiedIfOld()
    {
        // If user was created more than a day ago, auto-verify
        if ($this->created_at && $this->created_at->diffInDays(now()) >= 1 && !$this->hasVerifiedEmail()) {
            $this->forceFill(['email_verified_at' => now()])->save();
            return true;
        }
        
        return false;
    }
}
