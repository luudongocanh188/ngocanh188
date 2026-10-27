<?php
require __DIR__ . '/functions.php';
startFormSession();
try {
    checkPost();
    $email = postText('email');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Email người nhận không hợp lệ.');
    }
    $subject = postText('tieude');
    $content = postText('content');
    if ($subject === '' || $content === '') {
        throw new InvalidArgumentException('Tiêu đề và nội dung không được để trống.');
    }
    $mail = createMailer();
    $mail->addAddress($email);
    setMessage($mail, $subject, $content);
    attachUpload($mail);
    $mail->send();
    showResult('Gửi thành công', 'Máy chủ SMTP đã chấp nhận email bài 2. Hãy kiểm tra hộp thư đến và thư rác.');
} catch (Throwable $error) {
    showError($error);
}
