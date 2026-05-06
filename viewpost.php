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
    <title>BustaPosts - Viewing post #<?php echo($_GET['id']); ?></title>
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
                            $query = "SELECT * FROM posts WHERE postId = :id;";
                            $stmt = $sql->prepare($query);
                            $stmt->bindParam(":id", $_GET["id"]);
                            try {
                                $stmt->execute();
                            } catch(PDOException $e) {
                                exit("An error occurred while trying to view this post. " . $e);
                            }
                            $result = $stmt->fetch();
                            if($result["replyingTo"]) {
                                echo('
                                <div class="card w-100 my-2">
                                    <div class="card-body">
                                    <a href="viewuser.php?id=' . $result["posterId"] . '">@' . $result["posterHandle"] . '</a> at ' . $result["postTime"] . '<a class="float-end" href="viewpost.php?id=' . $result["postId"] . '">#' . $result["postId"] . '</a><br>
                                    <p class="form-text my-0"><bi class="bi bi-reply-fill"> Replying to <a href="viewpost.php?id=' . $result["replyingTo"] . '">#' . $result["replyingTo"] . '</a></p>
                                    ' . $result["postData"] . '
                                    </div>
                                </div>
                                ');
                            } else {
                                echo('
                                <div class="card w-100 my-2">
                                    <div class="card-body">
                                    <a href="viewuser.php?id=' . $result["posterId"] . '">@' . $result["posterHandle"] . '</a> at ' . $result["postTime"] . '<a class="float-end" href="viewpost.php?id=' . $result["postId"] . '">#' . $result["postId"] . '</a><br>
                                    ' . $result["postData"] . '
                                    </div>
                                </div>
                                ');
                            }
                            if(!isset($_SESSION["authenticated"])) {
                                // do nothing
                            } else {
                                echo('
                                <form action="viewpost.php?id=' . $_GET["id"] . '" method="POST">
                                    <div class="pb-3">
                                        <textarea class="form-control" maxlength="160" id="postData" name="postData" placeholder="you\'re wrong because..."></textarea>
                                        <input type="hidden" name="replyingTo" value="' . $_GET["id"] . '">
                                        <div id="postLength" class="form-text float-start">160 characters left</div>
                                        <button class="btn btn-primary float-end my-2" type="submit">post</button>
                                    </div>
                                </form>
                                ');
                            }
                            if(isset($_POST["postData"])) {
                                if(!isset($_SESSION["authenticated"])) {
                                    exit("<br>You are not authenticated!");
                                } else {
                                    $postData = $_POST['postData'];
                                    $posterHandle = $_SESSION['handle'];
                                    $posterId = $_SESSION['id'];
                                    $replyingTo = $_GET['id'];

                                    if (!isset($postData) || trim($postData) == "") {
                                        echo('
                                        <div class="card bg-danger text-white w-100">
                                            <div class="card-body pb-0">
                                                <p>You entered nothing, what the fuck do you want me to post?</p>
                                            </div>
                                        </div>
                                        ');
                                    } else {
                                        $query = "INSERT INTO posts (postData, posterHandle, posterId, replyingTo) VALUES (:postData, :posterHandle, :posterId, :replyingTo);";
                                        $stmt = $sql->prepare($query);
                                        $stmt->bindParam(":postData", $postData);
                                        $stmt->bindParam(":posterHandle", $posterHandle);
                                        $stmt->bindParam(":posterId", $posterId);
                                        $stmt->bindParam(":replyingTo", $replyingTo);

                                        try {
                                            $stmt->execute();
                                        } catch(PDOException $e) {
                                            echo('
                                            <div class="card bg-danger text-white w-100">
                                                <div class="card-body pb-0">
                                                    <p>An error occurred while creating your post. ' . $e . ' </p>
                                                </div>
                                            </div>
                                            ');
                                        }

                                        echo('
                                        <div class="card bg-success text-white w-100">
                                            <div class="card-body pb-0">
                                                <p>Successfully created your post!</p>
                                            </div>
                                        </div>
                                        ');
                                    }
                                }
                            }
                            $query = "SELECT * FROM posts WHERE replyingTo = :id;";
                            $stmt = $sql->prepare($query);
                            $stmt->bindParam(":id", $_GET["id"]);
                            try {
                                $stmt->execute();
                            } catch(PDOException $e) {
                                exit("An error occurred while trying to view this post. " . $e);
                            }
                            if($stmt->rowCount() > 0) {
                                while($row = $stmt->fetch()) {
                                    if($row["replyingTo"]) {
                                        echo('
                                        <div class="card w-100 my-2">
                                            <div class="card-body">
                                            <a href="viewuser.php?id=' . $row["posterId"] . '">@' . $row["posterHandle"] . '</a> at ' . $row["postTime"] . '<a class="float-end" href="viewpost.php?id=' . $row["postId"] . '">#' . $row["postId"] . '</a><br>
                                            <p class="form-text my-0"><bi class="bi bi-reply-fill"> Replying to <a href="viewpost.php?id=' . $row["replyingTo"] . '">#' . $row["replyingTo"] . '</a></p>
                                            ' . $row["postData"] . '
                                            </div>
                                        </div>
                                        ');
                                    } else {
                                        echo('
                                        <div class="card w-100 my-2">
                                            <div class="card-body">
                                            <a href="viewuser.php?id=' . $row["posterId"] . '">@' . $row["posterHandle"] . '</a> at ' . $row["postTime"] . '<a class="float-end" href="viewpost.php?id=' . $row["postId"] . '">#' . $row["postId"] . '</a><br>
                                            ' . $row["postData"] . '
                                            </div>
                                        </div>
                                        ');
                                    }
                                }
                            } else {
                                echo('
                                    <p class="form-text pt-5 text-center">There are no replies to this post. :-(</p>
                                ');
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
</body>
</html>