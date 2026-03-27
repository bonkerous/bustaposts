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
                exit("<br>No handle entered. Try again.");
            }
            elseif (!preg_match("/^[0-9-a-zA-Z-'-_-.]*$/", $ha)) {
                exit("<br>Handle contains characters not allowed. Try again. <br> Allowed characters: A-Z, 1-9, . _");
            }
            elseif (!isset($em) || trim($em) == "") {
                echo("<br>No email address entered. Try again.");
            }
            elseif (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                exit("<br>Invalid email address entered. Try again.");
            }
            elseif (!isset($pw1) || trim($pw1) == "") {
                exit("<br>No password entered. Try again.");
            }
            elseif ($pw1 != $pw2) {
                exit("<br>Passwords entered aren't the same. Try again.");
            }
            else {
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
            <form action="register.php" method="POST">
                handle: <input name="handle" placeholder="@bob" maxlength=16> <br>
                email: <input type="email" name="email" placeholder="bob@example.com" maxlength=40> <br>
                password: <input type="password" name="password1" placeholder="hunter2"> <br>
                confirm password: <input type="password" name="password2" placeholder="hunter2"> <br>
                <button type="submit">register</button>
            </form>
            ');
        }
        ?>
</body>
</html>