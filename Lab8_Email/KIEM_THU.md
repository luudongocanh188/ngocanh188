# Kết quả kiểm tra Lab 8

Ngày kiểm tra: 26/10/2026.

Môi trường: PHP 8.2.12, PHPMailer 7.1.1. Kiểm thử trên bản sao của bài; cấu hình SMTP của bản sao trỏ tới localhost, dùng tài khoản giả và chứng chỉ kiểm thử được tin cậy riêng cho tiến trình PHP.

| Kiểm tra | Kết quả |
| --- | --- |
| Trang trả về đủ 3 biểu mẫu | ĐẠT |
| Từ chối GET tại tệp gửi thư (405) | ĐẠT |
| Từ chối mã CSRF sai (403) | ĐẠT |
| Từ chối email sai (422) | ĐẠT |
| Bài 1: gửi thư qua STARTTLS có xác minh chứng chỉ | ĐẠT |
| Bài 2: truyền tệp đính kèm | ĐẠT |
| Giữ tiêu đề tiếng Việt và chuyển ký tự HTML trong nội dung | ĐẠT |
| Bài 3: gửi đến đúng 2 địa chỉ sau khi bỏ trùng | ĐẠT |
| Từ chối tiêu đề trống (422) | ĐẠT |
| Máy chủ SMTP giả lập không gặp lỗi | ĐẠT |
| Cú pháp của toàn bộ 7 tệp PHP | ĐẠT |

SMTP giả lập tiếp nhận 3 thư trong lần kiểm thử: bài 1, bài 2 và bài 3. Không gửi thư ra Internet.

## Chưa chạy

- Gửi Gmail thật và xác nhận thư đến hộp thư: chưa có tài khoản/mật khẩu ứng dụng của người dùng.
- Truy vấn SQL tùy chọn: chưa có database/schema.
- Kiểm tra giao diện bằng trình duyệt: chưa chạy; hiện đã kiểm tra phản hồi HTTP và xử lý phía máy chủ.
- Chưa thử từng trường hợp vượt dung lượng tệp hoặc từ chối từng người nhận tại SMTP.

Mã nguồn bàn giao giữ smtp.gmail.com:587, xác thực SMTP và xác minh chứng chỉ mặc định. Không chứa chứng chỉ, khóa hoặc tài khoản giả của kiểm thử.
