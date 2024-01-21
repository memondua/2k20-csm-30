<?php
require_once 'utilities/blogposts.php';
require_once 'utilities/user.php';

if (!is_user_loggedin()) {
    header("Location: index.php");
    return;
}

$blogposts = get_all_posts();
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
        <h1>This is the home page</h1>
        <?php
        if ($blogposts != null) :
            foreach ($blogposts as $blogpost) :
                $author = $blogpost["user_full_name"];
                $post_id = $blogpost["post_id"];
                $post_title = $blogpost["post_title"];
                $post_body = $blogpost["post_body"];
                $likes = $blogpost["likes"];
                $reads = $blogpost["_reads"];
                $followers = $blogpost["followers"];
                $shares = $blogpost["shares"];
                $post_date = $blogpost["post_date"]; // String object
                $post_date = date_create($post_date); // DateTime object
                $post_date = date_format($post_date, "jS, F, Y.");
        ?>
                <section class="blogpost">
                    <div class="blogtitle"><?= $post_title ?></div>
                    <div class="blogauthor">By <?= $author ?></div>
                    <div><?= $post_body ?>. <a href="post.php?id=<?= $post_id ?>">Read more...</a></div>
                    <div class="blogpostfooter">
                        <!-- Note: Never expose database ids in URLs -->
                        <?php if ($likes > 0) : ?>
                            <a href="postlikes.php?id=<?= $post_id ?>">
                        <?php endif; ?>
                        <span class="blogdate"><small><i class="count"><?= $likes ?></i> people like the blog.</small></span>
                        <?php if ($likes > 0) : ?>
                            </a>
                        <?php endif; ?>

                        <?php if ($reads > 0) : ?>
                            <a href="postreads.php?id=<?= $post_id ?>">
                        <?php endif; ?>
                        <span class="blogdate"><small><i class="count"><?= $reads ?></i> people have read the blog.</small></span>
                        <?php if ($reads > 0) : ?>
                            </a>
                        <?php endif; ?>

                        <?php if ($followers > 0) : ?>
                            <a href="postfollowers.php?id=<?= $post_id ?>">
                        <?php endif; ?>
                        <span class="blogdate"><small><i class="count"><?= $followers ?></i> people follow the blog.</small></span>
                        <?php if ($followers > 0) : ?>
                            </a>
                        <?php endif; ?>

                        <?php if ($shares > 0) : ?>
                            <a href="postshares.php?id=<?= $post_id ?>">
                        <?php endif; ?>
                        <span class="blogdate"><small><i class="count"><?= $shares ?></i> people have shared the blog.</small></span>
                        <?php if ($shares > 0) : ?>
                            </a>
                        <?php endif; ?>

                        <div class="blogdate"><small>Posted on: <?= $post_date ?></small></div>
                    </div>
                </section>
        <?php
            endforeach;
        endif;
        ?>
    </div>
</body>

</html>