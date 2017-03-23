<html>
<head>
    <title> Add message</title>
</head>

<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label>
        First user
        <input type="text" name="first_user">
    </label> <br />

    <label>
        Second user
        <input type="text" name="second_user">
    </label> <br />

    <label>
        Message
        <input type="text" name="message">
    </label> <br />

    <input type="submit" value="Submit" name="submit">
</form>
</body>
</html>

<?php
if(isset($_POST['submit'])) {
    $firstUser = $_POST['first_user'];
    $secondUser = $_POST['second_user'];
    $message = $_POST['message'];

    include "CourseDAO.php";

        $connection = CourseDAO::getInstance();
    $connection->insertMessage($firstUser, $secondUser, $message);
}
?>