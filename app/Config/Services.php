<?php

namespace Config;

use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
    public static function paymentGateway($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('paymentGateway');
        }

        return new \App\Libraries\PaymentGateway();
    }

    public static function notifier($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('notifier');
        }

        return new \App\Libraries\NotificationService();
    }
}
