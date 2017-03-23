<html>
<head>
    <title> Add picture</title>
</head>

<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
    <label>
         User ID
        <input type="text" name="user_id">
    </label> <br />

    <label>
         Content
        <input type="file" name="content">
    </label> <br />

    <input type="submit" value="Submit" name="submit">
</form>
</body>
</html>

<?php
if(isset($_POST['submit'])) {
    $userId= $_POST['user_id'];
    $content = $_POST['content'];

//        $connection = CourseDAO::getInstance();
    $connection->insertPicture($userId, $content);
}
?>