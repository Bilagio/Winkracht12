<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationPreferenceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show the notification preferences form.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        
        // If user doesn't have notification preferences yet, set defaults
        if (!$user->notification_preferences) {
            $user->notification_preferences = User::getDefaultNotificationPreferences();
            $user->save();
        }
        
        return view('profile.notifications', compact('user'));
    }
    
    /**
     * Update the notification preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $preferences = [
            'email_reminders' => (bool) ($request->email_reminders ?? false),
            'email_marketing' => (bool) ($request->email_marketing ?? false),
            'email_weather_alerts' => (bool) ($request->email_weather_alerts ?? false),
            'reminder_days_before' => max(1, min(7, (int) $request->reminder_days_before)),
        ];
        
        $user->notification_preferences = $preferences;
        $user->save();
        
        return redirect()->route('profile.notifications')
            ->with('status', 'Your notification preferences have been updated.');
    }
}
