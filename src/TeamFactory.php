<?php

class TeamFactory
{
    public static function getTeams(): array
    {
        $mysqli = new mysqli(DataBase::$dbHost, DataBase::$dbUsername, DataBase::$dbPassword, DataBase::$dbName);
        $result = $mysqli->query("SELECT * FROM Team");

        $list = array();
        $list[] = new Team(0, "All");
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new Team($row["id"], $row["name"]);
        }
        return $list;
    }
}