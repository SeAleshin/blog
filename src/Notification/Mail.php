<?php

namespace Blog\App\Notification;

class Mail
{
    public static function send(string|array $to, string $text, string $subject, string $name): void
    {
        $headers = 'From: sendphptest@yandex.ru' . "\r\n" .
            'Reply-To: ' . $to  . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        $message = '
        <html>
            <head>
                <title>'. $subject .'</title>
            </head>
            <body>
                <p>Добрый день, '. $name .'!</p>
                <p>' . $text . '</p>
            </body>
        </html>
        ';

        mail($to, $subject, $message, $headers);
    }
}
