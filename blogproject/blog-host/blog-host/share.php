<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    try {
        $db_connection = new PDO("mysql:host=localhost;dbname=" . DB_NAME, DB_USER, DB_PASS);

        // Increment the share count for the blog post
        $sql = "UPDATE BlogPostShares SET share_count = share_count + 1 WHERE post_id = :post_id AND user_id = :user_id";
        $statement = $db_connection->prepare($sql);
        $statement->bindParam(':post_id', $post_id);
        $statement->bindParam(':user_id', $user_id);
        $statement->execute();

        // Return success response
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // Return error response
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>