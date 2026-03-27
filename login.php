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
            $pw = $_POST["password"];

            if (!isset($ha) || trim($ha) == "") {
                exit("<br>No handle entered. Try again.");
            }
            elseif (!isset($pw) || trim($pw) == "") {
                exit("<br>No password entered. Try again.");
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
                    exit("<br>An account with that handle doesn't exist!");
                }
            }
        } else {
            echo('
            <form action="login.php" method="POST">
                handle: <input name="handle" placeholder="@bob" maxlength=16> <br>
                password: <input type="password" name="password" placeholder="hunter2"> <br>
                <button type="submit">login</button>
            </form>
            ');
        }
        ?>
</body>
</html>