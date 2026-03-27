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
    ?>
    <?php
        try {
            $sql = new PDO("mysql:host=localhost;dbname=bp", $sqlUser, $sqlPass);
            $query = "SELECT * FROM posts WHERE postId = :id;";
            $stmt = $sql->prepare($query);
            $stmt->bindParam(":id", $_GET["id"]);
            try {
                $stmt->execute();
            } catch(PDOException $e) {
                exit("An error occurred while trying to view this post. " . $e);
            }
            $result = $stmt->fetch();
            echo('<div class="postBody">
                <a href="viewuser.php?id=' . $result["posterId"] . '">@' . $result["posterHandle"] . '</a> - <a href="viewpost.php?id=' . $result["postId"] . '">Direct link</a><br>
                ' . $result["postData"] . '
            </div>');
        } catch(PDOException $e) {
            echo("Couldn't connect to database. " . $e);
        }
    ?>
</body>
</html>