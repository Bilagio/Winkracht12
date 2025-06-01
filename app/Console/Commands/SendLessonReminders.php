<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Models\User;
use App\Notifications\LessonReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendLessonReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send lesson reminders to users based on their preferences';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Sending lesson reminders...');
        $count = 0;

        // Get all users with upcoming reservations who have enabled reminders
        $users = User::whereHas('reservations', function ($query) {
            // Get reservations in the future
            $query->where('status', 'confirmed')
                  ->where('date', '>=', Carbon::today()->format('Y-m-d'));
        })->get();

        foreach ($users as $user) {
            // Skip users who haven't set up preferences or disabled reminders
            if (!isset($user->notification_preferences['email_reminders']) || 
                !$user->notification_preferences['email_reminders']) {
                continue;
            }
            
            // Get the number of days before to send the reminder
            $daysBefore = $user->notification_preferences['reminder_days_before'] ?? 2;
            
            // Find reservations that need reminders today
            $reservationsToRemind = $user->reservations()
                ->where('status', 'confirmed')
                ->where('date', Carbon::today()->addDays($daysBefore)->format('Y-m-d'))
                ->get();
            
            foreach ($reservationsToRemind as $reservation) {
                try {
                    $user->notify(new LessonReminder($reservation));
                    $this->info("Sent reminder to {$user->email} for reservation #{$reservation->id}");
                    $count++;
                } catch (\Exception $e) {
                    Log::error("Failed to send reminder for reservation #{$reservation->id}: " . $e->getMessage());
                    $this->error("Failed to send reminder for reservation #{$reservation->id}");
                }
            }
        }

        $this->info("Sent {$count} reminders successfully.");
        return Command::SUCCESS;
    }
}
