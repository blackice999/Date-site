<?php
/**
 * Created by PhpStorm.
 * User: W7
 * Date: 13.04.2016
 * Time: 18:10
 */


session_start();
include "CourseDAO.php";
$connection = CourseDAO::getInstance();

switch($_GET['cmd']) {
    case "listfriends":
        if(isset($_SESSION['authenticated'])) {
           // echo "<ul class='friends_list'>";
            foreach ($_SESSION['token'] as $tokenKey => $email) {
                $idQuery = $connection->runQuery("SELECT id FROM users WHERE email= '" . $email . "'");
                $user_id = $connection->getArray($idQuery);
                $friendsData = $connection->runQuery(
                    "SELECT first_name, last_name FROM users
                     INNER JOIN friends ON users.id = friends.user_2_id
                     WHERE friends.user_1_id = '" . $user_id['id'] . "'");

                $ajaxMessage = [];

                while ($friends = $connection->getArray($friendsData)) {
                    //echo "<li>";

                    //foreach is useless in this case as the getArray() function returnes an associative array
//                    foreach ($friends as $key => $value) {
                        $ajaxMessage[] = $friends;
                }
                echo json_encode($ajaxMessage);
            }
        }
        break;

    case 'listmessages':

       // echo "<ul class='messages_list'>";
        foreach($_SESSION['token'] as $tokenKey => $email) {
            $idQuery = $connection->runQuery("SELECT id FROM users WHERE email= '" . $email . "'");
            $user_id = $connection->getArray($idQuery);
            $messageData = $connection->runQuery(
                "SELECT message, send_date FROM messages
                 INNER JOIN users ON users.id = messages.user_1_id
                 WHERE messages.user_1_id =  '" . $user_id['id'] . "' OR messages.user_2_id = '" . $user_id['id'] . "'
                 ORDER BY send_date ASC"
            );

            $ajaxMessage2 = [];
            while($messages = $connection->getArray($messageData)) {
                $ajaxMessage2[] = $messages;
            }
            echo json_encode($ajaxMessage2);
        }

        break;

    case 'postmessage':

        foreach($_SESSION['token'] as $tokenKey => $email) {

            $idQuery = $connection->runQuery("SELECT id FROM users WHERE email= '" . $email . "'");
            $user_id = $connection->getArray($idQuery);
//
            if($connection->insertMessage($user_id['id'], $_GET['user'], $_GET['message'])) {
                echo json_encode(['response' => 'success']);
            } else {
                echo json_encode(['response' => 'fail']);
            }
        }
        break;

    default:
        echo "I don't know what you want";
}