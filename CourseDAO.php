<?php

/**
 * Created by PhpStorm.
 * User: W7
 * Date: 24.03.2016
 * Time: 15:23
 */
class CourseDAO
{
    private $connection;
    private $host = "52.2.195.57";
    private $username = "u010";
    private $password = "2476";
    private $dbName = "c010";
    private static $instance;
    private $token;

    public function __construct() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbName);

        if($this->connection->connect_error) {
            die("Connect error (" . $this->connection->connect_errno . ") " . $this->connection->connect_error);
        }
    }

    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function __destruct() {
        $this->connection->close();
        $this->connection = null;
    }

    public function runQuery($sqlString) {
        return $this->connection->query($sqlString);
    }

    public function getArray($sqlString, $type = MYSQLI_ASSOC) {
        return $sqlString->fetch_array($type);
    }

    public function bindQuery($sqlString, array $param) {
        $stmt = $this->connection->stmt_init();

        if($stmt->prepare($sqlString)) {
            call_user_func_array(
                array($stmt, "bind_param"),
                array_merge(
                    array($param['bindTypes']), $param['bindVariables']
                )
            );

            if(strpos($sqlString, "SELECT") !== false) {
                $stmt->execute();
                return $stmt->get_result();
            }

            return $stmt->execute();
        }
    }

    public function insertUser($firstName, $lastName, $email, $password, $birthDate, $gender) {

        $firstName = filter_var($firstName, FILTER_SANITIZE_STRING);
        $lastName = filter_var($lastName, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = filter_var($password, FILTER_SANITIZE_STRING);
        $gender = filter_var($gender, FILTER_SANITIZE_STRING);

        $bindArray = [
          'bindTypes' => 'ssssss',
          'bindVariables' =>  [&$firstName, &$lastName, &$email, &$password, &$birthDate, &$gender]
        ];

        $insert = $this->bindQuery("INSERT INTO `users`
                (`first_name`, `last_name`, `email`, `password`, `birth_date`, `gender`)
                VALUES (?, ?, ?, ?, ?, ?)", $bindArray);

        return $insert;
    }

    public function insertMessage($firstPersonID, $secondPersonID, $message) {
        $firstPersonID = filter_var($firstPersonID, FILTER_SANITIZE_NUMBER_INT);
        $secondPersonID = filter_var($secondPersonID, FILTER_SANITIZE_NUMBER_INT);
        $message = filter_var($message, FILTER_SANITIZE_STRING);

        $bindArray = [
            'bindTypes' => 'iis',
            'bindVariables' => [&$firstPersonID, &$secondPersonID, &$message]
        ];

        $insert = $this->bindQuery("INSERT INTO `messages`
            (`user_1_id`, `user_2_id`, `message`, `send_date`)
            VALUES(?, ?, ?, NOW())", $bindArray);

        return $insert;
    }

    public function insertFriend($firstPersonID, $secondPersonID, $status) {
        $firstPersonID = filter_var($firstPersonID, FILTER_SANITIZE_NUMBER_INT);
        $secondPersonID = filter_var($secondPersonID, FILTER_SANITIZE_NUMBER_INT);
        $status = filter_var($status, FILTER_SANITIZE_STRING);

        $bindArrayFirstPerson = [
            'bindTypes' => 'iis',
            'bindVariables' => [&$firstPersonID, &$secondPersonID, &$status]
        ];

        $bindArraySecondPerson = [
            'bindTypes' => 'iis',
            'bindVariables' => [&$secondPersonID, &$firstPersonID, &$status]
        ];


        $insertFirstPerson = $this->bindQuery("INSERT INTO `friends`
              (`user_1_id`, `user_2_id`, `status`)
               VALUES(? , ?, ?)", $bindArrayFirstPerson);

        $insertSecondPerson = $this->bindQuery("INSERT INTO `friends`
              (`user_1_id`, `user_2_id`, `status`)
               VALUES(? , ?, ?)", $bindArraySecondPerson);

        return $insertFirstPerson;
    }

    public function insertPicture($userId, $content) {
        $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
        $picture = file_get_contents($content);
        $picture = base64_encode($picture);

        $bindArray = [
            'bindTypes' => 'is',
            'bindVariables' => [&$userId, &$picture]
        ];

        $insert = $this->bindQuery("INSERT INTO `pictures`
          (`user_id`, `content`)
          VALUES(?, ?)", $bindArray);

        return $insert;
    }

    public function insertLike($pictureId, $userId) {
        $pictureId = filter_var($pictureId, FILTER_SANITIZE_NUMBER_INT);
        $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
        $likeDate = date("Y-m-d H-i-s");

        $bindArray = [
            'bindTypes' => 'iis',
            'bindVariables' => [&$pictureId, &$userId, &$likeDate]
        ];

        $insert = $this->bindQuery("INSERT INTO `likes`
          (`picture_id`, `user_id`, `like_date`)
          VALUES(?, ?, ?)", $bindArray);

        return $insert;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    public function authorizeAccess($username, $password){

        $username = filter_var($username, FILTER_SANITIZE_STRING);
        $password = filter_var($password, FILTER_SANITIZE_STRING);

        //Generate the token for every login attempt
        $key = $this->generateTokenKey();
        $this->setToken(array($key => $username));

        $user = $this->runQuery("SELECT id FROM users WHERE email = '" . $username . "' AND password = '" . $password . "' LIMIT 1");
//        $user = $this->runQuery("SELECT id FROM users WHERE email = '" . $username . "' AND password = '" . $password . "' LIMIT 1");

        //To enable MySQLnd
//        $user = $this->bindQuery("SELECT id FROM users WHERE email = ? AND password = ?", $bindArray);

        if($user->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function generateTokenKey($length = 30) {

        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);

        $tokenKey = "";
        for ($i = 0; $i < $length; $i++) {
            $tokenKey .= $characters[rand(0, $charactersLength - 1)];
        }
        return $tokenKey;
    }
}