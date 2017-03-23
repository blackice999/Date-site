<html>
    <head>
        <title>Table contents</title>
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
</html>

<?php
include_once "CourseDAO.php";
//error_reporting(E_ALL);
ini_set('display_errors', 1);

$connection = CourseDAO::getInstance();
//$connection = $database->getConnection();


echo "<h3> Chat </h3>";

//$data = mysqli_query($connection, "SELECT first_name, last_name, message FROM users INNER JOIN messages WHERE users.id = messages.user_1_id");
//Get the user first and last name, with the message
$data = $connection->runQuery("SELECT first_name, last_name, message FROM users INNER JOIN messages WHERE users.id = messages.user_1_id");
//$data = $connection->getArray("SELECT first_name, last_name, message FROM users INNER JOIN messages WHERE users.id = messages.user_1_id");
while($row = $connection->getArray($data)) {
    echo $row["first_name"] . " " . $row["last_name"] . ": " . $row["message"].  "<br />";
}


echo "<h3> Users </h3>";
//$usersData = mysqli_query($connection, "SELECT first_name, last_name, email, birth_date, gender FROM users");

echo "<table border='1' class='contents'>";

echo "<tr>";
echo "<th> First name </th>";
echo "<th> Last name </th>";
echo "<th> email </th>";
echo "<th> Birth date </th>";
echo "<th> Gender </th>";
echo "</tr>";
//Use MYSQLI_ASSOC constant to avoid duplicate rows

$usersData = $connection->runQuery("SELECT first_name, last_name, email, birth_date, gender FROM users");
while($users = $connection->getArray($usersData)) {
    echo "<tr>";
    foreach($users as $key => $value) {
        echo "<td>" . $value . "</td>";
    }
    echo "</tr>";
}

echo "</table>";

echo "<h3> Messages </h3>";


echo "<table border='1' class='contents'>";
echo "<tr>";
echo "<th> First person ID </th>";
echo "<th> Second person ID </th>";
echo "<th> Message </th>";
echo "<th> Send date </th>";
echo "<th> Read date </th>";
echo "</tr>";
$messageData = $connection->runQuery("SELECT user_1_id, user_2_id, message, send_date, read_date FROM messages");
while($messages = $connection->getArray($messageData)) {
    echo "<tr>";
    foreach($messages as $key => $value) {
        echo "<td>" . $value . "</td>";
    }
    echo "</tr>";
}

echo "</table>";

echo "<h3> Friends </h3>";

echo "<table border='1' class='contents'>";
echo "<tr>";
echo "<th> First person ID </th>";
echo "<th> Second person ID </th>";
echo "<th> Status </th>";
echo "</tr>";


$friendsData = $connection->runQuery("SELECT user_1_id, user_2_id, status FROM friends");
while($friends = $connection->getArray($friendsData)) {
    echo "<tr>";
    foreach($friends as $key => $value) {
        echo "<td>" . $value . "</td>";
    }
    echo "</tr>";
}

echo "</table>";

echo "<h3> Pictures </h3>";

echo "<table border='1' class='contents'>";
echo "<tr>";
echo "<th> User ID </th>";
echo "<th> Content </th>";
echo "</tr>";

$pictureData = $connection->runQuery("SELECT user_id, content FROM pictures");
while($pictures = $connection->getArray($pictureData)) {
    echo "<tr>";
    foreach($pictures as $key => $value) {
        echo "<td>" . $value . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

echo "<h3> Likes </h3>";

echo "<table border='1' class='contents'>";
echo "<tr>";
echo "<th> Picture ID </th>";
echo "<th> User ID </th>";
echo "</tr>";

$likeData = $connection->runQuery("SELECT picture_id, user_id FROM likes");
while($likes = $connection->getArray($likeData)) {
    echo "<tr>";
    foreach($likes as $key => $value) {
        echo "<td>" . $value . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

echo "<h3><a href='register.php'>Register</a></h3>";
//include "register.php";

echo "<h3><a href='login.php'>Login</a></h3>";

//echo "<h3> Add message </h3>";
//include "add_message.php";
//
//echo "<h3> Add friend </h3>";
//include "add_friend.php";
//
//echo "<h3> Add picture </h3>";
//include "add_picture.php";
//
//echo "<h3> Add like </h3>";
//include "add_like.php";



