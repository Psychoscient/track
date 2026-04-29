<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv -> load();

    $email = $_ENV['MAIL_USERNAME'];
    $pass = $_ENV['MAIL_PASSWORD'];
    $from_email = $_ENV['MAIL_FROM'];

    return [
        'host' => 'smtp.gmail.com',
        'username' => $email,
        'password' => $pass,
        'port' => 587,
        'encryption' => 'tls',
        'from_email' => $from_email,
        'from_name' => 'UST Track'
    ];
?>