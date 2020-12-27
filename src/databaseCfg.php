<?php

$mysqli = new mysqli("db", "root", "example", "SportsCalendar");

if ($mysqli->connect_error) {
    die("Connection could not be established: " . $mysqli->connect_error);
}