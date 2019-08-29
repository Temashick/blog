<?php
namespace MyProject\Services;

use MyProject\Models\Users\User;

class EmailSender
{
    public static function send(
        User $receiver,
        string $subject,
        string $templateName,
        array $templateVars = []
    ): void {
        extract($templateVars);

        ob_start();

        require __DIR__ . '/../../../templates/mail/' . $templateName;
        $body = ob_get_contents();
        ob_end_clean();

        mail($receiver->getEmail(), $subject, $body, 'Content-Type: text/html; charset=UTF-8'); // mail() автоматически отправляет сообщение message получателю to. Можно специфицировать несколько получателей, разделив запятой адреса в to. С помощью этой функции можно высылать Email с присоединением/attachment и содержимое специальных типов. Это делается через MIME-кодировку
    }
}