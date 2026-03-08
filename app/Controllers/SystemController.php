<?php

namespace App\Controllers;

class SystemController extends BaseController
{
    public function health()
    {
        $dbStatus = 'ok';
        try {
            \Config\Database::connect()->query('SELECT 1');
        } catch (\Throwable $e) {
            $dbStatus = 'error';
        }

        return $this->response->setJSON([
            'status' => $dbStatus === 'ok' ? 'ok' : 'degraded',
            'timestamp' => gmdate('c'),
            'environment' => ENVIRONMENT,
            'services' => [
                'database' => $dbStatus,
            ],
        ]);
    }
}
