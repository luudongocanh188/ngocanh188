<?php
// 1. Kết nối cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "tintuc");
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

// 2. Kiểm tra khi người dùng bấm nút "Them"
if (isset($_POST['Them'])) {
    $tenTL  = isset($_POST['TenTL']) ? trim($_POST['TenTL']) : '';
    $thuTu  = isset($_POST['ThuTu']) ? $_POST['ThuTu'] : 0;
    $anHien = isset($_POST['AnHien']) ? $_POST['AnHien'] : 1;

    // Kiểm tra tên thể loại không được để trống
    if (empty($tenTL)) {
        echo "<script>alert('Tên thể loại không được để trống!'); window.history.back();</script>";
        exit();
    }

    // 3. Xử lý upload file hình ảnh (lấy từ input name="image")
    $tenFileAnh = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $thuMucLuu = "../image/"; // Thư mục lưu ảnh trên máy của bạn
        
        // Nếu chưa có thư mục thì tự động tạo
        if (!file_exists($thuMucLuu)) {
            mkdir($thuMucLuu, 0777, true);
        }

        // Đổi tên file để tránh trùng lặp bằng hàm time()
        $tenFileAnh = time() . "_" . basename($_FILES['image']['name']);
        $duongDanDich = $thuMucLuu . $tenFileAnh;

        // Tiến hành di chuyển file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $duongDanDich)) {
            $tenFileAnh = ""; 
        }
    }

    // 4. Lưu vào CSDL (Khớp chính xác cột 'icon' trong cơ sở dữ liệu của bạn)
    $sql = "INSERT INTO theloai (TenTL, ThuTu, AnHien, icon) VALUES ('$tenTL', '$thuTu', '$anHien', '$tenFileAnh')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Thêm thể loại thành công!'); window.location.href='theloai.php';</script>";
    } else {
        echo "Lỗi SQL: " . mysqli_error($conn);
    }
} else {
    // Nếu truy cập trực tiếp file này, điều hướng về trang danh sách
    header("Location: theloai.php");
    exit();
}
?>