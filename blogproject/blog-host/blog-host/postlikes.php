<?php
require_once 'utilities/blogposts.php';
require_once 'utilities/user.php';

if (!is_user_loggedin()) {
    header("Location: index.php");
    return;
}

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
}

$users = get_users_who_like($post_id);

// Process followers and shares
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["follow"])) {
        $follower_id = get_loggedin_user_id();
        
        // Add the follower to the database
        add_follower($post_id, $follower_id);
    } elseif (isset($_POST["share"])) {
        $post_id = $_POST["post_id"];
        
        // Increment the shares count for the post
        increment_shares($post_id);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="style.css">
    <title>Home</title>
</head>

<body>
    <?php include "header.php"; ?>

    <div style="text-align: center">
        <h1>User details who like the post</h1>
        <?php
        if ($users != null):
            foreach ($users as $user):
                $user_name = $user["user_full_name"];
        ?>
                <div class="username"><?= $user_name ?></div>
        <?php
            endforeach;
        endif;
        ?>

        <div class="bloginteractions">
            <form action="" method="post" class="followForm">
                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                <button type="submit" name="follow">Follow</button>
            </form>

            <form action="" method="post" class="shareForm">
                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                <button type="submit" name="share">Share</button>
            </form>
        </div>
    </div>
</body>

</html>
