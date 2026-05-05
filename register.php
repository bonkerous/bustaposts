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
                            $em = $_POST["email"];
                            $pw1 = $_POST["password1"];
                            $pw2 = $_POST["password2"];

                            if (!isset($ha) || trim($ha) == "") {
                                echo("<br>No handle entered. Try again.");
                            } elseif (!preg_match("/^[0-9-a-zA-Z-'-_-.]*$/", $ha)) {
                                echo("<br>Handle contains characters not allowed. Try again. <br> Allowed characters: A-Z, 1-9, . _");
                            } elseif (!isset($em) || trim($em) == "") {
                                echo("<br>No email address entered. Try again.");
                            } elseif (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                                echo("<br>Invalid email address entered. Try again.");
                            } elseif (!isset($pw1) || trim($pw1) == "") {
                                echo("<br>No password entered. Try again.");
                            } elseif ($pw1 != $pw2) {
                                echo("<br>Passwords entered aren't the same. Try again.");
                            } else {
                                $pwHash = password_hash($pw1, PASSWORD_DEFAULT);

                                $query = "SELECT handle FROM users HAVING handle = :ha LIMIT 1;";
                                $stmt = $sql->prepare($query);
                                $stmt->bindParam(":ha", $ha);

                                try {
                                    $stmt->execute();
                                } catch(PDOException $e) {
                                    echo("An error occurred while creating your accounts. <br>" . $e);
                                }

                                if($stmt->rowCount() > 0) {
                                    exit("<br>An account with that handle exists already!");
                                } else {
                                    $query = "INSERT INTO users (handle, email, password) VALUES (:ha,:em,:pw);";
                                    $stmt = $sql->prepare($query);
                                    $stmt->bindParam(":ha", $ha);
                                    $stmt->bindParam(":em", $em);
                                    $stmt->bindParam(":pw", $pwHash);

                                    try {
                                        $stmt->execute();
                                    } catch(PDOException $e) {
                                        echo("An error occurred while creating your accounts. <br>" . $e);
                                    }
                                }
                            }
                        } else {
                            echo('
                            <h1 class="p-3 display-6">Registration</h1>
                            <form class="pt-3" action="register.php" method="POST">
                                <label for="handle" class="form-label">Handle</label>
                                <input id="handle" name="handle" class="form-control mb-2" placeholder="bob" maxlength=16>
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="bob@example.com" maxlength=40>
                                <p class="form-text">I\'ll never share your email, It\'s only for password resets.</p>
                                <label for="password1" class="form-label">Password</label>
                                <input type="password" id="password1" name="password1" class="form-control mb-2" placeholder="hunter2">
                                <label for="password2" class="form-label">Confirm password</label>
                                <input type="password" id="password2" name="password2" class="form-control mb-2" placeholder="hunter2"> 
                                <button class="btn btn-primary w-100" type="submit">Register</button>
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