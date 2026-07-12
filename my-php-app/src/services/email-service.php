<?php
require_once __DIR__ . '/../config/mail.php';
require_once __DIR__ . '/../../vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/../../vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../../vendor/phpmailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
class EmailService
{
    public static function sendAccountSetup(string $email, string $name, string $setupUrl): bool
    {
        $config = mailConfig();
        if ($config['host'] === '' || $config['username'] === '' || $config['password'] === '') return false;
        $mail = new PHPMailer(true);
        $mail->isSMTP(); $mail->Host = $config['host']; $mail->Port = $config['port']; $mail->SMTPAuth = true;
        $mail->Username = $config['username']; $mail->Password = $config['password']; $mail->SMTPSecure = $config['encryption'];
        $mail->CharSet = PHPMailer::CHARSET_UTF8; $mail->setFrom($config['from_address'], $config['from_name']);
        $mail->addAddress($email, $name); $mail->isHTML(true); $mail->Subject = 'Set up your Param account';
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); $safeUrl = htmlspecialchars($setupUrl, ENT_QUOTES, 'UTF-8');
        $mail->Body = "<p>Hello {$safeName},</p><p>Your Param staff account is ready.</p><p><a href=\"{$safeUrl}\">Choose your password</a></p><p>This one-time link expires in 24 hours.</p>";
        $mail->AltBody = "Hello {$name},\n\nSet up your account: {$setupUrl}\n\nThis one-time link expires in 24 hours.";
        return $mail->send();
    }
}
