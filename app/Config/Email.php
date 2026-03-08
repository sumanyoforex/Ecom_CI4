<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail = '';
    public string $fromName = 'ShopCI4';
    public string $recipients = '';
    public string $userAgent = 'CodeIgniter';
    public string $protocol = 'smtp';
    public string $mailPath = '/usr/sbin/sendmail';
    public string $SMTPHost = '';
    public string $SMTPAuthMethod = 'login';
    public string $SMTPUser = '';
    public string $SMTPPass = '';
    public int $SMTPPort = 587;
    public int $SMTPTimeout = 5;
    public bool $SMTPKeepAlive = false;
    public string $SMTPCrypto = 'tls';
    public bool $wordWrap = true;
    public int $wrapChars = 76;
    public string $mailType = 'html';
    public string $charset = 'UTF-8';
    public bool $validate = false;
    public int $priority = 3;
    public string $CRLF = "\r\n";
    public string $newline = "\r\n";
    public bool $BCCBatchMode = false;
    public int $BCCBatchSize = 200;
    public bool $DSN = false;

    public function __construct()
    {
        parent::__construct();

        $this->fromEmail = (string)env('MAIL_FROM_EMAIL', $this->fromEmail);
        $this->fromName = (string)env('MAIL_FROM_NAME', $this->fromName);
        $this->protocol = (string)env('MAIL_PROTOCOL', $this->protocol);
        $this->SMTPHost = (string)env('MAIL_SMTP_HOST', $this->SMTPHost);
        $this->SMTPPort = (int)env('MAIL_SMTP_PORT', $this->SMTPPort);
        $this->SMTPUser = (string)env('MAIL_SMTP_USER', $this->SMTPUser);
        $this->SMTPPass = (string)env('MAIL_SMTP_PASS', $this->SMTPPass);
        $this->SMTPCrypto = (string)env('MAIL_SMTP_CRYPTO', $this->SMTPCrypto);
    }
}
