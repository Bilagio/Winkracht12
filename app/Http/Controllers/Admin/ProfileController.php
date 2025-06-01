<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the owner profile form
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        // Expanded list of country codes with names
        $countryCodes = [
            // Europe
            'nl' => ['name' => 'Netherlands', 'code' => '+31'],
            'be' => ['name' => 'Belgium', 'code' => '+32'],
            'de' => ['name' => 'Germany', 'code' => '+49'],
            'fr' => ['name' => 'France', 'code' => '+33'],
            'gb' => ['name' => 'United Kingdom', 'code' => '+44'],
            'es' => ['name' => 'Spain', 'code' => '+34'],
            'it' => ['name' => 'Italy', 'code' => '+39'],
            'ch' => ['name' => 'Switzerland', 'code' => '+41'],
            'at' => ['name' => 'Austria', 'code' => '+43'],
            'se' => ['name' => 'Sweden', 'code' => '+46'],
            'dk' => ['name' => 'Denmark', 'code' => '+45'],
            'no' => ['name' => 'Norway', 'code' => '+47'],
            'fi' => ['name' => 'Finland', 'code' => '+358'],
            'pt' => ['name' => 'Portugal', 'code' => '+351'],
            'ie' => ['name' => 'Ireland', 'code' => '+353'],
            'gr' => ['name' => 'Greece', 'code' => '+30'],
            'pl' => ['name' => 'Poland', 'code' => '+48'],
            'cz' => ['name' => 'Czech Republic', 'code' => '+420'],
            'ro' => ['name' => 'Romania', 'code' => '+40'],
            'hu' => ['name' => 'Hungary', 'code' => '+36'],
            
            // North America
            'us' => ['name' => 'United States', 'code' => '+1'],
            'ca' => ['name' => 'Canada', 'code' => '+1'],
            'mx' => ['name' => 'Mexico', 'code' => '+52'],
            
            // South and Central America
            'br' => ['name' => 'Brazil', 'code' => '+55'],
            'ar' => ['name' => 'Argentina', 'code' => '+54'],
            'co' => ['name' => 'Colombia', 'code' => '+57'],
            'cl' => ['name' => 'Chile', 'code' => '+56'],
            'pe' => ['name' => 'Peru', 'code' => '+51'],
            'ec' => ['name' => 'Ecuador', 'code' => '+593'],
            'uy' => ['name' => 'Uruguay', 'code' => '+598'],
            
            // Asia
            'cn' => ['name' => 'China', 'code' => '+86'],
            'jp' => ['name' => 'Japan', 'code' => '+81'],
            'kr' => ['name' => 'South Korea', 'code' => '+82'],
            'in' => ['name' => 'India', 'code' => '+91'],
            'th' => ['name' => 'Thailand', 'code' => '+66'],
            'vn' => ['name' => 'Vietnam', 'code' => '+84'],
            'id' => ['name' => 'Indonesia', 'code' => '+62'],
            'my' => ['name' => 'Malaysia', 'code' => '+60'],
            'sg' => ['name' => 'Singapore', 'code' => '+65'],
            'ph' => ['name' => 'Philippines', 'code' => '+63'],
            
            // Middle East
            'ae' => ['name' => 'United Arab Emirates', 'code' => '+971'],
            'sa' => ['name' => 'Saudi Arabia', 'code' => '+966'],
            'tr' => ['name' => 'Turkey', 'code' => '+90'],
            'il' => ['name' => 'Israel', 'code' => '+972'],
            'qa' => ['name' => 'Qatar', 'code' => '+974'],
            
            // Africa
            'za' => ['name' => 'South Africa', 'code' => '+27'],
            'eg' => ['name' => 'Egypt', 'code' => '+20'],
            'ma' => ['name' => 'Morocco', 'code' => '+212'],
            'ng' => ['name' => 'Nigeria', 'code' => '+234'],
            'ke' => ['name' => 'Kenya', 'code' => '+254'],
            'gh' => ['name' => 'Ghana', 'code' => '+233'],
            
            // Oceania
            'au' => ['name' => 'Australia', 'code' => '+61'],
            'nz' => ['name' => 'New Zealand', 'code' => '+64'],
            'fj' => ['name' => 'Fiji', 'code' => '+679'],
            
            // Additional European countries
            'lu' => ['name' => 'Luxembourg', 'code' => '+352'],
            'bg' => ['name' => 'Bulgaria', 'code' => '+359'],
            'hr' => ['name' => 'Croatia', 'code' => '+385'],
            'cy' => ['name' => 'Cyprus', 'code' => '+357'],
            'ee' => ['name' => 'Estonia', 'code' => '+372'],
            'lv' => ['name' => 'Latvia', 'code' => '+371'],
            'lt' => ['name' => 'Lithuania', 'code' => '+370'],
            'mt' => ['name' => 'Malta', 'code' => '+356'],
            'sk' => ['name' => 'Slovakia', 'code' => '+421'],
            'si' => ['name' => 'Slovenia', 'code' => '+386'],
            
            // Additional Asian countries
            'hk' => ['name' => 'Hong Kong', 'code' => '+852'],
            'tw' => ['name' => 'Taiwan', 'code' => '+886'],
            'kh' => ['name' => 'Cambodia', 'code' => '+855'],
            'np' => ['name' => 'Nepal', 'code' => '+977'],
            'pk' => ['name' => 'Pakistan', 'code' => '+92'],
            'bd' => ['name' => 'Bangladesh', 'code' => '+880'],
        ];
        
        // Sort countries alphabetically by name
        uasort($countryCodes, function($a, $b) {
            return $a['name'] <=> $b['name'];
        });
        
        $user = Auth::user();
        
        // Parse existing phone number to separate country code and number
        $countryCode = '+31'; // Default to Netherlands
        $phoneNumber = '';
        
        if ($user->mobile) {
            // Extract country code using regex
            if (preg_match('/^(\+\d+)\s*(.*)$/', $user->mobile, $matches)) {
                $countryCode = $matches[1];
                $phoneNumber = $matches[2];
            } else {
                // If no country code format found, use the whole number
                $phoneNumber = $user->mobile;
            }
        }
        
        return view('admin.profile', compact('user', 'countryCodes', 'countryCode', 'phoneNumber'));
    }

    /**
     * Update the owner profile information
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => [
                'nullable', 
                'date', 
                'before_or_equal:'.now()->subYears(18)->format('Y-m-d'),
                'after_or_equal:1950-01-01'
            ],
            'bsn' => ['nullable', 'numeric', 'digits_between:8,9'],
            'country_code' => ['required_with:phone_number', 'string'],
            'phone_number' => ['nullable', 'string', 'max:15'],
        ]);
        
        // Combine country code and phone number for storage
        if (!empty($validated['phone_number'])) {
            $validated['mobile'] = $validated['country_code'] . ' ' . $validated['phone_number'];
        } else {
            $validated['mobile'] = null;
        }
        
        // Remove the fields that are not in the users table
        unset($validated['country_code'], $validated['phone_number']);
        
        $user->update($validated);

        return redirect()->route('admin.profile.edit')->with('status', 'Profile updated successfully.');
    }
}
