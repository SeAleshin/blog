<?php

namespace Blog\App\Notification;

class RestorePassword
{
    public static function send(string|array $to, string $code, string $subject, string $name): void
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
                <p>Ваш временный код - ' . $code . '</p>
                <p>Если вы не делали этот запрос, проигнорируйте это сообщение. Также, рекомендуем изменить пароль.</p>
            </body>
        </html>
        ';

        mail($to, $subject, $message, $headers);
    }
}