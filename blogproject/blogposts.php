<?php

require_once 'config.php';

$select_statment = "SELECT * FROM blogpost";

try {
    $db_connection = new PDO("mysql:host=localhost;dbname=" . DB_NAME, DB_USER, DB_PASS);

    $statment = $db_connection->prepare($select_statment);
    $statment->execute();
    $blogposts = $statment->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    // Handle exception
}

// Function to update followers count for a blog post
function updateFollowers($post_id, $new_followers)
{
    global $db_connection;
    
    $update_statement = "UPDATE blogpost SET followers = :followers WHERE post_id = :post_id";
    
    try {
        $update_statment = $db_connection->prepare($update_statement);
        $update_statment->bindValue(':followers', $new_followers, PDO::PARAM_INT);
        $update_statment->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $update_statment->execute();
    }
    catch (PDOException $e) {
        // Handle exception
    }
}

// Function to update shares count for a blog post
function updateShares($post_id, $new_shares)
{
    global $db_connection;
    
    $update_statement = "UPDATE blogpost SET shares = :shares WHERE post_id = :post_id";
    
    try {
        $update_statment = $db_connection->prepare($update_statement);
        $update_statment->bindValue(':shares', $new_shares, PDO::PARAM_INT);
        $update_statment->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $update_statment->execute();
    }
    catch (PDOException $e) {
        // Handle exception
    }
}

// Process followers and shares
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["follow"])) {
        $post_id = $_POST["post_id"];
        
        // Retrieve current followers count
        $select_statement = "SELECT followers FROM blogpost WHERE post_id = :post_id";
        $select_statment = $db_connection->prepare($select_statement);
        $select_statment->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $select_statment->execute();
        $row = $select_statment->fetch(PDO::FETCH_ASSOC);
        $current_followers = $row["followers"];
        
        // Increment followers count by 1
        $new_followers = $current_followers + 1;
        
        // Update followers count in the database
        updateFollowers($post_id, $new_followers);
    }
    elseif (isset($_POST["share"])) {
        $post_id = $_POST["post_id"];
        
        // Retrieve current shares count
        $select_statement = "SELECT shares FROM blogpost WHERE post_id = :post_id";
        $select_statment = $db_connection->prepare($select_statement);
        $select_statment->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $select_statment->execute();
        $row = $select_statment->fetch(PDO::FETCH_ASSOC);
        $current_shares = $row["shares"];
        
        // Increment shares count by 1
        $new_shares = $current_shares + 1;
        
        // Update shares count in the database
        updateShares($post_id, $new_shares);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>My Blog</title>
</head>
<body>
<h1>My Blogs</h1>
<?php
if (!empty($blogposts)):
    foreach($blogposts as $blogpost):
        $post_id = $blogpost["post_id"];
        $post_title = $blogpost["post_title"];
        $post_body = $blogpost["post_body"];
        $post_date = $blogpost["post_date"]; // String object
        $post_date = date_create($post_date); // DateTime object
        $post_date = date_format($post_date,"jS, F, Y.");
        $followers = $blogpost["followers"];
        $shares = $blogpost["shares"];
?>
<section class="blogpost">
    <div class="blogtitle"><?=$post_title?></div>
    <div><?=$post_body?></div>
    <div class="blogdate"><small>Posted on: <?=$post_date?></small></div>
    
    <Continuing from the previous response:
?>
    <div class="bloginteractions">
        <form action="" method="post" class="followForm">
            <input type="hidden" name="post_id" value="<?=$post_id?>">
            <button type="submit" name="follow">Follow</button>
            <span><?=$followers?></span> followers
        </form>
        
        <form action="" method="post" class="shareForm">
            <input type="hidden" name="post_id" value="<?=$post_id?>">
            <button type="submit" name="share">Share</button>
            <span><?=$shares?></span> shares
        </form>
    </div>
</section>
<?php
    endforeach;
endif;
?>

</body>
</html>