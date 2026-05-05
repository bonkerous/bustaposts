<?php
if(isset($_SESSION['authenticated'])) {
    echo('
        <div class="header sticky-top pt-3">
            <a href="index.php"><img class="img-fluid" src="img/bustaPostss.jpg"></a><br>
            <a class="btn text-start w-100" href="viewuser.php?id=' . $_SESSION['id'] . '" role="button">
                @' . $_SESSION['handle'] . '
            </a>
            <a class="btn text-start w-100" href="logout.php" role="button">
                Log out
            </a>
        </div>
        ');
        } else {
            echo('
            <div class="header sticky-top pt-3">
                <a href="index.php"><img class="img-fluid" src="img/bustaPostss.jpg"></a><br>
                <a class="btn text-start w-100" href="register.php" role="button">Register</a>
                <a class="btn text-start w-100" href="login.php" role="button">Log in</a>
            </div>
            ');
    }
?>