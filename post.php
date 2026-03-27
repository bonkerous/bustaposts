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
            exit("<br>You are not authenticated!");
        } else {
            $postData = $_POST['postData'];
            $posterHandle = $_SESSION['handle'];
            $posterId = $_SESSION['id'];

            if (!isset($postData) || trim($postData) == "") {
                exit("<br>You entered nothing, what the fuck do you want me to post?");
            } else {
                try {
                    $sql = new PDO("mysql:host=localhost;dbname=bp", $sqlUser, $sqlPass);
                } catch(PDOException $e) {
                    exit("<br>Couldn't connect to database. " . $e);
                }

                $query = "INSERT INTO posts (postData, posterHandle, posterId) VALUES (:postData, :posterHandle, :posterId);";
                $stmt = $sql->prepare($query);
                $stmt->bindParam(":postData", $postData);
                $stmt->bindParam(":posterHandle", $posterHandle);
                $stmt->bindParam(":posterId", $posterId);

                try {
                    $stmt->execute();
                } catch(PDOException $e) {
                    exit("An error occurred while posting your post. " . $e);
                }

                echo('Successfully posted your post! <a href="index.php">Return to index</a>');
            }
        }
    ?>    
</body>
</html>