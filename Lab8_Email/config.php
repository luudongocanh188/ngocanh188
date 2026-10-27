<?php
// Đặt các giá trị bí mật bằng biến môi trường trước khi chạy PHP.
return [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => getenv('SMTP_USERNAME') ?: '',
    'password' => str_replace(' ', '', getenv('SMTP_PASSWORD') ?: ''),
    'from_name' => getenv('SMTP_FROM_NAME') ?: 'Sinh viên - Lab 8',
    'fixed_to' => getenv('LAB_FIXED_TO') ?: '',
];
