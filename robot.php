<?php

interface RobotConnector
{

}

class Robot
{
    private $weight;
    private $speed;
    private $height;

    public function __construct($weight = 0, $speed = 0, $height = 0)
    {
        $this->weight = $weight;
        $this->speed = $speed;
        $this->height = $height;
    }

    public function addType(Robot $robot)
    {
        return $robot;
    }
}

class Robot1 extends Robot implements RobotConnector
{

}

class Robot2 extends Robot implements RobotConnector
{

}

class MergeRobot implements RobotConnector
{
    private $robots = [];

    public function addType(array $robots)
    {
    }

    function addRobot($robot)
    {
        foreach ($robot as $item) {
            $this->robots[] = $item;
        }
    }
}

class FactoryRobot
{
    private $robots = [];

    public function addType(Robot $robot)
    {
        $this->robots[] = array_merge($this->robots, $robot);
    }

    public function createRobot1($amount)
    {
        $tmp = [];
        foreach (range(1, $amount) as $n) {
            $tmp[] = new Robot1();
        }
        return $tmp;
    }

    public function createRobot2($amount)
    {
        $tmp = [];
        foreach (range(1, $amount) as $n) {
            $tmp[] = new Robot2();
        }
        return $tmp;
    }

    public function getSpeed()
    {
    }

    public function getWeight()
    {
    }

}
