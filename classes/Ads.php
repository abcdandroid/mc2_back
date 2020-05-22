<?php


class Ads
{
    public function getAds()
    {

        //http://drkamal3.com/Mechanic/index.php?route=getAds
        $conn = MyPDO::getInstance();
        $q = "select * from ads order by id ";
        $stmt = $conn->prepare($q);
        $stmt->execute();
        $ads = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result["good"]=$this->getGoodById($result["goodId"]);
            array_push($ads, $result);
        }

        header('Content-Type: application/json');
        echo json_encode($ads);
    }


    public function getGoodById($id)
    {
        $conn = MyPDO::getInstance();
        $q = "select * from store where id=:id  ";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("id",$id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result);
    }

}