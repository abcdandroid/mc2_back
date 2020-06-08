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

            $q4 = "INSERT INTO `count_question` (`id`, `q_id`, `seen_count`) VALUES (NULL,  $lastId, '1')";
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
        echo "A";
        var_dump($_FILES);
        if (isset($_FILES["recordedAnswer"])) {
            $fileName = basename($_FILES["recordedAnswer"]["name"]);
            $path = "answerAudios/" . $fileName;
            if (move_uploaded_file($_FILES["recordedAnswer"]["tmp_name"], $path)) {
                echo "audio saved";
                $q = "INSERT INTO `answers` (`a_id`, `a_entrance_id`, `q_id`, `a_text`, `a_voice_url`, `a_status`) VALUES (NULL, '$a_entrance_id', '$q_id', '', '$path', '0');";
                echo $q;
                $stmt2 = $conn->prepare($q);
                $stmt2->execute();
                echo "audio saved";

            } else echo "audio not saved";
        } else echo "file not received";
    }


    public function addNewMechanic()
    {
        $conn = MyPDO::getInstance();
        $m_entrance_id = app::get("m_entrance_id");
        $job_ids = app::get("job_ids");
        $address = app::get("address");
        $name = app::get("name");
        $store_name = app::get("store_name");
        $phone_number = app::get("phone_number");
        $about = app::get("about");
        $x_location = app::get("x_location");
        $y_location = app::get("y_location");

        //must check file extension and file size

        if (isset($_FILES["fileNo0"])) {
            $fileName0 = basename($_FILES["fileNo0"]["name"]);
            $path0 = "mechanic images/store images/" . $fileName0;
            if (move_uploaded_file($_FILES["fileNo0"]["tmp_name"], $path0)) echo "0 saved"; else echo "0 not saved";
        } else $path0 = "";
        if (isset($_FILES["fileNo1"])) {
            $fileName1 = basename($_FILES["fileNo1"]["name"]);
            $path1 = "mechanic images/store images/" . $fileName1;
            if (move_uploaded_file($_FILES["fileNo1"]["tmp_name"], $path1)) echo "1 saved"; else echo "1 not saved";
        } else $path1 = "";
        if (isset($_FILES["fileNo2"])) {
            $fileName2 = basename($_FILES["fileNo2"]["name"]);
            $path2 = "mechanic images/store images/" . $fileName2;
            if (move_uploaded_file($_FILES["fileNo2"]["tmp_name"], $path2)) echo "2 saved"; else echo "2 not saved";
        } else $path2 = "";
        if (isset($_FILES["fileNo3"])) {
            $fileName3 = basename($_FILES["fileNo3"]["name"]);
            $path3 = "mechanic images/profile images/" . $fileName3;
            if (move_uploaded_file($_FILES["fileNo3"]["tmp_name"], $path3)) echo "3 saved"; else echo "3 not saved";
        } else $path3 = "";


        $q = "INSERT INTO `users`
  ( `entrance_id` , `job_ids`,`address`,`name`,`store_image_1`,`store_image_2`,`store_image_3`,`mechanic_image`,`store_name`,`phone_number` ,`about`,`x_location` ,`y_location`) VALUES
  ( '$m_entrance_id', '$job_ids','$address','$name','$path0'        ,'$path1'       ,'$path2'       ,'$path3'          ,'$store_name','$phone_number','$about','$x_location','$y_location')";
        // echo $q;
        $stmt2 = $conn->prepare($q);
        $stmt2->execute();
        echo "saved";
        //echo "success";
    }


}