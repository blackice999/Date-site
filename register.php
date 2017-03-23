<html>
    <head>
        <title> Register</title>
        <link rel="stylesheet" type="text/css" href="css/forms.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">

        <script type="text/javascript">
            function validate_form() {

                var errorMessage = document.getElementsByClassName("error_message")[0];
                errorMessage.innerHTML = '';

                if(document.register.first_name.value == '') {
                    errorMessage.innerHTML = "Please specify a first name <br />";
//                    document.register.first_name.focus();
//                    return false;
                }

                if(document.register.last_name.value == '') {
                    errorMessage.innerHTML += "Please specify a last name <br />";
//                    document.register.last_name.focus();
//                    return false;
                }

                if(document.register.email.value == '') {
                   errorMessage.innerHTML += "Please specify an email <br />";
//                    document.register.email.focus();
//                    return false;
                }

                if(document.register.birth_date.value == '') {
                   errorMessage.innerHTML += "Please specify a birth date <br />";
//                   document.register.birth_date.focus();
//                   return false;
                }

                if(document.register.gender.value == '-1') {
                    errorMessage.innerHTML += "Please specify a gender <br />";
//                    return false;
                }

                if(document.register.password.value == '') {
                    errorMessage.innerHTML += "Please specify a password <br />";
//                    document.register.password.focus();
//                    return false;
                }

                if(document.register.password_confirm.value == '') {
                    errorMessage.innerHTML += "Please confirm your password <br />";
//                    document.register.password_confirm.focus();
//                    return false;
                }

                if(document.register.password.value != document.register.password_confirm.value) {
                    errorMessage.innerHTML += "Passwords do not match";
//                    return false;
                }

                return errorMessage.innerHTML === '';
            }
        </script>
    </head>

    <body>
    <?php

    include_once 'CourseDAO.php';

    $formFields = ['first_name', 'last_name', 'email', 'birth_date', 'gender', 'password', 'password_confirm'];
    $verified = true;
    $errors = [];
    $connection = CourseDAO::getInstance();

    if(isset($_POST['submit'])) {

        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $birthDate = $_POST['birth_date'];
        $gender = $_POST['gender'];

        echo $password;

        foreach ($formFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $errors[] = "Please specify a " . strtolower(str_replace("_", " ", $field));
            }

            if($field == 'gender' && $gender == '-1') {
                    $errors[] = "Please specify a " . $field;
            }

        }

        //If there were no errors, insert user
        if (empty($errors)) {
            if($connection->insertUser($firstName, $lastName, $email, $password, $birthDate, $gender)) {
                echo "<p class='success-message'> Succesfully inserted the user </p>";
            } else {
                echo "<p class='error_message'> Failed to insert user </p>";
            }
        } else {
            foreach ($errors as $error) {
                echo "<p class='error_message'>" . $error . "</p>";
            }
        }
    }
    ?>
        <div class="form_container">
            <h3> Add user</h3>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validate_form()" name="register">

               <div class="form_label">First name</div>
                <input type="text" name="first_name" class="form_input" value="<?php echo $firstName; ?>">

                <div class="form_label">Last name </div>
                <input type="text" name="last_name" class="form_input" value="<?php echo $lastName; ?>">

                <div class="form_label">Email</div>
                <input type="text" name="email" class="form_input" value="<?php echo $email; ?>">

                <div class="form_label">Birth date (ex: 1991-01-01) </div>
                <input type="text" name="birth_date" class="form_input" value="<?php echo $birthDate; ?>">

               <div class="form_label">Gender </div>
                <select name="gender" class="form_dropdown">
                    <option value="-1" selected> Choose</option>
                    <option value="male" <?php if($gender == 'male') echo 'selected'; ?>> Male</option>
                    <option value="female" <?php if($gender == 'female') echo 'selected'; ?>> Female</option>
                </select>

                <div class="form_label">Password</div>
                <input type="password" name="password" class="form_input">

                <div class="form_label">Confirm password</div>
                <input type="password" name="password_confirm" class="form_input">

                <input type="submit" value="Submit" name="submit" class="form_button">
                <div style="clear: both;"></div>
            </form>
            <br />

        <p class="error_message"></p>
        </div>
        <a href="index.php">Go back</a>
    </body>
</html>