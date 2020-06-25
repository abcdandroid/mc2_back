<?php


class Medias
{
    public function getAdminMedias()
    {
        $conn = MyPDO::getInstance();
        $q = "SELECT * FROM `admin`";
        $stmt = $conn->prepare($q);
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)); if ($mp_id == -1) {
        header('Content-Type: application/json');
        echo json_encode(array("msg" => $q, "mechanic" => $mechanics)); /* */
    } else
        return $mechanics;
    }
}