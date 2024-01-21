<?php
require_once 'blogposts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    if (share_post($post_id, $user_id)) {
        // Sharing successful
        echo json_encode(['success' => true]);
    } else {
        // Sharing failed
        echo json_encode(['success' => false]);
    }
}
?>