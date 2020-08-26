<?php

class UploadedFiles
{
    public function upload()
    {
        // http://drkamal3.com/Mechanic/index.php?route=upload&entrance_id=1&q_text=jjj&car_id=2&title_id=4

        //must check file extension and file size
        // $entrance_id,$q_text,$carId,$q_image_url1,$q_image_url2,$q_image_url3
        $entrance_id = app::get("entrance_id");
        $q_text = app::get("q_text");
        $carId = app::get("car_id");
        $titleId = app::get("title_id");
        //$carId = app::getIdByCar($car_name) == -1 ? 1 : app::getIdByCar($car_name);
        //var_dump($_FILES);

        $conn = MyPDO::getInstance();
        $q = "SELECT COUNT(*)  as nums FROM `questions` where q_text= '$q_text' and q_entrance_id = '$entrance_id'";
        $stmt = $conn->prepare($q);
        /*  /stmt->bindParam("q_text", $q_text);
         $stmt->bindParam("entrance_id", $entrance_id);*/
        $stmt->execute();

        $num = intval($stmt->fetch(PDO::FETCH_ASSOC)["nums"]);


        if ($num != 0) {
            die("duplicate q");
        } else {
            echo "A";
            if (isset($_FILES["fileNo0"])) {
                $fileName0 = basename($_FILES["fileNo0"]["name"]);
                $path0 = "questionImages/" . $fileName0;
                if (move_uploaded_file($_FILES["fileNo0"]["tmp_name"], $path0)) echo "0 saved"; else echo "0 not saved";
            } else $path0 = "";
            if (isset($_FILES["fileNo1"])) {
                $fileName1 = basename($_FILES["fileNo1"]["name"]);
                $path1 = "questionImages/" . $fileName1;
                if (move_uploaded_file($_FILES["fileNo1"]["tmp_name"], $path1)) echo "1 saved"; else echo "1 not saved";
            } else $path1 = "";
            if (isset($_FILES["fileNo2"])) {
                $fileName2 = basename($_FILES["fileNo2"]["name"]);
                $path2 = "questionImages/" . $fileName2;
                if (move_uploaded_file($_FILES["fileNo2"]["tmp_name"], $path2)) echo "2 saved"; else echo "2 not saved";
            } else $path2 = "";


            //$q = "INSERT INTO `questions` ( `q_entrance_id`, `q_text`, `carId`, `q_image_url1`, `q_image_url2`, `q_image_url3`) VALUES ( $entrance_id, $q_text, $carId, $path0, $path1, $path2)";

            //do not add `` for numbers in query
            $q = "INSERT INTO `questions` ( `q_entrance_id`, `q_text`, `carId`, `q_image_url1`, `q_image_url2`, `q_image_url3` , `q_title`) VALUES ( $entrance_id,   '$q_text', $carId,      '$path0', ' $path1', ' $path2' , $titleId)";
            echo $q;
            $stmt2 = $conn->prepare($q);
            $stmt2->execute();
            $lastId = $conn->lastInsertId();

            $q3 = "INSERT INTO `seen_question` (`id`, `q_id`, `entrance_id`) VALUES (NULL,   $lastId, $entrance_id)";
            $stmt3 = $conn->prepare($q3);
            $stmt3->execute();

            $q4 = "INSERT INTO `count_question` (`id`, `q_id`, `seen_count`) VALUES (NULL,  $lastId, '0')";
            $stmt4 = $conn->prepare($q4);
            $stmt4->execute();
            /*  echo "success";*/
        }
    }


    public function audioUpload()
    {
        //must check file extension and file size
        $conn = MyPDO::getInstance();
        $a_entrance_id = app::get("a_entrance_id");
        $q_id = app::get("q_id");
        $a_text = app::get("a_text");
        if (isset($_FILES["recordedAnswer"])) {
            $fileName0 = basename($_FILES["recordedAnswer"]["name"]);
            $path0 = "answerAudios/" . $fileName0;
            if (move_uploaded_file($_FILES["recordedAnswer"]["tmp_name"], $path0)) echo "0 saved"; else echo "0 not saved";
        } else $path0 = "";
        $q = "INSERT INTO `answers` (`a_id`, `a_entrance_id`, `q_id`, `a_text`, `a_voice_url`, `a_status`) VALUES (NULL, '$a_entrance_id', '$q_id', '$a_text', '$path0', '0');";
        echo $q;
        $stmt2 = $conn->prepare($q);
        $stmt2->execute();
        echo "audio saved";

        /*
        if (isset($_FILES["recordedAnswer"])) {
            $fileName = basename($_FILES["recordedAnswer"]["name"]);
            $path = "answerAudios/" . $fileName;
            if (move_uploaded_file($_FILES["recordedAnswer"]["tmp_name"], $path)) {
                echo "audio saved";


            } else echo "audio not saved";
        } else echo "file not received";*/
    }


    public function addNewMechanic()
    {

        $conn = MyPDO::getInstance();
        $job_ids = app::get("job_ids");
        $region_id = app::get("region_id");
        $address = app::get("address");
        $name = app::get("name");
        $store_name = app::get("store_name");
        $phone_number_entrance = app::get("phone_number_entrance");
        $phone_number_mechanic = app::get("phone_number_mechanic");
        $about = app::get("about");
        $x_location = app::get("x_location");
        $y_location = app::get("y_location");
        $mID = app::get("m_id");
        if ($mID != 0) {
            $qGetImages = "select `store_image_1`,`store_image_2`,`store_image_3`,`mechanic_image` from `users` where id= $mID";
            $stmtGetImages = $conn->prepare($qGetImages);
            $stmtGetImages->execute();


            $store_images = $stmtGetImages->fetch(PDO::FETCH_ASSOC);
            $store_image_1 = $store_images["store_image_1"];
            $store_image_2 = $store_images["store_image_2"];
            $store_image_3 = $store_images["store_image_3"];
            $mechanic_image = $store_images["mechanic_image"];


            $imageAddresses = array();
            array_push($imageAddresses, $store_image_1);
            array_push($imageAddresses, $store_image_2);
            array_push($imageAddresses, $store_image_3);
            array_push($imageAddresses, $mechanic_image);

            foreach ($imageAddresses as $imageAddress) {
                unlink($imageAddress);

            }


            if (isset($_FILES["fileNo0"])) {
                $fileName0 = basename($_FILES["fileNo0"]["name"]);
                $path0 = "mechanic images/store images/" . $fileName0;
                if (move_uploaded_file($_FILES["fileNo0"]["tmp_name"], $path0)) $savedImages[0] = "0 saved";
                $savedImages[0] = "0 not saved";
            } else $path0 = "";
            if (isset($_FILES["fileNo1"])) {
                $fileName1 = basename($_FILES["fileNo1"]["name"]);
                $path1 = "mechanic images/store images/" . $fileName1;
                if (move_uploaded_file($_FILES["fileNo1"]["tmp_name"], $path1)) $savedImages[1] = "1 saved"; else $savedImages[1] = "1 not saved";
            } else $path1 = "";
            if (isset($_FILES["fileNo2"])) {
                $fileName2 = basename($_FILES["fileNo2"]["name"]);
                $path2 = "mechanic images/store images/" . $fileName2;
                if (move_uploaded_file($_FILES["fileNo2"]["tmp_name"], $path2)) $savedImages[2] = "2 saved"; else $savedImages[2] = "2 not saved";
            } else $path2 = "";
            if (isset($_FILES["fileNo3"])) {
                $fileName3 = basename($_FILES["fileNo3"]["name"]);
                $path3 = "mechanic images/profile images/" . $fileName3;
                if (move_uploaded_file($_FILES["fileNo3"]["tmp_name"], $path3)) $savedImages[3] = "3 saved"; else $savedImages[3] = "3 not saved";
            } else $path3 = "";


            $qUpdate = "update `users` set
                                                 `job` = '$job_ids',`region` =  '$region_id',`address`='$address',`name`  ='$name',
                                                 `store_image_1`='$path0',`store_image_2`= '$path1' ,`store_image_3`='$path2',`mechanic_image`= '$path3'  ,
                                                 `store_name`='$store_name',`phone_number` = '$phone_number_mechanic',`about`='$about',
                                                 `x_location`= '$x_location',`y_location`='$y_location'
                                                  where  id = $mID";


            $stmtUpdate = $conn->prepare($qUpdate);
            /**/
            $stmtUpdate->execute();
            echo json_encode(array("state" => "update"));

        } else {
            //must check file extension and file size
            $savedImages = array();
            if (isset($_FILES["fileNo0"])) {
                $fileName0 = basename($_FILES["fileNo0"]["name"]);
                $path0 = "mechanic images/store images/" . $fileName0;
                if (move_uploaded_file($_FILES["fileNo0"]["tmp_name"], $path0)) $savedImages[0] = "0 saved";
                $savedImages[0] = "0 not saved";
            } else $path0 = "";
            if (isset($_FILES["fileNo1"])) {
                $fileName1 = basename($_FILES["fileNo1"]["name"]);
                $path1 = "mechanic images/store images/" . $fileName1;
                if (move_uploaded_file($_FILES["fileNo1"]["tmp_name"], $path1)) $savedImages[1] = "1 saved"; else $savedImages[1] = "1 not saved";
            } else $path1 = "";
            if (isset($_FILES["fileNo2"])) {
                $fileName2 = basename($_FILES["fileNo2"]["name"]);
                $path2 = "mechanic images/store images/" . $fileName2;
                if (move_uploaded_file($_FILES["fileNo2"]["tmp_name"], $path2)) $savedImages[2] = "2 saved"; else $savedImages[2] = "2 not saved";
            } else $path2 = "";
            if (isset($_FILES["fileNo3"])) {
                $fileName3 = basename($_FILES["fileNo3"]["name"]);
                $path3 = "mechanic images/profile images/" . $fileName3;
                if (move_uploaded_file($_FILES["fileNo3"]["tmp_name"], $path3)) $savedImages[3] = "3 saved"; else $savedImages[3] = "3 not saved";
            } else $path3 = "";


            $q0 = "insert into entrance (mobile,type) values ('$phone_number_entrance',1)";
            $stmt0 = $conn->prepare($q0);
            //$stmt0->bindParam("mobile", $phone_number);

            $stmt0->execute();


            $q1 = "select max(entrance.id) as li from  entrance";
            $stmt1 = $conn->prepare($q1);
            $stmt1->execute();
            $m_entrance_id = $stmt1->fetch(PDO::FETCH_ASSOC)["li"];


            $q2 = "INSERT INTO `users`
                                 ( `entrance_id`  , `job`,`region`,`address`,`name`,`store_image_1`,`store_image_2`,`store_image_3`,`mechanic_image`,`store_name`,`phone_number` ,`about`,`x_location` ,`y_location`) VALUES
                                 ( '$m_entrance_id' ,'$job_ids','$region_id','$address','$name','$path0'        ,'$path1'       ,'$path2'       ,'$path3'          ,'$store_name','$phone_number_mechanic','$about','$x_location','$y_location')";

            $stmt2 = $conn->prepare($q2);
            $stmt2->execute();

            $q3 = "select max(id) as li from  users";
            $stmt3 = $conn->prepare($q3);
            $stmt3->execute();
            $m_id = $stmt3->fetch(PDO::FETCH_ASSOC)["li"];
            echo json_encode(array("state" => "saved", "entrance_id" => $m_entrance_id, "m_id" => $m_id, "saved_images" => $savedImages));
        }

    }

    public function addTestMechanic()
    {

        $conn = MyPDO::getInstance();


        for ($i = 1; $i <= 100; $i++) {

               $job_ids = ceil($i / 15) ;

                   $region_id = ceil($i / 6.5);
                   $address = "address" . $i;
                $name = "name" . $i;
                   $store_name = "store_name" . $i;
                   if($i<10){
                   $phone_number_entrance = "0915952140" . $i ;
                       $phone_number_mechanic = "0939699102" . $i ;
                   }
                   else if($i<100){
                       $phone_number_entrance = "091595214" . $i ;
                       $phone_number_mechanic = "093969910" . $i ;
                   }
                      $about = "about" . $i;;
                   $x_location = "36.".(9 * $i + 99)."2341717";
                   $y_location = "59.". (9 * $i + 99)."2172912" ;


                   $q0 = "insert into entrance (mobile,type) values ('$phone_number_entrance',1)";
                  $stmt0 = $conn->prepare($q0);
                   //$stmt0->bindParam("mobile", $phone_number);

                   $stmt0->execute();


                   $q1 = "select max(entrance.id) as li from  entrance";
                   $stmt1 = $conn->prepare($q1);
                   $stmt1->execute();
                   $m_entrance_id = $stmt1->fetch(PDO::FETCH_ASSOC)["li"];


                   $q2 = "INSERT INTO `users`
                                        ( `entrance_id`  , `job`,`region`,`address`,`name`,`store_image_1`,`store_image_2`,`store_image_3`,`mechanic_image`,`store_name`,`phone_number` ,`about`,`x_location` ,`y_location`) VALUES
                                        ( '$m_entrance_id' ,'$job_ids','$region_id','$address','$name','$path0'        ,'$path1'       ,'$path2'       ,'$path3'          ,'$store_name','$phone_number_mechanic','$about','$x_location','$y_location')";

                   $stmt2 = $conn->prepare($q2);
                   $stmt2->execute();/**/
            echo "$x_location,$y_location" ."<br>";

        }


    }
}