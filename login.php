<?php
session_start();
require_once 'CourseDAO.php';
require_once 'session.php';
?>
<html>
<head>
    <title> Login</title>
    <link rel="stylesheet" href="css/main.css" type="text/css">
</head>
<body>
    <div class="content">

        <?php if (isset($_POST['username'])) {
            echo "Welcome:" . $_POST['username'];
        }

        if($_SESSION['authenticated'] === true && isset($_SESSION['authenticated'])) { ?>
            <div id="logout-form">
                <form method="post" action="">
                    <input type="hidden" value="logout" name="cmd">
                    <input type="submit" value="Logout">
                </form>
            </div> <br />
            <a href="register.php"> Register</a> <br />
            <a href="index.php"> Go back</a>
        <?php
        } else { ?>
        <div id="login-form">
            <?php if (isset($bad_login) && $bad_login === true) {?>
                <div class="error_message">Bad login!</div>
            <?php } ?>
            <form method="post" action="">
                <input type="hidden" name="cmd" value="login">
                <label>
                    User:
                    <input type="text" name="username" required>
                </label>
                <br />

                <label>
                    Pass:
                    <input type="password" name="password" required>
                </label>

                <br />
                <input type="submit" value="Login" style="position: relative; top: 10px;">
            </form>
            <?php } ?>
        </div>
    </div>
</body>
</html>