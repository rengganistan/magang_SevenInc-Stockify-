<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    protected SettingService $service;

    public function __construct(
        SettingService $service
    )
    {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $setting = $this->service->getSetting();

        return view(
            'settings.index',
            compact('setting')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    )
    {
        $validated = $request->validate([

            'app_name' => 'required|max:100',

            'email' => 'nullable|email',

            'phone' => 'nullable|max:20',

            'address' => 'nullable',

            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'

        ]);

        if ($request->hasFile('logo')) {

            $validated['logo'] = $request
                ->file('logo')
                ->store('settings', 'public');
        }

        $this->service->updateSetting(
            $id,
            $validated
        );

        // Bust cache navbar setting supaya perubahan nama & logo langsung tampil
        Cache::forget('nav_setting');

        return redirect()
            ->route('settings.index')
            ->with(
                'success',
                'Pengaturan berhasil diperbarui.'
            );
    }
}
