<html>
<head>
    <title> Add friend</title>
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
        Status
        <input type="text" name="status">
    </label> <br />

    <input type="submit" value="Submit" name="submit">
</form>
</body>
</html>

<?php
if(isset($_POST['submit'])) {

    include "CourseDAO.php";
    $firstUser = $_POST['first_user'];
    $secondUser = $_POST['second_user'];
    $status = $_POST['status'];

        $connection = CourseDAO::getInstance();
    $connection->insertFriend($firstUser, $secondUser, $status);
}
?>