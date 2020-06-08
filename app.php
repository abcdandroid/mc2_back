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

    public static function getJobsById($ids)
    {
        $conn = MyPDO::getInstance();
        $array = explode(",", $ids);
        $jobList = array();

        for ($i = 1; $i <= sizeof($array); $i++) {
            $q = "select * from jobs where id=:id";
            $stmt = $conn->prepare($q);
            $stmt->bindParam("id", $array[$i - 1]);
            $stmt->execute();
            $job = $stmt->fetch(PDO::FETCH_ASSOC)["name"];//15+12+3.5+2
            array_push($jobList, $job);
        }

        if (!in_array(false, $jobList)) {
            return json_encode($jobList);
        } else {
            $array1 = array();
            array_push($array1, array("id" => 0, "name" => "all jobs"));
            return json_encode($array1);
        }

    }

    public static function getMoviesBySize($movieUrl)
    {
        $array = explode(",", $movieUrl);
        $movieList = array();

        for ($i = 1; $i <= sizeof($array); $i++) {
            $moveSize = app::getRemoteFileSize($array[$i - 1]);
            $result = array("movie_url" => $array[$i - 1], "movie_size" => $moveSize);
            array_push($movieList, $result);
        }

        if (!in_array(false, $movieList)) {
            return ($movieList);
        } else {
            $array1 = array();
            array_push($array1, array("movie_url" => "bad url", "movie_size" => -1));
            return ($array1);
        }

    }


    public static function getRemoteFileSize($url, $formatSize = true, $useHead = true)
    {
        if (false !== $useHead) {
            stream_context_set_default(array('http' => array('method' => 'HEAD')));
        }
        $head = array_change_key_case(get_headers($url, 1));
        // content-length of download (in bytes), read from Content-Length: field
        $clen = isset($head['content-length']) ? $head['content-length'] : 0;

        // cannot retrieve file size, return "-1"
        if (!$clen) {
            return -1;
        }

        if (!$formatSize) {
            return $clen; // return size in bytes
        }

        $size = $clen;
        switch ($clen) {
            case $clen < 1024:
                $size = $clen . ' B';
                break;
            case $clen < 1048576:
                $size = round($clen / 1024, 2) . ' KiB';
                break;
            case $clen < 1073741824:
                $size = round($clen / 1048576, 2) . ' MiB';
                break;
            case $clen < 1099511627776:
                $size = round($clen / 1073741824, 2) . ' GiB';
                break;
        }

        return $clen; // return formatted size
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

    public static function getGoodById($id)
    {
        $conn = MyPDO::getInstance();

        $q = "select * from goods where id=:id";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $good = $stmt->fetch(PDO::FETCH_ASSOC)["name"];

        if ($good) {
            return ($good);
        } else {
            $array1 = array();
            array_push($array1, array("id" => -1, "name" => "not found"));
            return json_encode($array1);
        }
    }

    public static function getRegionById($id)
    {
        $conn = MyPDO::getInstance();

        $q = "select * from regions where id=:id";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $Region = $stmt->fetch(PDO::FETCH_ASSOC)["name"];

        if ($Region) {
            return ($Region);
        } else {
            $array1 = array();
            array_push($array1, array("id" => -1, "name" => "not found"));
            return json_encode($array1);
        }
    }

    public static function getWarrantyById($id)
    {
        $conn = MyPDO::getInstance();

        $q = "select * from warrantys where id=:id";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $warranty = $stmt->fetch(PDO::FETCH_ASSOC)["name"];

        if ($warranty) {
            return ($warranty);
        } else {
            $array1 = array();
            array_push($array1, array("id" => -1, "name" => "not found"));
            return json_encode($array1);
        }
    }

    public static function getCountryById($id)
    {
        $conn = MyPDO::getInstance();

        $q = "select * from countries where id=:id";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $country = $stmt->fetch(PDO::FETCH_ASSOC)["name"];

        if ($country) {
            return ($country);
        } else {
            $array1 = array();
            array_push($array1, array("id" => -1, "name" => "not found"));
            return json_encode($array1);
        }
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