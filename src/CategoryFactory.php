<?php

class CategoryFactory
{
    public static function getCategories(): array
    {
        $mysqli = new mysqli(DataBase::$dbHost, DataBase::$dbUsername, DataBase::$dbPassword, DataBase::$dbName);
        $result = $mysqli->query("SELECT * FROM Category");

        $list = array();
        $list[] = new Category(0, "All");
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new Category($row["id"], $row["name"]);
        }
        return $list;
    }
}