<?php

class Database
{

    public function getConn()
    {

        $db_host = "mysql";
        $db_name = "cms";
        $db_user = "cms_www";
        $db_pw = "6VCVBn16KKCXA)3m";
        $db_port = "3306";

        $dsn = 'mysql:host=' . $db_host . ';port=' . $db_port . ';dbname=' . $db_name . ';charset=utf8';

        try {
            $db = new PDO($dsn, $db_user, $db_pw);

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $db;

        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }

    }

}
