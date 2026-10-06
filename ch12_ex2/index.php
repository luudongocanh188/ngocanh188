<?php
// 1. Cấu hình session tồn tại trong 1 năm
$lifetime = 60 * 60 * 24 * 365; // 1 năm (31536000 giây)
session_set_cookie_params($lifetime, '/');
session_start();

// 2. Tạo mảng task_list trong session nếu chưa có
if (empty($_SESSION['task_list'])) {
    $_SESSION['task_list'] = array();
}

$action = filter_input(INPUT_POST, 'action');
$errors = array();

switch( $action ) {
    case 'add':
        $new_task = filter_input(INPUT_POST, 'newtask');
        if (empty($new_task)) {
            $errors[] = 'The new task cannot be empty.';
        } else {
            // Thêm vào mảng trong session
            $_SESSION['task_list'][] = $new_task;
        }
        break;

    case 'delete':
        $task_index = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
        if ($task_index === NULL || $task_index === FALSE) {
            $errors[] = 'The task cannot be deleted.';
        } else {
            // Xóa khỏi mảng trong session
            unset($_SESSION['task_list'][$task_index]);
            $_SESSION['task_list'] = array_values($_SESSION['task_list']);
        }
        break;
}

include('task_list.php');
?>