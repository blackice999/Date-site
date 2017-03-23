<html>
<head>
    <title> Add picture</title>
</head>

<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <label>
        Picture ID
        <input type="text" name="picture_id">
    </label> <br />

    <label>
        User ID
        <input type="text" name="user_id">
    </label> <br />

    <input type="submit" value="Submit" name="submit">
</form>
</body>
</html>

<?php
if(isset($_POST['submit'])) {
    $pictureId = $_POST['picture_id'];
    $userId= $_POST['user_id'];

//        $connection = CourseDAO::getInstance();
    $connection->insertLike($pictureId, $userId);
}
?>