<?php
require __DIR__ . '/functions.php';
startFormSession();
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lab 8 - Gửi email</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Lab 8 · Gửi email bằng PHP</h1>
    <p>Cấu hình Gmail trước khi gửi. Các nút bên dưới sẽ gửi email thật khi cấu hình hợp lệ.</p>
    <section>
        <h2>Bài 1. Gửi email cố định</h2>
        <p>Gửi lời chào đến địa chỉ đã đặt trong biến môi trường LAB_FIXED_TO.</p>
        <form action="bai1.php" method="post">
            <input type="hidden" name="csrf" value="<?= escapeHtml($_SESSION['csrf']) ?>">
            <button type="submit">Gửi email cố định</button>
        </form>
    </section>
    <section>
        <h2>Bài 2. Gửi email có tệp đính kèm</h2>
        <form action="mail.php" enctype="multipart/form-data" method="post">
            <input type="hidden" name="csrf" value="<?= escapeHtml($_SESSION['csrf']) ?>">
            <label>Email người nhận <input type="email" name="email" required placeholder="nguoinhan@example.com"></label>
            <label>Tiêu đề <input type="text" name="tieude" required maxlength="150"></label>
            <label>Nội dung <textarea name="content" id="editor" rows="6" required maxlength="20000"></textarea></label>
            <label>Tệp đính kèm (không bắt buộc, tối đa 5 MB) <input type="file" name="file"></label>
            <button type="submit">Gửi email</button>
        </form>
    </section>
    <section>
        <h2>Bài 3. Gửi đến nhiều địa chỉ</h2>
        <form action="bai3.php" method="post">
            <input type="hidden" name="csrf" value="<?= escapeHtml($_SESSION['csrf']) ?>">
            <label>Danh sách email <textarea name="emails" rows="3" required placeholder="a@example.com, b@example.com"></textarea></label>
            <p class="hint">Ngăn cách bằng dấu phẩy, chấm phẩy hoặc xuống dòng. Địa chỉ trùng chỉ nhận một lần; tối đa 20 địa chỉ.</p>
            <label>Tiêu đề <input type="text" name="tieude" required maxlength="150"></label>
            <label>Nội dung <textarea name="content" rows="6" required maxlength="20000"></textarea></label>
            <button type="submit">Gửi đến danh sách</button>
        </form>
    </section>
</main>
</body>
</html>
