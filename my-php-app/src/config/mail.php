<?php
function mailConfig(): array
{
    return [
        'host' => getenv('MAIL_HOST') ?: '', 'port' => (int) (getenv('MAIL_PORT') ?: 587),
        'username' => getenv('MAIL_USERNAME') ?: '', 'password' => getenv('MAIL_PASSWORD') ?: '',
        'encryption' => getenv('MAIL_ENCRYPTION') ?: 'tls',
        'from_address' => getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@param.test',
        'from_name' => getenv('MAIL_FROM_NAME') ?: 'Param Clothing Line',
    ];
}
