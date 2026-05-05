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
    <title>Document</title>
</head>
<body>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-3">
                    <?php include("parts/header.php"); ?>
                </div>
                <div class="col-12 col-md-6">
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
                            echo('
                                <div class="card w-100 my-2">
                                    <div class="card-body">
                                    <a href="viewuser.php?id=' . $result["posterId"] . '">@' . $result["posterHandle"] . '</a> - <a href="viewpost.php?id=' . $result["postId"] . '">Direct link</a><br>
                                    ' . $result["postData"] . '
                                    </div>
                            </div>');
                        } catch(PDOException $e) {
                            echo("Couldn't connect to database. " . $e);
                        }
                    ?>
                </div>
                <div class="col-12 col-md-3">
                    News
                </div>
            </div>
        </div>
    </div>
</body>
</html>