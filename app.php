<?php

/**
 * Created by PhpStorm.
 * User: ahmad
 * Date: 02/15/2020
 * Time: 19:57
 */
class app
{
    public static function get($key)
    {
        if (!isset($_REQUEST[$key]))
            Response::isError();

        return $_REQUEST[$key];
    }

    public static function getCarsById($ids)
    {
        $conn = MyPDO::getInstance();
        $array = explode(",", $ids);
        $carList = array();

        for ($i = 1; $i <= sizeof($array); $i++) {
            $q = "select * from cars where id=:id";
            $stmt = $conn->prepare($q);
            $stmt->bindParam("id", $array[$i - 1]);
            $stmt->execute();
            $car = $stmt->fetch(PDO::FETCH_ASSOC);//15+12+3.5+2
            array_push($carList, $car);
        }

        if (!in_array(false, $carList)) {
            return json_encode($carList);
        } else {
            $array1 = array();
            array_push($array1, array("id" => 0, "name" => "all cars"));
            return json_encode($array1);
        }

    }

    public static function getIdByCar($carName)
    {
        $conn = MyPDO::getInstance();
        $q = "SELECT * FROM cars WHERE name LIKE '%$carName%'";
        $stmt = $conn->prepare($q);
        $stmt->execute();
//       $array = array();
//        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
//            array_push($array, intval($result["id"]));
//        }
//        if (sizeof($array) == 0)
//            array_push($array, "not found");
        if (MyPDO::getRowCount($stmt) == 1)
            $id = $stmt->fetch(PDO::FETCH_ASSOC)["id"];
        else $id = -1;
        return $id;
    }

    public static function test()
    {
        $conn = MyPDO::getInstance();
        $q = "SELECT * FROM cars WHERE name LIKE 'aaa'";
        $stmt = $conn->prepare($q);
        $stmt->execute();
        $array = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($array, $result);
        }
        if (sizeof($array) == 0)
            array_push($array, "not found");
        echo json_encode($array);
    }
}