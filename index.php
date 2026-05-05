<?php session_start();
include("settings.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Document</title>
</head>
<body>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-3 border-end border-primary">
                    <?php include("parts/header.php"); ?>
                </div>
                <div class="col-12 col-md-6 pt-3">
                    <?php
                        if(!isset($_SESSION["authenticated"])) {
                            // do nothing
                        } else {
                            echo('
                            <form action="post.php" method="POST">
                                <div class="pb-3">
                                    <textarea class="form-control" maxlength="160" id="postData" name="postData" placeholder="today i..."></textarea>
                                    <div id="postLength" class="form-text float-start">160 characters left</div>
                                    <button class="btn btn-primary float-end my-2" type="submit">post</button>
                                </div>
                            </form>
                            ');
                        }
                            try {
                                $sql = new PDO("mysql:host=localhost;dbname=bp", $sqlUser, $sqlPass);
                                $query = $sql->query("SELECT * FROM posts ORDER BY postId DESC");
                                if($query->rowCount() > 0) {
                                    while($row = $query->fetch()) {
                                        echo('
                                            <div class="card w-100 my-2">
                                                <div class="card-body">
                                                <a href="viewuser.php?id=' . $row["posterId"] . '">@' . $row["posterHandle"] . '</a> - <a href="viewpost.php?id=' . $row["postId"] . '">Direct link</a><br>
                                                ' . $row["postData"] . '
                                                </div>
                                        </div>');
                                    }
                                }
                            } catch(PDOException $e) {
                                echo("Couldn't connect to database. " . $e);
                            }
                    ?>
                </div>
                <div class="col-12 col-md-3 border-start border-primary">
                    <div class="sticky-top pt-3">
                        <p class="text-center">News</p>
                        <?php
                            $query = $sql->query("SELECT * FROM news");
                            if($query->rowCount() > 0) {
                                while($row = $query->fetch()) {
                                    echo('
                                        <div class="card w-100 my-2">
                                            <div class="card-body">
                                            <p>' . $row["newsPoster"] . ' at ' . $row["newsDate"] . '</p>
                                            <p>' . $row["newsData"] . '</p>
                                            </div>
                                    </div>');
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/postLength.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>