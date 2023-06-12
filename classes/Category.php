<?php

class Category
{

    public static function getAll($conn)
    {

        $sql = "SELECT * FROM category ORDER BY name;";

        $result = $conn->query($sql);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


}
