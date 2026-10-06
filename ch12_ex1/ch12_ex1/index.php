<?php
// 1. Cấu hình Persistent Cookie tồn tại 3 năm
$lifetime = 60 * 60 * 24 * 365 * 3; // 3 năm (94608000 giây)
session_set_cookie_params($lifetime, '/');
session_start();

// 2. Tạo mảng giỏ hàng nếu chưa tồn tại
if (empty($_SESSION['cart'])) { 
    $_SESSION['cart'] = array(); 
}

// 3. Danh sách sản phẩm mẫu
$products = array();
$products['MMS-1754'] = array('name' => 'Flute', 'cost' => '149.50');
$products['MMS-6289'] = array('name' => 'Trumpet', 'cost' => '199.50');
$products['MMS-3408'] = array('name' => 'Clarinet', 'cost' => '299.50');

// Include các hàm xử lý giỏ hàng
require_once('cart.php');

// Lấy action từ POST hoặc GET
$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action === NULL) {
        $action = 'show_add_item';
    }
}

// Xử lý các action
switch($action) {
    case 'add':
        $product_key = filter_input(INPUT_POST, 'productkey');
        $item_qty = filter_input(INPUT_POST, 'itemqty');
        add_item($product_key, $item_qty);
        include('cart_view.php');
        break;

    case 'update':
        $new_qty_list = filter_input(INPUT_POST, 'newqty', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if ($new_qty_list !== NULL) {
            foreach($new_qty_list as $key => $qty) {
                // Đã sửa $_SESSION['cart12'] thành $_SESSION['cart']
                if (isset($_SESSION['cart'][$key]) && $_SESSION['cart'][$key]['qty'] != $qty) {
                    update_item($key, $qty);
                }
            }
        }
        include('cart_view.php');
        break;

    case 'show_cart':
        include('cart_view.php');
        break;

    case 'show_add_item':
        include('add_item_view.php');
        break;

    case 'empty_cart':
        // Đã sửa $_SESSION['cart12'] thành $_SESSION['cart']
        unset($_SESSION['cart']);
        include('cart_view.php');
        break;

    // Thêm case end_session (Cho bước 9)
    case 'end_session':
        // Xóa toàn bộ dữ liệu session
        $_SESSION = array();

        // Xóa cookie của session phía browser
        $params = session_get_cookie_params();
        $name = session_name();
        $expire = time() - 42000;
        setcookie($name, '', $expire, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

        // Hủy session trên server
        session_destroy();

        include('cart_view.php');
        break;
}
?>