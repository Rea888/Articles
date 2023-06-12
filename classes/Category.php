<?php

class Category
{

    public static function getAll($conn)
    { //static because this method won't be acting upon an idividual article object

        $sql = "SELECT * FROM category ORDER BY name;";

        $result = $conn->query($sql);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


}
