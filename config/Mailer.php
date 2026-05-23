<?php

class Mailer {
    private $from;

    public function __construct() {
        $this->from = getenv('MAIL_FROM') ?: 'no-reply@advisorhub.local';
    }

    public function send($to, $subject, $body, $headers = []) {
        $headers[] = 'From: ' . $this->from;
        $headers[] = 'Content-Type: text/plain; charset=utf-8';
        $headers_str = implode("\r\n", $headers);
        // In production, replace with a robust mailer (SMTP/PHPMailer). For now use mail()
        return mail($to, $subject, $body, $headers_str);
    }
}
