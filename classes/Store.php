<?php


class Store
{
    public function getStore()
    {
        $conn = MyPDO::getInstance();
        $lastId = app::get("lastId");


        if ($lastId == 0)
            $q = "select * from store order by id desc limit 8";
        else
            $q = "select * from store where id<$lastId order by id desc limit 8";


        $stmt = $conn->prepare($q);
        $stmt->execute();
        $goods = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result["fileSize"] = $this->getRemoteFileSize($result["voice"]);
            $result["suitable_car"] = app::getCarsById($result["suitable_car"]);
            array_push($goods, $result);
        }

        header('Content-Type: application/json');
        echo json_encode($goods);
    }


    public function getStore2()
    {
        $conn = MyPDO::getInstance();
        $lastId = app::get("lastId");
        $carName = $_REQUEST["carName"];
        $goodName = $_REQUEST["goodName"];
        $search = $_REQUEST["search"];
        $goods = array();
        $limit = " order by id desc limit 8 ";
        if ($lastId != 0) $limit = "and id<$lastId " . $limit;


        if ($carName == "null" and $goodName == "null" and $search == "null") {
            $q = "select * from store where (1)";
        } elseif ($carName != "null" and $goodName != "null" and $search == "null") {
            $carId = app::getIdByCar($carName);
            if (in_array("not found", $carId))
                $q = "select * from store where (name like '%$goodName%')";
            else {
                $q = "select * from store where (name like '%$goodName%' and (suitable_car like '%,$carId,%' or suitable_car like '$carId,%' or suitable_car like '%,$carId' or suitable_car =$carId))   ";
            }
        } elseif ($carName == "null" and  $goodName== "null" and $search != "null") {
            $carId = app::getIdByCar($search);
            if ($carId == -1)
                $q = "select * from store where (name like '%$search%') ";
            else {
                $q = "select * from store where (name like '%$search%' or (suitable_car like '%,$carId,%' or suitable_car like '$carId,%' or suitable_car like '%,$carId' or suitable_car =$carId))   ";
            }
        } elseif ($carName != "null" and $goodName == "null" and $search == "null") {
            $carId = app::getIdByCar($carName);
            $q = "select * from store where ((suitable_car like '%,$carId,%' or suitable_car like '$carId,%' or suitable_car like '%,$carId' or suitable_car =$carId))   ";
        } elseif ($carName == "null" and $goodName != "null" and $search == "null") {
            $q = "select * from store where ( name like '%$goodName%' ) ";
        }
        //echo $q . $limit;
        $stmt = $conn->prepare($q . $limit);
        $stmt->execute();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result["fileSize"] = $this->getRemoteFileSize($result["voice"]);
            $result["suitable_car"] = app::getCarsById($result["suitable_car"]);
            array_push($goods, $result);
        }

        header('Content-Type: application/json');
        echo json_encode($goods);
    }


    function getRemoteFileSize($url, $formatSize = true, $useHead = true)
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

    public function getGoodsByCarAndGoodName()
    {

        // http://drkamal3.com/Mechanic/index.php?route=getGoodsByCarAndGoodName&carName=%D8%A7%D9%84%DA%A9&goodName=%D9%85%DB%8C%D9%84&lastId=0
        $conn = MyPDO::getInstance();
        $carName = app::get("carName");
        $goodName = app::get("goodName");
        $lastId = app::get("lastId");
        $arrayCarNames = app::getIdByCar($carName);

        $itemArray = array();
        if ($lastId != 0) $limit = " and id<$lastId "; else $limit = "";
        foreach ($arrayCarNames as $carId) {
            $q2 = " select * from store where (suitable_car like '%,$carId,%' or suitable_car like '$carId,%' or suitable_car like '%,$carId' or suitable_car =$carId) and name like '%$goodName%'  $limit order by id desc limit 8";
            $stmt = $conn->prepare($q2);
            $stmt->execute();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (!in_array($result, $itemArray))
                    array_push($itemArray, $result);
            }
        }


        echo json_encode((($itemArray)));
    }


    public function searchGoodOrCar2()
    {
        $conn = MyPDO::getInstance();
        $search = app::get("search");
        $arrayAll = array();
        $carIds = app::getIdByCar($search);
        if (in_array("not found", $carIds))
            $q = "select * from store where name like '%$search%' order by id desc limit 8";
        else {
            $fs = str_replace("[", "(", json_encode($carIds));
            $ss = str_replace("]", ")", $fs);
            $q = "select * from store where name like '%$search%' or suitable_car in $ss order by id desc limit 8";
        }

        $stmt = $conn->prepare($q);
        $stmt->execute();

        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($arrayAll, $result);
        }

        echo json_encode($arrayAll);
    }

    public function totalSearchInStore()
    {
        /*preview,thumbnails,phone,price,name,price_time,id,good_desc,isVisible,voice,fileSize,suitable_car*/
        $conn = MyPDO::getInstance();
        $lastId = app::get("lastId");
        $search = app::get("search");

        if ($lastId == 0)
            $q = "SELECT * FROM `store` WHERE store.name like '%$search%' or store.good_desc like '%$search%' or store.price like '%$search%' or store.phone like '%$search%' order by id desc limit 8";
        else
            $q = "SELECT * FROM `store` WHERE (store.name like '%$search%' or store.good_desc like '%$search%' or store.price like '%$search%' or store.phone like '%$search%') and id<$lastId order by id desc limit 8";


        $stmt = $conn->prepare($q);
        $stmt->execute();
        $goods = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $result["fileSize"] = $this->getRemoteFileSize($result["voice"]);
            array_push($goods, $result);

        }

        header('Content-Type: application/json');
        echo json_encode($goods);
    }

    public function getGoodsByCar()
    {

        $conn = MyPDO::getInstance();
        $lastId = app::get("lastId");
        $search = app::get("search");

        $array = app::getIdByCar($search);
        if (in_array("not found", $array)) {
            echo "not found";
            return;
        }

        $itemArray = array();
        if ($lastId != 0) $limit = " and id<$lastId "; else $limit = "";
        foreach ($array as $carId) {
            $q2 = " select * from store where (suitable_car like '%,$carId,%' or suitable_car like '$carId,%' or suitable_car like '%,$carId' or suitable_car =$carId) $limit";
            $stmt = $conn->prepare($q2);
            $stmt->execute();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (!in_array($result, $itemArray))
                    array_push($itemArray, $result);
            }
        }
        echo json_encode((($itemArray)));

    }

    public function searchGood()
    {
        $conn = MyPDO::getInstance();
        $search = app::get("search");

        $q = "select * from store where name like '%$search%' order by name asc ";


        /* if($search="*") $q = "select * from cars";*/

        $stmt = $conn->prepare($q);
        $stmt->execute();
        $goods = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $arrayCarName=array("id"=>$result["id"],"name"=>$result["name"]);
            array_push($goods, $arrayCarName);
        }
        echo json_encode($goods);
    }

    public function searchAutoCompleteGoodOrCar1()
    {
        $conn = MyPDO::getInstance();
        $search = app::get("search");
        $arrayAll = array();
        $carIds = app::getIdByCar($search);

        $q = "select name from cars where name like '%$search%' order by name asc ";


        /* if($search="*") $q = "select * from cars";*/

        $stmt = $conn->prepare($q);
        $stmt->execute();
        $cars = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)["name"]) {
            array_push($arrayAll, $result);
        }


        $q2 = "select name from store where name like '%$search%' order by name asc ";

        $stmt2 = $conn->prepare($q2);
        $stmt2->execute();
        $goods = array();
        while ($result = $stmt2->fetch(PDO::FETCH_ASSOC)["name"]) {
            array_push($arrayAll, $result);
        }
        echo json_encode($arrayAll);
    }


}