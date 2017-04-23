<?php

/**
 * Created by PhpStorm.
 * User: Lennart
 * Date: 19-04-17
 * Time: 10:53
 */
class User
{

    static $instance = null;

    private $id = 0;
    private $username = "";
    private $firstname = "";
    private $lastname = "";
    private $code = "";
    private $lock_id = 0;

    private function __construct()
    {

    }

    public static function get_instance()
    {
        if (self::$instance == null) {
            self::$instance = new User();
        }

        return self::$instance;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId($id)
    {
        $_SESSION['id'] = $id;
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return int
     */
    public function getLockId(): int
    {
        return $this->lock_id;
    }

    /**
     * @param int $lock_id
     */
    public function setLockId(int $lock_id)
    {
        $this->lock_id = $lock_id;
    }


    public function check_login()
    {
        if (isset($_SESSION['id'])) {
            $this->login($_SESSION['id']);
            return true;
        } else {
            return false;
        }
    }

    public function login($id)
    {
        $sql = new Database();
        $result = $sql->select("SELECT * FROM `users` WHERE `id`= {$id}");

        $account = $result[0];

        $this->setId($account['id']);
        $this->setUsername($account['username']);
        $this->setFirstname($account['firstname']);
        $this->setLastname($account['lastname']);
        $this->setCode($account['code']);
        $this->setLockId($account['lock_id']);
    }

    public function logout()
    {
        unset($_SESSION['id']);
        unset($_SESSION['username']);
        unset($_SESSION['code']);
    }
}