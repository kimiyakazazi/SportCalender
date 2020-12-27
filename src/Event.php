<?php


class Event
{
    public string $id;
    public string $date;
    public string $time;
    public string $first_team;
    public string $second_team;
    public string $category;
    public string $location;

    function __construct($id, $date, $time, $first_team, $second_team, $category, $location)
    {
        $this->id = $id;
        $this->date = $date;
        $this->time = $time;
        $this->first_team = $first_team;
        $this->second_team = $second_team;
        $this->category = $category;
        $this->location = $location;
    }

    function __toString(): string
    {
        $category = $this->category;
        switch ($this->category){
            case "Ice hockey":
                $category="<i class='fas fa-hockey-puck' style='font-size:20px'></i>";
                break;
            case "Football":
                $category="<i class='fas fa-futbol' style='font-size:20px'></i>";
                break;
            case "Basketball":
                $category="<i class='fas fa-basketball-ball' style='font-size:20px'></i>";
                break;
            case "Ski":
                $category="<i class='fas fa-skiing' style='font-size:20px'></i>";
                break;
            case "Tennis":
                $category="<i class='fas fa-table-tennis' style='font-size:20px'></i>";
                break;


        }


        $time = "<i class='far fa-clock' style='font-size:20px'></i>" . " " . $this->time;
        $teams = "<b>" .$this->first_team . " - " . $this->second_team . "</b>";
        return $category . ' ' . $teams . '</br>' . $time .  ' in ' .$this->location;
    }
}