<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Document</title>
</head>
<body>
    <?php
        include("settings.php");
        include("parts/header.php");
    
    if(!isset($_SESSION["authenticated"])) {
        // do nothing
    } else {
        echo('<form action="post.php" method="POST">
                <textarea name="postData" placeholder="today i..."></textarea> <br>
                <button type="submit">post</button>
        </form>');
    }
        try {
            $sql = new PDO("mysql:host=localhost;dbname=bp", $sqlUser, $sqlPass);
            $query = $sql->query("SELECT * FROM posts ORDER BY postId DESC");
            if($query->rowCount() > 0) {
                while($row = $query->fetch()) {
                    echo('<div class="postBody">
                        <a href="viewuser.php?id=' . $row["posterId"] . '">@' . $row["posterHandle"] . '</a> - <a href="viewpost.php?id=' . $row["postId"] . '">Direct link</a><br>
                        ' . $row["postData"] . '
                    </div>');
                }
            }
        } catch(PDOException $e) {
            echo("Couldn't connect to database. " . $e);
        }
    ?>
</body>
</html>