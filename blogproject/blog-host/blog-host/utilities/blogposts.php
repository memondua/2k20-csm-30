<?php
require_once 'db.php';

function get_all_posts() {
    try {
        $db_connection = db_connect();

        $select_statement = "
        SELECT DISTINCT b.post_id, b.user_id, post_title, SUBSTRING(post_body, 1, 150) as post_body, post_date, user_full_name, 
        (SELECT COUNT(*) FROM BlogPostLikes WHERE post_id = b.post_id) as likes, 
        (SELECT COUNT(*) FROM BlogPostReads WHERE post_id = b.post_id) as _reads,
        (SELECT COUNT(*) FROM BlogPostFollowers WHERE post_id = b.post_id) as followers,
        (SELECT COUNT(*) FROM BlogPostShares WHERE post_id = b.post_id) as shares
        FROM BlogPost b 
        JOIN User u ON b.user_id = u.user_id 
        LEFT JOIN BlogPostLikes bl on bl.post_id=b.post_id 
        LEFT JOIN BlogPostReads br on br.post_id=b.post_id 
        WHERE b.post_public = 1";

        $select_statement = $db_connection->prepare($select_statement);

        $select_statement->execute();
        $blogposts = $select_statement->fetchAll(PDO::FETCH_ASSOC);

        return !empty($blogposts) ? $blogposts : null;
    } catch (PDOException $e) {
        var_dump($e);
        return null;
    }
}

function get_users_who_like($post_id) {
    $select_statement = "
        SELECT u.user_full_name FROM BlogPostLikes b JOIN User u ON b.user_id = u.user_id WHERE post_id = :post_id;";

    return get_users($post_id, $select_statement);
}

function get_users_who_read($post_id) {
    $select_statement = "
        SELECT u.user_full_name FROM BlogPostReads b JOIN User u ON b.user_id = u.user_id WHERE post_id = :post_id;";

    return get_users($post_id, $select_statement);
}

function get_users_who_follow($post_id) {
    $select_statement = "
        SELECT u.user_full_name FROM BlogPostFollowers b JOIN User u ON b.user_id = u.user_id WHERE post_id = :post_id;";

    return get_users($post_id, $select_statement);
}

function get_users($post_id, $select_statement) {
    try {
        $db_connection = db_connect();

        $select_statement = $db_connection->prepare($select_statement);

        $select_statement->bindParam(":post_id", $post_id);

        $select_statement->execute();
        $users = $select_statement->fetchAll(PDO::FETCH_ASSOC);

        return !empty($users) ? $users : null;
    } catch (PDOException $e) {
        var_dump($e);
        return null;
    }
}

function share_post($post_id, $user_id) {
    try {
        $db_connection = db_connect();

        $insert_statement = "
        INSERT INTO BlogPostShares (post_id, user_id) VALUES (:post_id, :user_id);";

        $insert_statement = $db_connection->prepare($insert_statement);

        $insert_statement->bindParam(":post_id", $post_id);
        $insert_statement->bindParam(":user_id", $user_id);

        $insert_statement->execute();

        return true;
    } catch (PDOException $e) {
        var_dump($e);
        return false;
    }
}

function share_post($post_id, $user_id) {
    try {
        $db_connection = db_connect();

        $insert_statement = "
        INSERT INTO BlogPostShares (post_id, user_id) VALUES (:post_id, :user_id);";

        $insert_statement = $db_connection->prepare($insert_statement);

        $insert_statement->bindParam(":post_id", $post_id);
        $insert_statement->bindParam(":user_id", $user_id);

        $insert_statement->execute();

        return true;
    } catch (PDOException $e) {
        var_dump($e);
        return false;
    }
}

function follow_post($post_id, $user_id) {
    try {
        $db_connection = db_connect();

        $insert_statement = "
        INSERT INTO BlogPostFollowers (post_id, user_id) VALUES (:post_id, :user_id);";

        $insert_statement = $db_connection->prepare($insert_statement);

        $insert_statement->bindParam(":post_id", $post_id);
        $insert_statement->bindParam(":user_id", $user_id);

        $insert_statement->execute();

        return true;
    } catch (PDOException $e) {
        var_dump($e);
        return false;
    }
}
?>