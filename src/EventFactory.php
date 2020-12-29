<?php

class EventFactory
{
    public static function getEvents($currentDate, $team, $category): array
    {
        $mysqli = new mysqli(DataBase::$dbHost, DataBase::$dbUsername, DataBase::$dbPassword, DataBase::$dbName);

        $teamClause = '';
        $categoryClause = '';

        if ($team > '0') {
            $teamClause .= " AND (_id_first_team = '" . $team . "' OR _id_second_team = '" . $team . "')";
        }
        if ($category > '0') {
            $categoryClause .= " AND _id_category = '" . $category . "'";
        }

        $result = $mysqli->query("SELECT * FROM EventView WHERE date = '" . $currentDate . "'" . $teamClause . $categoryClause);

        $list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = new Event($row["ID"], $row["Date"], $row["Time"], $row["first_team"], $row["second_team"], $row["category"], $row["location"]);
        }
        return $list;
    }

    public static function addEvent($eventDate, $eventTime, $eventCategory, $eventFirstTeam, $eventSecondTeam, $eventLocation)
    {
        $mysqli = new mysqli(DataBase::$dbHost, DataBase::$dbUsername, DataBase::$dbPassword, DataBase::$dbName);
        $result = $mysqli->query("INSERT INTO Event (date, time, _id_first_team, _id_second_team, _id_Category, _id_location) VALUES ('" . $eventDate. "', '" . $eventTime . "', '" . $eventFirstTeam . "','" . $eventSecondTeam . "','" . $eventCategory . "' ,'" . $eventLocation . "')");

    }
}