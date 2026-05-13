<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationSettingController extends Controller
{
    public function edit()
    {
        $tenant = $this->requireTenant();

        $emails = $tenant->notification_emails
            ? array_filter(array_map('trim', explode(',', $tenant->notification_emails)))
            : [];

        return view('admin.notification.edit', [
            'tenant' => $tenant,
            'emails' => $emails,
        ]);
    }

    public function update(Request $request)
    {
        $tenant = $this->requireTenant();

        $validated = $request->validate([
            'notify_on_new_order' => ['nullable', 'boolean'],
            'notification_emails' => ['nullable', 'string', 'max:1000'],
        ], [
            'notification_emails.max' => 'Daftar email terlalu panjang (maks 1000 karakter).',
        ]);

        // Validasi setiap email
        if (! empty($validated['notification_emails'])) {
            $emails = array_filter(array_map('trim', explode(',', $validated['notification_emails'])));
            foreach ($emails as $email) {
                if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return back()->withErrors(['notification_emails' => "Format email tidak valid: {$email}"])->withInput();
                }
            }
            $validated['notification_emails'] = implode(',', $emails);
        } else {
            $validated['notification_emails'] = null;
        }

        $tenant->update([
            'notify_on_new_order' => $request->boolean('notify_on_new_order'),
            'notification_emails' => $validated['notification_emails'],
        ]);

        return redirect()->route('notification.settings', ['tenant' => $tenant->slug])
            ->with('success', 'Pengaturan notifikasi berhasil disimpan.');
    }
}
