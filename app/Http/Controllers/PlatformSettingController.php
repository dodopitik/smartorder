<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlatformSettingController extends Controller
{
    public function edit(): View
    {
        $settings = [
            'platform_name' => AppSetting::getValue('platform_name', 'Archana Order'),
            'support_email' => AppSetting::getValue('support_email', ''),
            'support_phone' => AppSetting::getValue('support_phone', ''),
            'monthly_fee_per_order' => AppSetting::getValue('monthly_fee_per_order', '1000'),
            'billing_cycle_note' => AppSetting::getValue('billing_cycle_note', ''),
            'hero_message' => AppSetting::getValue('hero_message', ''),
        ];

        return view('platform.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'platform_name' => ['required', 'string', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'support_phone' => ['nullable', 'string', 'max:30'],
            'monthly_fee_per_order' => ['required', 'integer', 'min:0'],
            'billing_cycle_note' => ['nullable', 'string', 'max:1000'],
            'hero_message' => ['nullable', 'string', 'max:1000'],
        ]);

        foreach ($validated as $key => $value) {
            AppSetting::setValue($key, (string) $value);
        }

        return redirect()->route('platform.settings.edit')->with('success', 'Global setting berhasil diperbarui.');
    }
}
