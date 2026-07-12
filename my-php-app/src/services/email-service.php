<?php

require_once __DIR__ . '/../config/mail.php';
require_once __DIR__ . '/../../vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/../../vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../../vendor/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

class EmailService
{
    public static function sendAccountSetup(
        string $recipientEmail,
        string $recipientName,
        string $setupUrl
    ): bool {
        $mailSettings = mailConfig();

        if (!self::hasSmtpCredentials($mailSettings)) {
            return false;
        }

        $email = new PHPMailer(true);
        $email->isSMTP();
        $email->Host = $mailSettings['host'];
        $email->Port = $mailSettings['port'];
        $email->SMTPAuth = true;
        $email->Username = $mailSettings['username'];
        $email->Password = $mailSettings['password'];
        $email->SMTPSecure = $mailSettings['encryption'];
        $email->CharSet = PHPMailer::CHARSET_UTF8;

        $email->setFrom(
            $mailSettings['from_address'],
            $mailSettings['from_name']
        );
        $email->addAddress($recipientEmail, $recipientName);
        $email->isHTML(true);
        $email->Subject = 'Set up your Param account';
        $email->Body = self::buildHtmlMessage($recipientName, $setupUrl);
        $email->AltBody = self::buildPlainTextMessage($recipientName, $setupUrl);

        return $email->send();
    }

    private static function hasSmtpCredentials(array $mailSettings): bool
    {
        return $mailSettings['host'] !== ''
            && $mailSettings['username'] !== ''
            && $mailSettings['password'] !== '';
    }

    private static function buildHtmlMessage(string $recipientName, string $setupUrl): string
    {
        $safeName = htmlspecialchars($recipientName, ENT_QUOTES, 'UTF-8');
        $safeUrl = htmlspecialchars($setupUrl, ENT_QUOTES, 'UTF-8');

        return "<p>Hello {$safeName},</p>"
            . '<p>Your Param staff account is ready.</p>'
            . "<p><a href=\"{$safeUrl}\">Choose your password</a></p>"
            . '<p>This one-time link expires in 24 hours.</p>';
    }

    private static function buildPlainTextMessage(
        string $recipientName,
        string $setupUrl
    ): string {
        return "Hello {$recipientName},\n\n"
            . "Set up your account: {$setupUrl}\n\n"
            . 'This one-time link expires in 24 hours.';
    }
}
