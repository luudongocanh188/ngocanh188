<?php
// 1. Kết nối cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "tintuc");
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

// 2. Lấy idTL từ URL chuyển sang
$idTL = isset($_GET['idTL']) ? intval($_GET['idTL']) : 0;

// 3. Truy vấn lấy thông tin cũ của thể loại cần sửa
$sql = "SELECT * FROM theloai WHERE idTL = $idTL";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result); // Đã bổ sung $result vào đây

// Nếu không tìm thấy dữ liệu, quay về trang danh sách
if (!$row) {
    echo "<script>alert('Không tìm thấy thể loại!'); window.location.href='theloai.php';</script>";
    exit();
}

$tenTL  = $row['TenTL'];
$thuTu  = $row['ThuTu'];
$anHien = $row['AnHien'];
$icon   = $row['icon'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sửa thể loại</title>
</head>
<body>
<!-- Form gửi dữ liệu sang file xử lý sửa (theloai_sua_xl.php) -->
<form action="theloai_sua_xl.php" method="post" enctype="multipart/form-data" name="form1">
    <!-- Input ẩn chứa idTL để biết đang sửa bản ghi nào -->
    <input type="hidden" name="idTL" value="<?php echo $idTL; ?>" />
    
    <table align="left" width="500">
        <tr>
            <td align="right">Ten The Loai</td>
            <td><input type="text" name="TenTL" value="<?php echo $tenTL; ?>" /></td>
        </tr>
        <tr>
            <td align="right">Thu Tu</td>
            <td><input type="text" name="ThuTu" value="<?php echo $thuTu; ?>" /></td>
        </tr>
        <tr>
            <td align="right">An Hien</td>
            <td>
                <select name="AnHien">
                    <option value="0" <?php if($anHien == 0) echo "selected='selected'"; ?>>An</option>
                    <option value="1" <?php if($anHien == 1) echo "selected='selected'"; ?>>Hien</option>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right">Icon cũ</td>
            <td>
                <?php if (!empty($icon)) { ?>
                    <img src="../image/<?php echo $icon; ?>" width="50" height="50" /><br>
                <?php } ?>
                <input type="hidden" name="icon_cu" value="<?php echo $icon; ?>" />
            </td>
        </tr>
        <tr>
            <td align="right">Chọn Icon mới</td>
            <td><input type="file" name="image" id="anh" /></td>
        </tr>
        <tr>
            <td align="right"><input type="submit" name="Sua" value="Sua" /></td>
            <td><input type="reset" name="Huy" value="Huy" /></td>
        </tr>
    </table>
</form>
</body>
</html>