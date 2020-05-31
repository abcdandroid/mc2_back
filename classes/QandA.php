<?php


class QandA
{
    public function questionsFetchAll()
    {
        $conn = MyPDO::getInstance();
        $lastId = app::get("lastId");
        $carName = app::get("carName");
        $q_entrance_id = app::get("entranceId");
        $search = app::get("search");
        //echo "A";
        $limit = " order by questions.q_id desc  limit 4 ";
        if ($lastId != 0) $limit = " and questions.q_id<$lastId " . $limit;

        if ($carName == "null" && $q_entrance_id == "null" && $search == "null") {
            $q = "SELECT * FROM questions where (1)";
        } else if ($carName != "null" && $q_entrance_id == "null" && $search == "null") {
            $carId = app::getIdByCar($carName);
            $q = "SELECT * FROM  questions where (carId =$carId)";
        } else if ($carName != "null" && $q_entrance_id == "null" && $search != "null") {
            $carId = app::getIdByCar($carName);
            $q = "SELECT DISTINCT questions.* FROM answers RIGHT JOIN questions on questions.q_id= answers.q_id WHERE (questions.carId=$carId and (questions.q_text LIKE '%$search%' or answers.a_text LIKE '%$search%'))";
        } else if ($carName == "null" && $q_entrance_id != "null" && $search == "null") {
            $q = "SELECT * FROM  questions where (questions.q_entrance_id=$q_entrance_id)";
        }


        $stmt = $conn->prepare($q . $limit);
        $stmt->execute();
        $array = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result["answerCount"] = $this->getAnswerCountForQuestionId($result["q_id"]);
            $result["carName"] = json_decode(app::getCarsById($result["carId"]))[0]->{"name"};
            $result["count"] = $this->getQuestionCount($result["q_id"]);
            array_push($array, $result);
        }

        header('Content-Type: application/json');
        echo json_encode($array);
    }


    public function questionAdd($entrance_id, $q_text, $carId, $q_image_url1, $q_image_url2, $q_image_url3)
    {
        //http://drkamal3.com/Mechanic/index.php?route=questionAdd&q_text=a&entrance_id=1&car=c


        echo "BB";

        $conn = MyPDO::getInstance();
        $q = "SELECT q_id FROM `questions` where q_text= :q_text and q_entrance_id = :entrance_id";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("q_text", $q_text);
        $stmt->bindParam("entrance_id", $entrance_id);
        $stmt->execute();
        /**/  /**/
        if ($stmt->fetch()) {
            echo "duplicate q";
        } else {
            $q = "insert into questions (q_entrance_id,q_text,carId,q_image_url1,q_image_url2,q_image_url3) values ($entrance_id,$q_text,$carId,$q_image_url1,$q_image_url2,$q_image_url3)";
            $stmt = $conn->prepare($q);
            $stmt->execute();
            echo "saved";
        }


    }

    //09112912140  mohandes saeli
    public function getQuestionCount($id)
    {
        $q_id = $id;
        $conn = MyPDO::getInstance();
        $q2 = "select COUNT(*) as q_count2 from seen_question where q_id=:q_id ";
        $stmt2 = $conn->prepare($q2);
        $stmt2->bindParam("q_id", $q_id);
        $stmt2->execute();
        return $stmt2->fetch(PDO::FETCH_ASSOC)["q_count2"];
    }


    public function addToCounterQuestion()
    {

        $q_id = app::get("q_id");
        $entrance_id = app::get("entrance_id");

        $conn = MyPDO::getInstance();

        $q = "select COUNT(*) as q_count from seen_question where q_id=:q_id and entrance_id=:entrance_id";


        $stmt = $conn->prepare($q);
        $stmt->bindParam("q_id", $q_id);
        $stmt->bindParam("entrance_id", $entrance_id);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC)["q_count"];

        if ($count == 0) {
            $q = "insert into seen_question (q_id,entrance_id) values ('$q_id','$entrance_id')";
            $stmt = $conn->prepare($q);
            $stmt->execute();
            echo "inserted";
        } else echo "repeat click";

    }

    public function questionFavorite()
    {
        //http://drkamal3.com/Mechanic/index.php?route=questionFavorite&q_id=1&entrance_id=1

        $entrance_id = app::get("entrance_id");
        $q_id = app::get("q_id");

        $conn = MyPDO::getInstance();
        $q = "SELECT id FROM `q_favorites` where q_id=:q_id and entrance_id=:entrance_id";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("q_id", $q_id);
        $stmt->bindParam("entrance_id", $entrance_id);
        $stmt->execute();

        if ($stmt->fetch()) {

            $q = "DELETE  FROM `q_favorites` where q_id=:q_id and entrance_id=:entrance_id";
            $stmt = $conn->prepare($q);
            $stmt->bindParam("q_id", $q_id);
            $stmt->bindParam("entrance_id", $entrance_id);
            $stmt->execute();
            echo "removed";
        } else {

            $q = "insert into `q_favorites` (entrance_id, q_id) values (:entrance_id,:q_id)";
            $stmt = $conn->prepare($q);
            $stmt->bindParam("q_id", $q_id);
            $stmt->bindParam("entrance_id", $entrance_id);
            $stmt->execute();
            echo "saved";
        }


    }


    public function questionMy()
    {
        //http://drkamal3.com/Mechanic/index.php?route=questionMy&entrance_id=1

        $entrance_id = app::get("entrance_id");


        $conn = MyPDO::getInstance();
        $q = "SELECT * FROM `questions` where entrance_id=:entrance_id";
        $stmt = $conn->prepare($q);

        $stmt->bindParam("entrance_id", $entrance_id);
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function questionChangeState()
    {
        //http://drkamal3.com/Mechanic/index.php?route=questionChangeState&id=1&status=1

        $id = app::get("id");
        $status = app::get("status");


        $conn = MyPDO::getInstance();
        $q = "update `questions` set status=:status where id=:id";
        $stmt = $conn->prepare($q);

        $stmt->bindParam("id", $id);
        $stmt->bindParam("status", $status);
        $stmt->execute();
        echo "done";
    }

    public function searchCar()
    {
        $conn = MyPDO::getInstance();
        $search = app::get("search");

        $q = "select * from cars where name like '%$search%' order by name asc ";


        /* if($search="*") $q = "select * from cars";*/

        $stmt = $conn->prepare($q);
        $stmt->execute();
        $cars = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($cars, $result);
        }
        echo json_encode($cars);
    }

    public function getAnswerCountForQuestionId($id)
    {
        $conn = MyPDO::getInstance();
        $q = "SELECT COUNT(*)  as nums from answers where q_id =$id";
        $stmt = $conn->prepare($q);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)["nums"];

    }

    public function sendImageAddress()
    {

        if (isset($_REQUEST['image'])) {
            echo "A";
            $image_name = rand(100000000, 99999999999);
            $image_name = "../questionImages/" . $image_name . ".png";

            $imgsrc = base64_decode($_REQUEST['image']);

            $fp = fopen($image_name, 'w');
            fwrite($fp, $imgsrc);

            if (fclose($fp)) {
                echo $image_name;
            } else {
                echo "0";
            }

            exit();
        }
    }

    public function calculate($url, $formatSize = true, $useHead = true)
    {
        /* echo "$a and $b";*/
/*        $conn = MyPDO::getInstance();
        $q = "SELECT id FROM `goods` WHERE 1";
        $stmt = $conn->prepare($q);
        $stmt->execute();
        $aa = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($aa, $result['id']);
        }

        foreach ($aa as $k => $v) {
            $i=" کارخانه شماره ".($v+1);
            echo $i."&&";
            $q2 = "update  store set company='$i '  where id = '$v'";
            $stmt2 = $conn->prepare($q2);
            $stmt2->execute();
        }*/

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
                $size = $clen .' B'; break;
            case $clen < 1048576:
                $size = round($clen / 1024, 2) .' KiB'; break;
            case $clen < 1073741824:
                $size = round($clen / 1048576, 2) . ' MiB'; break;
            case $clen < 1099511627776:
                $size = round($clen / 1073741824, 2) . ' GiB'; break;
        }

        echo $clen; // return formatted size

    }

    public function searchTitle()
    {
        $conn = MyPDO::getInstance();
        $search = app::get("search");

        $q = "select * from titles where name like '%$search%' order by name asc ";

        /* if($search="*") $q = "select * from cars";*/

        $stmt = $conn->prepare($q);
        $stmt->execute();
        $titles = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($titles, $result);
        }
        echo json_encode($titles);
    }

}