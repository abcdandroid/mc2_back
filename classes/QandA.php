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

    public function questionMy($entrance_id)
    {
        //http://drkamal3.com/Mechanic/index.php?route=questionMy&entrance_id=1


        $conn = MyPDO::getInstance();
        $q = "SELECT * FROM `questions` where entrance_id=:entrance_id";
        $stmt = $conn->prepare($q);

        $stmt->bindParam("entrance_id", $entrance_id);
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getQuestions()
    {
        // http://drkamal3.com/Mechanic/index.php?route=getQuestions&lastId=0&lastSeenCount=0&carId=0&titleId=0&&sortBy=1&showMyQuestion=0&entrance_id=0
        $conn = MyPDO::getInstance();
        header('Content-Type: application/json');
        $lastId = app::get("lastId");
        $carId = $_REQUEST["carId"];
        $titleId = $_REQUEST["titleId"];
        $sortBy = $_REQUEST["sortBy"];
        $showMyQuestion = $_REQUEST["showMyQuestion"];
        $entrance_id = app::get("entrance_id");
        $offset = app::get("offset");


        if ($carId == -1 && $titleId != -1) {
            $errorCarArray = array();
            $tmpCarArray = array();
            $tmpCarArray["q_id"] = "-2";
            $tmpCarArray["q_entrance_id"] = "0";
            $tmpCarArray["q_text"] = "0";
            $tmpCarArray["carId"] = "0";
            $tmpCarArray["q_image_url1"] = "0";
            $tmpCarArray["q_image_url2"] = "0";
            $tmpCarArray["q_image_url3"] = "0";
            $tmpCarArray["q_status"] = "0";
            $tmpCarArray["q_title"] = "0";
            $tmpCarArray["seen_count"] = "0";
            $tmpCarArray["answerCount"] = "0";
            $tmpCarArray["carName"] = "0";
            array_push($errorCarArray, $tmpCarArray);
            echo json_encode($errorCarArray);
            die();
        } else if ($titleId == -1 && $carId != -1) {
            $errorTitleArray = array();
            $tmpTitleArray = array();
            $tmpTitleArray["q_id"] = "-3";
            $tmpTitleArray["q_entrance_id"] = "0";
            $tmpTitleArray["q_text"] = "0";
            $tmpTitleArray["carId"] = "0";
            $tmpTitleArray["q_image_url1"] = "0";
            $tmpTitleArray["q_image_url2"] = "0";
            $tmpTitleArray["q_image_url3"] = "0";
            $tmpTitleArray["q_status"] = "0";
            $tmpTitleArray["q_title"] = "0";
            $tmpTitleArray["seen_count"] = "0";
            $tmpTitleArray["answerCount"] = "0";
            $tmpTitleArray["carName"] = "0";
            array_push($errorTitleArray, $tmpTitleArray);
            echo json_encode($errorTitleArray);
            die();
        } else if ($titleId == -1 && $carId == -1) {
            $errorTitleAndCarArray = array();
            $tmpArray = array();
            $tmpArray["q_id"] = "-4";
            $tmpArray["q_entrance_id"] = "0";
            $tmpArray["q_text"] = "0";
            $tmpArray["carId"] = "0";
            $tmpArray["q_image_url1"] = "0";
            $tmpArray["q_image_url2"] = "0";
            $tmpArray["q_image_url3"] = "0";
            $tmpArray["q_status"] = "0";
            $tmpArray["q_title"] = "0";
            $tmpArray["seen_count"] = "0";
            $tmpArray["answerCount"] = "0";
            $tmpArray["carName"] = "0";
            array_push($errorTitleAndCarArray, $tmpArray);
            echo json_encode($errorTitleAndCarArray);
            die();
        }


        if ($carId != 0) {
            $carFilter = " carId  =$carId ";
        } else {
            $carFilter = "  carId like '%' ";
        }
        if ($titleId != 0) {
            $titleFilter = " q_title  = $titleId ";
        } else {
            $titleFilter = "  q_title like '%' ";
        }
        if ($showMyQuestion != 0) {
            $myQuestionFilter = "  questions.q_entrance_id=$entrance_id ";
        } else {
            $myQuestionFilter = "  questions.q_entrance_id like '%' ";
        }
        if ($sortBy == 1 /*default recently */) {/*
            $orderFilter = " questions.q_id ";*/

            $limit = " order by questions.q_id desc limit 5 ";
            if ($lastId != 0) $limit = " and questions.q_id<$lastId " . $limit;

        } else {/*$orderFilter = " seen_count ";*/
            $offset=$offset*5;
            $limit = " order by count_question.seen_count desc  limit $offset,5 ";
            //if ($lastSeenCount != 0) $limit = " and (count_question.seen_count<=$lastSeenCount and questions.q_id<$lastId) " . $limit;
        }


        $mainQ = "SELECT questions.q_id,questions.q_entrance_id ,questions.q_text, questions.carId, questions.q_image_url1,questions.q_image_url2,
 questions.q_image_url3,questions.q_status,questions.q_title,count_question.seen_count
  FROM questions LEFT JOIN count_question ON questions.q_id=count_question.q_id ";

        $mainQ = $mainQ . " where " . $carFilter . " and " . $titleFilter . " and " . $myQuestionFilter . $limit;

        /*echo $mainQ;*/

                $stmt = $conn->prepare($mainQ);
                $stmt->execute();
                $array = array();
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result["answerCount"] = $this->getAnswerCountForQuestionId($result["q_id"]);
                    $result["carName"] = json_decode(app::getCarsById($result["carId"]))[0]->{"name"};
                    array_push($array, $result);
                }
                $array=array("msg"=>$mainQ,"result"=>$array);

                echo json_encode($array);
    }


    public function questionAdd($entrance_id, $q_text, $carId, $q_image_url1, $q_image_url2, $q_image_url3)
    {
        //http://drkamal3.com/Mechanic/index.php?route=questionAdd&q_text=a&entrance_id=1&car=c


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

            $q2 = "SELECT seen_count FROM count_question WHERE count_question.q_id=$q_id";
            $stmt2 = $conn->prepare($q2);
            $stmt2->execute();
            $count2 = $stmt2->fetch(PDO::FETCH_ASSOC)["seen_count"];
            $count2 = $count2 + 1;
            echo $count2 . "**";
            $q3 = "UPDATE count_question SET count_question.seen_count = $count2 WHERE count_question.q_id= $q_id";
            $stmt3 = $conn->prepare($q3);
            $stmt3->execute();
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
    {/*
        print_r(DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, 'IR'));
        date_default_timezone_set('UTC');*/
        /*
                $date = new DateTime('now');
                echo 'UTC:     '.$date->format('Y-m-d H:i:s')."\n";*/


        try {
            $date = new DateTime('now', new DateTimeZone('Asia/tehran'));
            echo 'tehran: ' . $date->format('Y-m-d H:i:s') . "\n";
        } catch (Exception $e) {
        }


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
    }


}