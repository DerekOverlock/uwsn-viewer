<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/Database.php";

class User {
    static private $tbl_name = "User";
    static private $primary_key = "UID";

    private $model;
    private $first_name;
    private $last_name;
    private $email;
    private $password;
    private $uid = null;

    private function __construct($first_name, $last_name, $email, $encryptedPassword, $uid = null) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password = $encryptedPassword;
        $this->uid = $uid;
        $this->model = self::getDatabase();
    }

    public static function encryptPassword($password)
    {
        $salt = md5(mt_rand());
        return crypt($password, '$5$rounds=5000$'.$salt.'$');
    }


    private static function matchPassword($userInput, $passInDb)
    {
        return $passInDb == crypt($userInput, $passInDb);
    }

    public function firstName($firstName = NULL) {
        if($firstName) {
            $this->first_name = $firstName;
            return $this;
        } else
            return $this->first_name;
    }

    public function lastName($lastName = NULL) {
        if($lastName) {
            $this->last_name = $lastName;
            return $this;
        } else
            return $this->last_name;
    }

    public function email($email = NULL) {
        if($email) {
            $this->email = $email;
            return $this;
        } else
            return $this->email;
    }

    public function password($password = NULL) {
        if($password) {
            $this->password = self::encryptPassword($password);
            return $this;
        } else {
            return $this->password;
        }

    }

    public function uid() {
        return $this->uid;
    }

    public function save() {
        $fields = array(
            "FirstName" => $this->first_name,
            "LastName" => $this->last_name,
            "Email" => $this->email,
            "Password" => $this->password
        );
        $result = $this->model->save($fields, $this->uid);
        if($result->success && !$this->uid) {
            $this->uid = $result->insert_id;
        }
        return $result;
    }

    static public function getUserWithUid($uid) {
        $model = self::getDatabase();
        $uid = $model->sanitize($uid);
        $sql = "SELECT * FROM User WHERE UID = '$uid'";
        $userResult = $model->query($sql)->itemize();
        if($userResult) {
           $user = $userResult[0];
            return new User($user->FirstName, $user->LastName, $user->Email, $user->Password, $user->UID);
        } else {
            return false;
        }
    }

    static private function getDatabase() {
        return new Database(self::$tbl_name, self::$primary_key);
    }


    static public function getUserWithEmail($email) {
        $model = self::getDatabase();
        $email = $model->sanitize($email);
        $sql = "SELECT * FROM User WHERE Email = '$email'";
        $userResult = $model->query($sql)->itemize();
        if($userResult) {
            $user = $userResult[0];
            return new User($user->FirstName, $user->LastName, $user->Email, $user->Password, $user->UID);
        } else {
            return false;
        }
    }

    static public function login($email, $pw) {
        //TODO
    }
} 