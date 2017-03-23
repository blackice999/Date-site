<?php
switch($_POST['cmd']) {
    case 'logout':
        $_SESSION['authenticated'] = false;
        session_destroy();
        header("Location: index.php");
        break;

    case 'login':
        if(CourseDAO::getInstance()->authorizeAccess($_POST['username'], $_POST['password'])) {

                //Set a token to the session
                $_SESSION['token'] = CourseDAO::getInstance()->getToken();
                $_SESSION['authenticated'] = true;
            } else {
                $bad_login = true;
            }

        break;
}

?>