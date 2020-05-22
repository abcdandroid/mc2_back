<?php


class Medias
{
    public function getAdminMedias()
    {

        $conn = MyPDO::getInstance();
        $q = "SELECT * FROM `admin` ";
        $stmt = $conn->prepare($q);
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));


    }

}