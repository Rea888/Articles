<?php

class User
{

    public $id;
    public $username;
    public $password;

    public static function authenticate($conn, $username, $password)
    { // we will not have an instance of a user object

        $sql = "SELECT * FROM user WHERE username = :username";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'USER');
        $stmt->execute();

        if ($user = $stmt->fetch()) {
            return password_verify($password, $user->password);
        }
    }

}
