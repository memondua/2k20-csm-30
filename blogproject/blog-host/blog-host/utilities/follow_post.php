<?php
require_once 'blogposts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    if (follow_post($post_id, $user_id)) {
        // Follow successful
        echo json_encode(['success' => true]);
    } else {
        // Follow failed
        echo json_encode(['success' => false]);
    }
}
?>