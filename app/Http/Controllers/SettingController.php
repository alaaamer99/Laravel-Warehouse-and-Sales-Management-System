<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return redirect()->route('profile.edit', ['tab' => 'settings']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string|max:500',
            'company_phone' => 'nullable|string|max:50',
            'company_email' => 'nullable|email|max:255',
            'company_website' => 'nullable|url|max:255',
            'tax_number' => 'nullable|string|max:50',
            'commercial_register' => 'nullable|string|max:50',
            'invoice_terms' => 'nullable|string|max:1000',
            'currency' => 'required|string|max:10',
            'timezone' => 'required|string|max:50',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico|max:1024',
        ]);

        $data = $request->except(['company_logo', 'company_favicon']);



        Setting::updateSettings($data);

        return redirect()->route('profile.edit', ['tab' => 'settings'])->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
