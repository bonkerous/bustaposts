<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>