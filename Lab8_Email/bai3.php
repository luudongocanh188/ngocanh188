<?php
require __DIR__ . '/functions.php';
startFormSession();
try {
    checkPost();
    $emails = emailList(postText('emails'));
    $subject = postText('tieude');
    $content = postText('content');
    if ($subject === '' || $content === '') {
        throw new InvalidArgumentException('Tiêu đề và nội dung không được để trống.');
    }
    $mail = createMailer();
    setMessage($mail, $subject, $content);
    foreach ($emails as $email) {
        $mail->addAddress($email);
    }
    $mail->send();
    showResult('Gửi thành công', 'Máy chủ SMTP đã chấp nhận email cho ' . count($emails) . ' địa chỉ. Hãy kiểm tra các hộp thư.');
} catch (Throwable $error) {
    showError($error);
}
