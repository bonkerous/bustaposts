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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
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
                        try {
                            $sql = new PDO("mysql:host=localhost;dbname=bp", $sqlUser, $sqlPass);
                            $query = "SELECT * FROM users WHERE id = :id LIMIT 1;";
                            $stmt = $sql->prepare($query);
                            $stmt->bindParam(":id", $_GET["id"]);
                            $stmt->execute();
                            try {
                                $stmt->execute();
                            } catch(PDOException $e) {
                                exit("An error occurred while trying to view this post. " . $e);
                            }
                            if($stmt->rowCount() > 0) {
                                while($row = $stmt->fetch()) {
                                    echo('
                                        <h1 class="display-6">@' . $row["handle"] . '</h1>
                                        <p class="form-text">User ID: ' . $row["id"] . '</p>
                                        <hr>
                                        ');
                                }
                            } else {
                                echo('
                                <div class="text-center">
                                    <a href="index.php"><img class="img-fluid w-50" src="img/busta_error.jpg"></a>
                                    <h1>This user does not exist!</h1>
                                    <p class="form-text">Click Busta to go back to the index.</p>
                                </div>
                                ');
                            }
                            $query = "SELECT * FROM posts WHERE posterId = :id ORDER BY postId DESC;";
                            $stmt = $sql->prepare($query);
                            $stmt->bindParam(":id", $_GET["id"]);
                            $stmt->execute();
                            if($stmt->rowCount() > 0) {
                                    while($row = $stmt->fetch()) {
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
    <div class="container-fluid">
        <div class="container text-center">
            <a class="text-decoration-none" href="privacy.php">Privacy</a> | <a class="text-decoration-none" href="terms.php">Terms of Service</a>
        </div>
    </div>
    <script src="js/postLength.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>