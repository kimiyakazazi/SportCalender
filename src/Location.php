<?php


class Location
{
    public int $id;
    public string $name;

    function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}