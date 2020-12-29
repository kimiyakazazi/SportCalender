<?php


class LocationFactory
{
    public static function getLocations(): array
    {
        $mysqli = new mysqli(DataBase::$dbHost, DataBase::$dbUsername, DataBase::$dbPassword, DataBase::$dbName);
        $result = $mysqli->query("SELECT * FROM Location");

        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new Location($row["id"], $row["name"]);
        }
        return $list;
    }
}