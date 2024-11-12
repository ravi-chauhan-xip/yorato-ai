<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SettingsController extends Controller
{
    public function index(): Renderable
    {
        return view('admin.settings.index');
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'system_password' => app()->isProduction() ? 'required' : '',
            'sms_enabled' => 'required|boolean',
            'tds_percent' => 'required|numeric|min:0.01',
            'admin_charge_percent' => 'required|numeric|min:0',
            'social_link' => 'required|boolean',
            'address_enabled' => 'required|boolean',
            'primary_color' => 'required',
            'primary_color_hover' => 'required',
            'payment_gateway' => 'required|boolean',
            'is_ecommerce' => 'required|boolean',
        ]);

        if (app()->isProduction() && ! Hash::check($request->input('system_password'), '$2y$10$cMAgE8B0Bg7BodqP4OJNx.tQzf/QLRJekhicuqhi4HwvGSkLEpeDS')) {
            return redirect()->route('admin.settings.index')
                ->with(['error' => 'System Password is incorrect']);
        }

        settings(['sms_enabled' => (bool) $request->input('sms_enabled')]);
        settings(['is_ecommerce' => (bool) $request->input('is_ecommerce')]);
        settings(['front_template' => $request->input('front_template')]);
        settings(['tds_percent' => (float) $request->input('tds_percent')]);
        settings(['admin_charge_percent' => (float) $request->input('admin_charge_percent')]);
        settings(['social_link' => (bool) $request->input('social_link')]);
        settings(['address_enabled' => (bool) $request->input('address_enabled')]);
        settings(['primary_color' => $request->input('primary_color')]);
        settings(['primary_color_hover' => $request->input('primary_color_hover')]);
        settings(['payment_gateway' => (bool) $request->input('payment_gateway')]);

        return redirect()->route('admin.settings.index')
            ->with(['success' => 'Settings updated successfully']);
    }

    public function content(Request $request): RedirectResponse|Renderable
    {
        return view('admin.settings.content', [
            'about_us' => settings('about_us'),
            'terms' => settings('terms'),
            'privacy_policy' => settings('privacy_policy'),
            'vision_mission' => settings('vision_mission'),
            'founder_message' => settings('founder_message'),
        ]);
    }
}
