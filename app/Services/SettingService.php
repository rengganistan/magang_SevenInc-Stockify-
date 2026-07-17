<?php

namespace App\Services;

use App\Repositories\SettingRepository;

class SettingService
{
    protected SettingRepository $repository;

    public function __construct(SettingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getSetting()
    {
        $setting = $this->repository->first();

        if (!$setting) {
            $setting = $this->repository->create([
                'app_name' => 'Stockify',
                'logo' => null,
                'email' => '',
                'phone' => '',
                'address' => ''
            ]);
        }

        return $setting;
    }

    public function updateSetting($id, array $data)
    {
        return $this->repository->update($id, $data);
    }
}
