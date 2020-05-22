<?php

class MyPDO extends PDO
{


    private static $instance = null;


    public function __construct()
    {

        try {
            parent::__construct("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME , DB_USERNAME, DB_PASSWORD);
            self::$instance = $this;
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->exec("set names utf8");
            self::$instance->exec("SET CHARACTER SET utf8");
           header("Content-Type: text/html;charset=UTF-8");
        } catch (PDOException $ex) {
            Response::isError();
        }

    }

    private function __clone()
    {

    }


    public static function getInstance()
    {
        if (self::$instance == null) {//create a new connection
            try {

                self::$instance = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"));
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->exec("set names utf8");
                self::$instance->exec("SET CHARACTER SET utf8");
                header("Content-Type: text/html;charset=UTF-8");
            } catch (PDOException $ex) {
                Response::isError();
            }
        }

        return self::$instance;
    }


    public static function getRowCount($stmt)
    {
        return $stmt->rowCount();
    }


    public static function getLastID($conn)
    {
        return $conn->lastInsertId();
    }

}