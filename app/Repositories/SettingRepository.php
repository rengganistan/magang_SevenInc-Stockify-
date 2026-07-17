<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository
{
    public function first()
    {
        return Setting::first();
    }

    public function create(array $data)
    {
        return Setting::create($data);
    }

    public function update($id, array $data)
    {
        $setting = Setting::findOrFail($id);

        $setting->update($data);

        return $setting;
    }
}
