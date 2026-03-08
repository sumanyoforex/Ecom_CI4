<?php

namespace App\Libraries;

use App\Models\NotificationLogModel;

class NotificationService
{
    public function sendEmail(?int $userId, string $to, string $subject, string $message): bool
    {
        $logs = new NotificationLogModel();
        $logId = $logs->insert([
            'user_id' => $userId,
            'channel' => 'email',
            'subject' => $subject,
            'recipient' => $to,
            'body' => $message,
            'status' => 'queued',
            'created_at' => date('Y-m-d H:i:s'),
        ], true);

        if (!$to || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $logs->update($logId, [
                'status' => 'failed',
                'error_message' => 'Invalid recipient email',
            ]);
            return false;
        }

        $emailConfig = config('Email');
        if ($emailConfig->fromEmail === '') {
            $logs->update($logId, [
                'status' => 'skipped',
                'error_message' => 'Email sender not configured',
            ]);
            return false;
        }

        try {
            $email = service('email');
            $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName ?: 'ShopCI4');
            $email->setTo($to);
            $email->setSubject($subject);
            $email->setMessage($message);

            $sent = $email->send(false);
            if ($sent) {
                $logs->update($logId, [
                    'status' => 'sent',
                    'sent_at' => date('Y-m-d H:i:s'),
                ]);
                return true;
            }

            $logs->update($logId, [
                'status' => 'failed',
                'error_message' => strip_tags((string)$email->printDebugger(['headers'])),
            ]);
            return false;
        } catch (\Throwable $e) {
            $logs->update($logId, [
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
