<?php
use PHPMailer\PHPMailer\PHPMailer;

function escapeHtml(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function postText(string $name): string
{
    return isset($_POST[$name]) && is_string($_POST[$name]) ? trim($_POST[$name]) : '';
}

function emailList(string $input): array
{
    $items = preg_split('/[,;\s]+/', trim($input), -1, PREG_SPLIT_NO_EMPTY);
    if (!$items) {
        throw new InvalidArgumentException('Vui lòng nhập địa chỉ email.');
    }
    $emails = [];
    foreach ($items as $email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email không hợp lệ: ' . $email);
        }
        // So sánh không phân biệt hoa thường để tránh gửi trùng trong bài lab.
        $emails[strtolower($email)] = $email;
    }
    if (count($emails) > 20) {
        throw new InvalidArgumentException('Mỗi lần chỉ gửi tối đa 20 địa chỉ trong bài lab.');
    }
    return array_values($emails);
}

function createMailer(): PHPMailer
{
    if (!is_file(__DIR__ . '/vendor/autoload.php')) {
        throw new RuntimeException('Thiếu PHPMailer. Hãy chạy composer install trong thư mục bài.');
    }
    require_once __DIR__ . '/vendor/autoload.php';
    $config = require __DIR__ . '/config.php';
    if (!filter_var($config['username'], FILTER_VALIDATE_EMAIL) || $config['password'] === '') {
        throw new RuntimeException('Chưa cấu hình SMTP_USERNAME và SMTP_PASSWORD. Xem README.md.');
    }
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = $config['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['username'];
    $mail->Password = $config['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $config['port'];
    $mail->Timeout = 15;
    $mail->SMTPDebug = 0;
    $mail->setFrom($config['username'], $config['from_name']);
    $mail->isHTML(true);
    return $mail;
}

function setMessage(PHPMailer $mail, string $subject, string $content): void
{
    if ($subject === '' || $content === '') {
        throw new InvalidArgumentException('Tiêu đề và nội dung không được để trống.');
    }
    if (strlen($subject) > 500 || strlen($content) > 100000) {
        throw new InvalidArgumentException('Tiêu đề hoặc nội dung quá dài.');
    }
    $mail->Subject = $subject;
    $mail->Body = '<h2>Xin chào bạn!</h2><p>' . nl2br(escapeHtml($content)) . '</p>';
    $mail->AltBody = "Xin chào bạn!\n\n" . $content;
}

function attachUpload(PHPMailer $mail): void
{
    if (!isset($_FILES['file'])) {
        return;
    }
    $file = $_FILES['file'];
    if (!isset($file['error']) || !is_int($file['error'])) {
        throw new InvalidArgumentException('Dữ liệu tệp không hợp lệ.');
    }
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new InvalidArgumentException('Tải tệp thất bại. Kiểm tra dung lượng và cấu hình PHP.');
    }
    if (!is_string($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        throw new InvalidArgumentException('Tệp tải lên không hợp lệ.');
    }
    if (filesize($file['tmp_name']) > 5 * 1024 * 1024) {
        throw new InvalidArgumentException('Tệp đính kèm tối đa 5 MB.');
    }
    $name = is_string($file['name']) ? basename(str_replace('\\', '/', $file['name'])) : 'attachment';
    $mail->addAttachment($file['tmp_name'], $name);
}

function startFormSession(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
}

function checkPost(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        header('Allow: POST');
        throw new InvalidArgumentException('Chỉ chấp nhận yêu cầu POST.');
    }
    if (!hash_equals($_SESSION['csrf'], postText('csrf'))) {
        http_response_code(403);
        throw new InvalidArgumentException('Phiên biểu mẫu không hợp lệ. Hãy mở lại trang gửi email.');
    }
}

function showResult(string $title, string $message): void
{
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!doctype html><html lang="vi"><meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . escapeHtml($title) . '</title><link rel="stylesheet" href="style.css">';
    echo '<main><h1>' . escapeHtml($title) . '</h1><p>' . escapeHtml($message) . '</p>';
    echo '<a href="index.php">Về trang bài tập</a></main></html>';
}

function showError(Throwable $error): void
{
    if ($error instanceof InvalidArgumentException) {
        if (http_response_code() < 400) {
            http_response_code(422);
        }
        showResult('Chưa gửi email', $error->getMessage());
    } elseif ($error instanceof RuntimeException && !($error instanceof \PHPMailer\PHPMailer\Exception)) {
        http_response_code(503);
        showResult('Thiếu cấu hình', $error->getMessage());
    } else {
        http_response_code(502);
        showResult('Gửi email thất bại', 'Hãy kiểm tra kết nối Internet, mật khẩu ứng dụng và cấu hình SMTP.');
    }
}
