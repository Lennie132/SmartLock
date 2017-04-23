<?php

/**
 * Created by PhpStorm.
 * User: Lennart
 * Date: 15-04-17
 * Time: 10:59
 */
class Database
{
    protected static $connection;

    /**
     * Connect to the database
     *
     * @return bool | mysqli
     * @description false on failure | mysqli MySQLi object instance on success
     */
    public function connect()
    {
        //if ($_SERVER['HTTP_REFERER'] == "http://localhost/") {
        // DATABASE VARIABLES LOCALHOST
        $db_host = "localhost";
        $db_username = "root";
        $db_password = "root";
        $db_name = "smartlock";

//        } else {
//            // DATABASE VARIABLES
//            $db_host = "lennartv.nl.mysql";
//            $db_username = "lennartv_nl";
//            $db_password = "n2FNVrVN";
//            $db_name = "lennartv_nl";
//        }

        // Try and connect to the database
        if (!isset(self::$connection)) {
            // Load configuration as an array. Use the actual location of your configuration file
            self::$connection = new mysqli($db_host, $db_username, $db_password, $db_name);
        }
        // If connection was not successful, handle the error
        if (self::$connection === false) {
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
        }
        return self::$connection;
    }

    /**
     * Query the database
     *
     * @param   string $query The query string
     * @return  mixed           The result of the mysqli::query() function
     */
    public function query($query)
    {
        // Connect to the database
        $connection = $this->connect();

        // Query the database
        $result = $connection->query($query);

        return $result;
    }

    /**
     * Fetch rows from the database (SELECT query)
     *
     * @param       string $query The query string
     * @return      bool | array
     * @description False on failure | array Database rows on success
     */
    public function select($query)
    {
        $rows = array();
        $result = $this->query($query);
        if ($result === false) {
            return false;
        }
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Fetch the last error from the database
     *
     * @return string Database  error message
     */
    public function error()
    {
        $connection = $this->connect();
        return $connection->error;
    }

    /**
     * Quote and escape value for use in a database query
     *
     * @param   string $value The value to be quoted and escaped
     * @return  string              The quoted and escaped string
     */
    public function quote($value)
    {
        $connection = $this->connect();
        //print_r($connection);
        return $connection->real_escape_string($value);
    }

    /**
     * @return mixed
     */
    public function get_last_id()
    {
        $connection = $this->connect();
        return $connection->insert_id;
    }
}