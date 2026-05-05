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
                <div class="col-12 col-md-3 border-end border-primary">
                    <?php include("parts/header.php"); ?>
                </div>
                <div class="col-12 col-md-6">
                    <?php
                        if(isset($_POST["handle"])) {
                            try {
                                $sql = new PDO("mysql:host=localhost;dbname=bp", $sqlUser, $sqlPass);
                            } catch(PDOException $e) {
                                echo("Oops, " . $e);
                            }

                            $ha = $_POST["handle"];
                            $pw = $_POST["password"];

                            if (!isset($ha) || trim($ha) == "") {
                                echo("<br>No handle entered. Try again.");
                            }
                            elseif (!isset($pw) || trim($pw) == "") {
                                echo("<br>No password entered. Try again.");
                            }
                            else {
                                $query = "SELECT id, handle, password FROM users HAVING handle = :ha LIMIT 1;";
                                $stmt = $sql->prepare($query);
                                $stmt->bindParam(":ha", $ha);

                                try {
                                    $stmt->execute();
                                } catch(PDOException $e) {
                                    echo("An error occurred while logging into your account. <br>" . $e);
                                }

                                if($stmt->rowCount() > 0) {
                                    $result = $stmt->fetch();
                                    if(password_verify($pw, $result['password'])) {
                                        $_SESSION['authenticated'] = TRUE;
                                        $_SESSION['handle'] = $result['handle'];
                                        $_SESSION['id'] = $result['id'];
                                        echo("<br>Logged in as @" . $ha . "!");
                                    }
                                } else {
                                    echo("<br>An account with that handle doesn't exist!");
                                }
                            }
                        } else {
                            echo('
                                <h1 class="p-3 display-6">Login</h1>
                                <form class="pt-3" action="login.php" method="POST">
                                    <label for="handle" class="form-label">Handle</label>
                                    <input id="handle" name="handle" class="form-control mb-2" placeholder="bob" maxlength=16>
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" name="password" class="form-control mb-2" placeholder="hunter2">
                                    <button class="btn btn-primary w-100" type="submit">Log in</button>
                                </form>
                                ');
                            }
                    ?>
                </div>
                <div class="col-12 col-md-3 border-start border-primary">
                    <div class="sticky-top pt-3">
                        <p class="text-center">News</p>
                        <?php
                            try {
                                $sql = new PDO("mysql:host=localhost;dbname=bp", $sqlUser, $sqlPass);
                            } catch(PDOException $e) {
                                echo("Oops, " . $e);
                            }
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
</body>
</html>