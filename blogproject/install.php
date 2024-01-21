<?php

require_once 'config.php';

$table_blogpost = "
CREATE TABLE IF NOT EXISTS blogpost (
    post_id int AUTO_INCREMENT,
    post_title varchar(200) NOT NULL,
    post_body text NOT NULL,
    post_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    followers int NOT NULL DEFAULT 0,
    shares int NOT NULL DEFAULT 0,
    CONSTRAINT PK_blogpost_post_id PRIMARY KEY (post_id)
);";

$insert_statment = "INSERT INTO blogpost (post_title, post_body, post_date) VALUES (:post_title, :post_body, :post_date)";

$blogs = [
    [
        "post_title" => "My first blog post",
        "post_body" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti aperiam provident libero quisquam velit. Libero illum blanditiis, rem minus expedita consequuntur iusto! Ex, earum magnam dignissimos alias expedita nostrum impedit asperiores corporis non eos! Ipsum, est consequatur? Deleniti rem culpa vitae nulla. Quaerat placeat necessitatibus dolore modi illo quae ab.",
        "post_date" => "2019-10-20" // YYYY-MM-DD
    ],
    [
        "post_title" => "This is my second blog post",
        "post_body" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti aperiam provident libero quisquam velit. Libero illum blanditiis, rem minus expedita consequuntur iusto! Ex, earum magnam dignissimos alias expedita nostrum impedit asperiores corporis non eos! Ipsum, est consequatur? Deleniti rem culpa vitae nulla. Quaerat placeat necessitatibus dolore modi illo quae ab.",
        "post_date" => "2020-10-10" // YYYY-MM-DD
    ],
    [
        "post_title" => "My recent blog post, a bit longer",
        "post_body" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti aperiam provident libero quisquam velit. Libero illum blanditiis, rem minus expedita consequuntur iusto! Ex, earum magnam dignissimos alias expedita nostrum impedit asperiores corporis non eos! Ipsum, est consequatur? Deleniti rem culpa vitae nulla. Quaerat placeat necessitatibus dolore modi illo quae ab. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptate consectetur, laudantium aspernatur ducimus autem quisquam praesentium molestiae esse illo et eligendi, veniam deserunt saepe obcaecati ea! Nostrum rerum eum quaerat iure, debitis libero eos! Adipisci ad vel amet, dolores id, at animi, voluptas veritatis accusamus repellat perspiciatis fuga! Dolorem, numquam!",
        "post_date" => "2021-09-21" // YYYY-MM-DD
    ]
];

try {
    $db_connection = new PDO("mysql:host=localhost", DB_USER, DB_PASS);

    $db_connection->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);

    $db_connection = new PDO("mysql:host=localhost;dbname=" . DB_NAME, DB_USER, DB_PASS);
    $db_connection->exec($table_blogpost);

    // In case page refresh, do not insert rows repeatedly.
    $row_count = (int) $db_connection->query("SELECT count(*) FROM blogpost")->fetchColumn();

    // If there are no rows (which means the table is just created), add some rows.
    if ($row_count === 0) {

        $blog_post_title = "";
        $blog_post_body = "";
        $blog_post_date = "";

        $statment = $db_connection->prepare($insert_statment);
        $statment->bindParam(":post_title", $blog_post_title);
        $statment->bindParam(":post_body", $blog_post_body);
        $statment->bindParam(":post_date", $blog_post_date);

        foreach ($blogs as $blog) {
            $blog_post_title = $blog['post_title'];
            $blog_post_body = $blog['post_body'];
            $blog_post_date = $blog['post_date'];

            $statment->execute();
        }
    }

    $success = true;
} catch (PDOException $e) {
    echo $e->getMessage();
    $success = false;
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
        $update_statement = "UPDATE blogpost SET followers = :followers WHERE post_id = :post_id";

        try {
            $update_statment = $db_connection->prepare($update_statement);
            $update_statment->bindValue(':followers', $new_followers, PDO::PARAM_INT);
            $update_statment->bindValue(':post_id', $post_id, PDO::PARAM_INT);
            $update_statment->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } elseif (isset($_POST["share"])) {
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
        $update_statement = "UPDATE blogpost SET shares = :shares WHERE post_id = :post_id";

        try {
            $update_statment = $db_connection->prepare($update_statement);
            $update_statment->bindValue(':shares', $new_shares, PDO::PARAM_INT);
            $update_statment->bindValue(':post_id', $post_id, PDO::PARAM_INT);
            $update_statment->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

$message = $success ?
    "Congratulations: installation is successful" :
    "Oops: installation is unsuccessful";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style.css">
    <title>Installation Status</title>
</head>

<body>
    <h1><?= $message ?></h1>
</body>

</html>
