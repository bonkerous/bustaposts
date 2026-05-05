<?php
if(isset($_SESSION['authenticated'])) {
    echo('
        <div class="header">
            <a href="index.php"><img src="img/bustaPostss.jpg"></a><br>
            <a href="user?id=' . $_SESSION['id'] . '">@' . $_SESSION['handle'] . '</a> -
            <a href="logout.php">destroy session</a>
        </div>
        <hr>
        ');
        } else {
            echo('
            <div class="header">
                <a href="index.php"><img src="img/bustaPostss.jpg"></a><br>
                <a href="register.php">register</a> -
                <a href="login.php">login</a>
            </div>
            <hr>
            ');
    }
?>