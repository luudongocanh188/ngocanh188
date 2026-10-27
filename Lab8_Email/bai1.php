<?php
require __DIR__ . '/functions.php';
startFormSession();
try {
    checkPost();
    $config = require __DIR__ . '/config.php';
    if (!filter_var($config['fixed_to'], FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Hãy cấu hình LAB_FIXED_TO bằng email người nhận của bạn.');
    }
    $mail = createMailer();
    $mail->addAddress($config['fixed_to']);
    setMessage($mail, 'Bài 1 - Gửi thư từ PHP', 'Chào bạn! Chúc bạn học tập tốt!');
    // Có thể đặt 4.jpg trong thư mục bài rồi bật dòng sau nếu muốn đính kèm ở bài 1.
    // $mail->addAttachment(__DIR__ . '/4.jpg', 'picture.jpg');
    $mail->send();
    showResult('Gửi thành công', 'Máy chủ SMTP đã chấp nhận email bài 1. Hãy kiểm tra hộp thư đến và thư rác.');
} catch (Throwable $error) {
    showError($error);
}
