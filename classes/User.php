<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/Model.php";

class User extends DataModel {
    static private $tbl_name = "User";
    static private $primary_key = "UID";
    private $model;
    private $first_name;
    private $last_name;
    private $email;
    private $password;
    private $uid = null;

    public function __construct($first_name, $last_name, $email, $password, $uid = null) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password = $password;
        $this->uid = $uid;
        $this->model = self::getDataModel();
    }

    public function setFirstName($first_name) {
        $this->first_name = $first_name;
    }

    public function setLastName($last_name) {
        $this->last_name = $last_name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = self::hashPassword($password);
    }

    private static function hashPassword($password)
    {
        $salt = md5(mt_rand());
        return crypt($password, '$5$rounds=5000$'.$salt.'$');
    }


    private static function matchPassword($userInput, $passInDb)
    {
        return $passInDb == crypt($userInput, $passInDb);
    }

    public function firstName() {
        return $this->first_name;
    }

    public function lastName() {
        return $this->last_name;
    }

    public function email() {
        return $this->email;
    }

    public function password() {
        return $this->password;
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
        $model = self::getDataModel();
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


    static public function getUserWithEmail($email) {
            $model = self::getDataModel();
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

    static private function getDataModel() {
        $model = new DataModel(self::$tbl_name, self::$primary_key);
        return $model;
    }

    static public function login($email, $pw) {
        //TODO
    }
} 