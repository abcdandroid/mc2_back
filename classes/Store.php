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
        } elseif ($carName == "null" and $goodName == "null" and $search != "null") {
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

    public function getStore3()
    {
        //http://drkamal3.com/Mechanic/index.php?route=getStore3&lastId=0&carId=0&goodId=0&warrantyId=0&countryId=0&isStock=0#
        $conn = MyPDO::getInstance();
        $lastId = app::get("lastId");
        $carId = $_REQUEST["carId"];
        $goodId = $_REQUEST["goodId"];
        $warrantyId = $_REQUEST["warrantyId"];
        $countryId = $_REQUEST["countryId"];
        $isStock = $_REQUEST["isStock"];

        if ($carId == -1 && $goodId != -1) {
            $errorCarArray = array();
            $tmpCarArray = array();
            $tmpCarArray["id"] = "-2";
            $tmpCarArray["preview"] = "0";
            $tmpCarArray["good_id"] = "0";
            $tmpCarArray["good_desc"] = "0";
            $tmpCarArray["voice"] = "0";
            $tmpCarArray["price_time"] = "0";
            $tmpCarArray["price"] = "0";
            $tmpCarArray["suitable_car"] = "0";
            $tmpCarArray["thumbnails"] = "0";
            $tmpCarArray["made_by"] = "0";
            $tmpCarArray["company"] = "0";
            $tmpCarArray["warranty"] = "0";
            $tmpCarArray["is_stock"] = "0";
            $tmpCarArray["status"] = "0";
            $tmpCarArray["fileSize"] = "0";
            array_push($errorCarArray, $tmpCarArray);
            echo json_encode($errorCarArray);
            die();
        } else if ($goodId == -1 && $carId != -1) {
            $errorGoodArray = array();
            $tmpGoodArray = array();
            $tmpGoodArray["id"] = "-3";
            $tmpGoodArray["preview"] = "0";
            $tmpGoodArray["good_id"] = "0";
            $tmpGoodArray["good_desc"] = "0";
            $tmpGoodArray["voice"] = "0";
            $tmpGoodArray["price_time"] = "0";
            $tmpGoodArray["price"] = "0";
            $tmpGoodArray["suitable_car"] = "0";
            $tmpGoodArray["thumbnails"] = "0";
            $tmpGoodArray["made_by"] = "0";
            $tmpGoodArray["company"] = "0";
            $tmpGoodArray["warranty"] = "0";
            $tmpGoodArray["is_stock"] = "0";
            $tmpGoodArray["status"] = "0";
            $tmpGoodArray["fileSize"] = "0";
            array_push($errorGoodArray, $tmpGoodArray);
            echo json_encode($errorGoodArray);
            die();
        } else if ($goodId == -1 && $carId == -1) {
            $errorGoodAndCarArray = array();
            $tmpArray = array();
            $tmpArray["id"] = "-4";
            $tmpArray["preview"] = "0";
            $tmpArray["good_id"] = "0";
            $tmpArray["good_desc"] = "0";
            $tmpArray["voice"] = "0";
            $tmpArray["price_time"] = "0";
            $tmpArray["price"] = "0";
            $tmpArray["suitable_car"] = "0";
            $tmpArray["thumbnails"] = "0";
            $tmpArray["made_by"] = "0";
            $tmpArray["company"] = "0";
            $tmpArray["warranty"] = "0";
            $tmpArray["is_stock"] = "0";
            $tmpArray["status"] = "0";
            $tmpArray["fileSize"] = "0";
            array_push($errorGoodAndCarArray, $tmpArray);
            echo json_encode($errorGoodAndCarArray);
            die();
        }

        if ($carId != 0) {
            $carFilter = " suitable_car like '%,$carId,%' or suitable_car like '$carId,%' or suitable_car like '%,$carId' or suitable_car =$carId ";
        } else {
            $carFilter = "  suitable_car like '%' ";
        }
        if ($goodId != 0) {
            $goodFilter = " good_id = '$goodId' ";
        } else {
            $goodFilter = " good_id like '%' ";
        }
        if ($warrantyId != 0) {
            $warrantyFilter = " warranty = '$warrantyId' ";
        } else {

            $warrantyFilter = " warranty like '%' ";
        }

        if ($countryId != 0) {
            $countryFilter = " made_by = '$countryId' ";
        } else {
            $countryFilter = " made_by like '%' ";
        }
        if ($isStock != 0) {
            $stockFilter = " is_stock = '$isStock' ";
        } else {
            $stockFilter = " is_stock like '%' ";
        }

        $goods = array();
        $limit = " order by id desc limit 8 ";
        if ($lastId != 0) $limit = "and id<$lastId " . $limit;
        $q = "select * from store where  ( $carFilter ) and ( $goodFilter ) and ( $warrantyFilter ) and ( $countryFilter ) and ( $stockFilter ) ";
        //echo $q . $limit;
        $stmt = $conn->prepare($q . $limit);
        $stmt->execute();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result["fileSize"] = $this->getRemoteFileSize($result["voice"]);
            $result["suitable_car"] = app::getCarsById($result["suitable_car"]);
            $result["good_id"] = app::getGoodById($result["good_id"]);
            $result["warranty"] = app::getWarrantyById($result["warranty"]);
            $result["made_by"] = app::getCountryById($result["made_by"]);
            array_push($goods, $result);
        }

        header('Content-Type: application/json');
        echo json_encode($goods); /* */
    }

    function getRemoteFilesize($url, $formatSize = true, $useHead = true)
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

        $size = $clen;/*
        switch ($clen) {
            case $clen < 1024:
                $size = $clen .' B'; break;
            case $clen < 1048576:
                $size = round($clen / 1024, 2) .' KiB'; break;
            case $clen < 1073741824:
                $size = round($clen / 1048576, 2) . ' MiB'; break;
            case $clen < 1099511627776:
                $size = round($clen / 1073741824, 2) . ' GiB'; break;
        }*/

        return $size; // return formatted size
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

        $q = "select * from goods where name like '%$search%' order by name asc ";


        /* if($search="*") $q = "select * from cars";*/

        $stmt = $conn->prepare($q);
        $stmt->execute();
        $goods = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $arrayCarName = array("id" => $result["id"], "name" => $result["name"]);
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

    public function getAllWarranties()
    {
        header('Content-Type: application/json');
        $conn = MyPDO::getInstance();


        $q = "select * from warrantys order by name  ";


        $stmt = $conn->prepare($q);
        $stmt->execute();
        $warranties = array();

        array_push($warranties, array("id" => 0, "name" => "همه گارانتی ها"));
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($warranties, $result);
        }
        return ($warranties);
    }

    public function getAllCountries()
    {
        header('Content-Type: application/json');
        $conn = MyPDO::getInstance();


        $q = "select * from countries order by name  ";


        $stmt = $conn->prepare($q);
        $stmt->execute();
        $warranties = array();
        array_push($warranties, array("id" => 0, "name" => "همه کشورها"));
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($warranties, $result);
        }
        return ($warranties);
    }

    public function getCountriesAndWarranties()
    {
        echo json_encode(array("countries" => $this->getAllCountries(), "warrantys" => $this->getAllWarranties()));
    }

    public function addToSold()
    {
        header('Content-Type: application/json');
        $conn = MyPDO::getInstance();
        $userId = app::get("userId");
        $goodId = app::get("goodId");

        $date = new DateTime('now', new DateTimeZone('Asia/tehran'));
        $currentTime = $date->format('Y-m-d H:i:s');

        $q = "INSERT INTO `sold` (`id`, `user_id`, `good_id`, `date`) VALUES (NULL, :userId, :goodId, :currentTime);";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("userId",$userId);
        $stmt->bindParam("goodId",$goodId);
        $stmt->bindParam("currentTime",$currentTime);
        $stmt->execute();

    }

}