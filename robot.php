<?php

//общая идея такая:
//используем фабрику, для этого создаем интерфейс, с помощью которого будут
//вызываться функции, отличающиеся в зависимости от используемого класса
//по сути у нас 3 типа робота: Robot1, Robot2, MergeRobot
//под каждый тип создаем свой класс
//Robot1, Robot2 - похожие типы, поэтому делаем родительский класс и все общее заносим туда

//в классах Robot1, Robot2 свойства получаем через геттеры
//в классе MergeRobot свойства получаем исходя из свойства этого класса $robots


//общие функции вынес в трейт
trait HelpService
{

    public function getArray($func, $robot)
    {
        $array = array_map(function ($item) use ($func) {
            return call_user_func([$item, $func]);
        }, $robot);
        return $array;
    }

}

//интерфейс для типа робота
//в зависимости от типа робота используются свои функции getSpeed, getWeight, getHeight
interface RobotConnector
{
    public function getSpeed();

    public function getWeight();

    public function getHeight();
}

//делаем общий класс для типа роботов Robot1, Robot2
//класс реализует интерфейс RobotConnector
class Robot implements RobotConnector
{
    use HelpService;

    //делаем protected, чтобы поля были доступны из потомков
    protected $speed;
    protected $weight;
    protected $height;

    public function __construct($speed = 0, $weight = 0, $height = 0)
    {
        $this->speed = $speed;
        $this->weight = $weight;
        $this->height = $height;
    }

    public function getSpeed()
    {
        return $this->speed;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function getHeight()
    {
        return $this->height;
    }
}

class Robot1 extends Robot implements RobotConnector
{

}

class Robot2 extends Robot implements RobotConnector
{

}

//класс MergeRobot, содержащий коллекцию роботов
//класс также реализует интерфейс RobotConnector
class MergeRobot implements RobotConnector
{
    use HelpService;

    private $robots = [];

    public function addRobot($robot)
    {
        if (is_array($robot)) {
            foreach ($robot as $item) {
                $this->robots[] = $item;
            }
        } else {
            $this->robots[] = $robot;
        }
    }

    public function getSpeed()
    {
        //т.к. код неоднократно повторялся, для минимизации кода вынес функцию getArray в трейт
        $speeds = $this->getArray('getSpeed', $this->robots);

        return min($speeds);
    }

    public function getWeight()
    {
        $weights = $this->getArray('getWeight', $this->robots);

        return array_sum($weights);
    }

    public function getHeight()
    {
        $heights = $this->getArray('getHeight', $this->robots);

        return array_sum($heights);
    }
}

class FactoryRobot
{
    use HelpService;

    private $robots = [];

    public function addType(RobotConnector $robot)
    {
        $this->robots[] = $robot;
    }

    public function createRobot1($amount, $speed = 0, $weight = 0, $height = 0)
    {
        $tmp = [];
        foreach (range(1, $amount) as $n) {
            $tmp[] = new Robot1($speed, $weight, $height);
        }
        return $tmp;
    }

    public function createRobot2($amount, $speed = 0, $weight = 0, $height = 0)
    {
        $tmp = [];
        foreach (range(1, $amount) as $n) {
            $tmp[] = new Robot2($speed, $weight, $height);
        }
        return $tmp;
    }

    public function getSpeed()
    {
        $speeds = $this->getArray('getSpeed', $this->robots);

        return min($speeds);
    }

    public function getWeight()
    {
        $weights = $this->getArray('getWeight', $this->robots);
        return array_sum($weights);
    }

    public function getHeight()
    {
        $heights = $this->getArray('getHeight', $this->robots);
        return array_sum($heights);
    }

}

$factory = new FactoryRobot();
$factory->addType(new Robot1(10, 20, 30));
$factory->addType(new Robot2(15, 25, 35));
$merge = new MergeRobot();
$merge->addRobot(new Robot1(20, 30, 40));
$merge->addRobot($factory->createRobot1(2, 40, 50, 60));
$factory->addType($merge);
echo $factory->getSpeed();
echo PHP_EOL;
echo $factory->getHeight();