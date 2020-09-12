<?php


class Mechanic
{
//SELECT users.x_location,(pow(users.x_location,2)) as pw from users ORDER BY pw ASC

    public function getMechanics($mp_id = -1)
    {
        //  http://drkamal3.com/Mechanic/index.php?route=getMechanics&offset=0&jobId=0&regionId=0&x=0&y=0&sortBy=0
        $conn = MyPDO::getInstance();
        //$mp_id = $_REQUEST["mp_id"];
        if ($mp_id != -1) {
            $q = "SELECT users.* FROM `users` where id= $mp_id";
        } else {
            $offset = app::get("offset");
            $jobId = $_REQUEST["jobId"];
            $regionId = $_REQUEST["regionId"];
            $x = app::get("x");
            $y = app::get("y");
            $sortBy = $_REQUEST["sortBy"];
            /**/
            if ($jobId == -1 && $regionId != -1) {
                $errorJobArray = array();
                $tmpJobArray = array();
                $tmpJobArray["id"] = "-2";
                $tmpJobArray["entrance_id"] = "0";
                $tmpJobArray["movies"] = array();
                $tmpJobArray["job"] = array();
                $tmpJobArray["region"] =array("id"=>-10,"name"=>"");
                $tmpJobArray["address"] = "0";
                $tmpJobArray["name"] = "0";
                $tmpJobArray["store_image_1"] = "0";
                $tmpJobArray["store_image_2"] = "0";
                $tmpJobArray["store_image_3"] = "0";
                $tmpJobArray["mechanic_image"] = "0";
                $tmpJobArray["store_name"] = "0";
                $tmpJobArray["phone_number"] = "0";
                $tmpJobArray["about"] = "0";
                $tmpJobArray["x_location"] = "0";
                $tmpJobArray["y_location"] = "0";
                $tmpJobArray["score"] = "0";
                $tmpJobArray["score_state"] = "0";
                $tmpJobArray["is_signed"] = "0";
                array_push($errorJobArray, $tmpJobArray);
                echo json_encode(array("msg" => "errorJob", "mechanic" => $errorJobArray));
                die();
            } else if ($jobId != -1 && $regionId == -1) {
                $errorRegionArray = array();
                $tmpRegionArray = array();
                $tmpRegionArray["id"] = "-3";
                $tmpRegionArray["entrance_id"] = "0";
                $tmpRegionArray["movies"] = array();
                $tmpRegionArray["job"] = array();
                $tmpRegionArray["region"] = array("id"=>-10,"name"=>"");
                $tmpRegionArray["address"] = "0";
                $tmpRegionArray["name"] = "0";
                $tmpRegionArray["store_image_1"] = "0";
                $tmpRegionArray["store_image_2"] = "0";
                $tmpRegionArray["store_image_3"] = "0";
                $tmpRegionArray["mechanic_image"] = "0";
                $tmpRegionArray["store_name"] = "0";
                $tmpRegionArray["phone_number"] = "0";
                $tmpRegionArray["about"] = "0";
                $tmpRegionArray["x_location"] = "0";
                $tmpRegionArray["y_location"] = "0";
                $tmpRegionArray["score"] = "0";
                $tmpJobArray["score_state"] = "0";
                $tmpJobArray["is_signed"] = "0";
                array_push($errorRegionArray, $tmpRegionArray);
                echo json_encode(array("msg" => "errorRegion", "mechanic" => $errorRegionArray));
                die();
            } else if ($jobId == -1 && $regionId == -1) {
                $errorRegionAndJobArray = array();
                $tmpRegionAndJobArray = array();
                $tmpRegionAndJobArray["id"] = "-4";
                $tmpRegionAndJobArray["entrance_id"] = "0";
                $tmpRegionAndJobArray["movies"] = array();
                $tmpRegionAndJobArray["job"] = array();
                $tmpRegionAndJobArray["region"] = array("id"=>-10,"name"=>"");
                $tmpRegionAndJobArray["address"] = "0";
                $tmpRegionAndJobArray["name"] = "0";
                $tmpRegionAndJobArray["store_image_1"] = "0";
                $tmpRegionAndJobArray["store_image_2"] = "0";
                $tmpRegionAndJobArray["store_image_3"] = "0";
                $tmpRegionAndJobArray["mechanic_image"] = "0";
                $tmpRegionAndJobArray["store_name"] = "0";
                $tmpRegionAndJobArray["phone_number"] = "0";
                $tmpRegionAndJobArray["about"] = "0";
                $tmpRegionAndJobArray["x_location"] = "0";
                $tmpRegionAndJobArray["y_location"] = "0";
                $tmpRegionAndJobArray["score"] = "0";
                $tmpJobArray["score_state"] = "0";
                $tmpJobArray["is_signed"] = "0";
                array_push($errorRegionAndJobArray, $tmpRegionAndJobArray);
                echo json_encode(array("msg" => "errorBoth", "mechanic" => $errorRegionAndJobArray));
                die();
            }

            if ($jobId != 0) {
                $jobFilter = " job like '%,$jobId,%' or job like '$jobId,%' or job like '%,$jobId' or job =$jobId ";
            } else {
                $jobFilter = "  job  like '%' ";
            }
            if ($regionId != 0) {
                $regionFilter = " region  = '$regionId' ";
            } else {
                $regionFilter = " region  like '%' ";
            }

            $segmentCount=20;
            if ($sortBy == 0 /*default recently */) {
                $offset = $offset * $segmentCount;
                $limit = " order by users.id  desc limit $offset,$segmentCount ";
            } else if ($sortBy == 1 /*score */) {
                $offset = $offset * $segmentCount;
                $limit = " order by users.score desc limit $offset,$segmentCount ";
            } else if ($sortBy == 2 /*location */) {
                $offset = $offset * $segmentCount;
                $limit = " ORDER BY pow((pow(users.x_location-$x,2)+pow(users.y_location-$y,2)),0.5) limit $offset,$segmentCount ";
            }

            $q = "SELECT users.* FROM `users` where   ($jobFilter)  and  $regionFilter and is_signed=1	 $limit ";
        }
        $mechanics = array();
        $stmt = $conn->prepare($q);
        $stmt->execute();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result["job"] = app::getJobsById($result["job"]);
            $result["region"] = app::getRegionById($result["region"]);
            $result["movies"] = app::getMoviesBySizAndDesc($result["id"] );
            array_push($mechanics, $result);
        } 
        if ($mp_id == -1) {
            echo json_encode(array("msg" => $q, "mechanic" => $mechanics)); /* */
            return null;
        } else
            return $mechanics;
    }


    public function searchJob($mp_id = -1)
    {
        // http://drkamal3.com/Mechanic/index.php?route=searchJob&search=%D8%A2%D9%BE

        $conn = MyPDO::getInstance();
        if ($mp_id != -1) {
            $q = "select * from jobs where id = $mp_id  ";
        } else {
            $search = app::get("search");

            $q = "select * from jobs where name like '%$search%' order by name asc ";

        }
        /* if($search="*") $q = "select * from cars";*/

        $stmt = $conn->prepare($q);
        $stmt->execute();
        $jobs = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($jobs, $result);
        }
        if ($mp_id == -1) {
            header('Content-Type: application/json');
            echo json_encode($jobs);
        } else
            return $jobs;
    }

    public function searchRegion($mp_id = -1)
    {
        $conn = MyPDO::getInstance();
        if ($mp_id != -1) {
            $q = "select * from regions where id = $mp_id  ";
        } else {
        $search = app::get("search");

        $q = "select * from regions where name like '%$search%' order by name asc ";
        }
        /* if($search="*") $q = "select * from cars";*/

        $stmt = $conn->prepare($q);
        $stmt->execute();
        $regions = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($regions, $result);
        }
        if ($mp_id == -1) {
            header('Content-Type: application/json');
            echo json_encode($regions);
        } else
            return $regions;
    }

    function getMechanicMovies()
    {
        include_once './../../vendor/autoload.php';
        $conn = MyPDO::getInstance();
        $id = app::get("id");
        $q = "select  * from users_movie where user_id= :id ";
        $stmt = $conn->prepare($q);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $mechanic_movie = array();

        $curl = curl_init();

        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www.aparat.com/etc/api/video/videohash/" . $result["movie_uid"],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache"
                ),
            ));
            $response = curl_exec($curl);
            $response = json_decode($response, true); //because of true, it's in an array
            $result["movie_preview"] = $response["video"]["big_poster"];
          //  $result["movie_size"] = $response["video"]["size"];
            array_push($mechanic_movie, $result);
        }
        curl_close($curl);

        if (!in_array(false, $mechanic_movie)) {
            //  return ($mechanic_movie);
            echo json_encode($mechanic_movie);
        } else {
            $array1 = array();
            array_push($array1, array("id" => "0", "user_id" => -1, "movie_size" => -1, "movie_url" => "", "movie_desc" => "", "movie_offset" => -1, "movie_preview" => ""));
            //return ($array1);

            echo json_encode($mechanic_movie);
        }/**/
    }

    public function getMechanicMovies0()
    {
        include_once './../../vendor/autoload.php';
        $conn = MyPDO::getInstance();
        $id = app::get("id");
        $q = "select  * from users_movie where user_id= :id ";
        $stmt = $conn->prepare($q);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $mechanic_movie = array();
        $ffmpeg = FFMpeg\FFMpeg::create(array(
            'ffmpeg.binaries' => getcwd() . '/' . 'ffmpeg',
            'ffprobe.binaries' => getcwd() . '/' . 'ffprobe',
            'timeout' => 3600,
            'ffmpeg.threads' => 12,
        ));

        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $movieName = substr(basename($result["movie_url"]), 0, strlen(basename($result["movie_url"])) - 4) . ".jpg";

            $imagePath = 'Movie mechanic  previews/' . $movieName;
            if (file_exists($imagePath)) {
                $result["movie_preview"] = $imagePath;
            } else {
                $video = $ffmpeg->open($result["movie_url"]);
                $video
                    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($result["movie_offset"]))
                    ->save($imagePath, false, false);
                $result["movie_preview"] = $imagePath;
            }


            $result["movie_size"] = app::getRemoteFileSize($result["movie_url"]);
            array_push($mechanic_movie, $result);
        }

        if (!in_array(false, $mechanic_movie)) {
          //  return ($mechanic_movie);
            echo json_encode($mechanic_movie);
        } else {
            $array1 = array();
            array_push($array1, array("id" => "0", "user_id" => -1, "movie_size" => -1, "movie_url" => "", "movie_desc" => "", "movie_offset" => -1, "movie_preview" => "" ));
            //return ($array1);

            echo json_encode($mechanic_movie);
        }/**/
    }


    public function addToCalledMechanic()
    {
        header('Content-Type: application/json');
        $conn = MyPDO::getInstance();
        $userId = app::get("userId");
        $goodId = app::get("mechanicId");

        $date = new DateTime('now', new DateTimeZone('Asia/tehran'));
        $currentTime = $date->format('Y-m-d H:i:s');

        //$q = "INSERT INTO `called_mechanics` (  `user_id`, `good_id`, `date`) VALUES (NULL, :userId, :goodId, :currentTime);";
        $q ="INSERT INTO `called_mechanics` (`customer_id`, `mechanic_id`, `date`) VALUES (:userId, :goodId, :currentTime)";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("userId", $userId);
        $stmt->bindParam("goodId", $goodId);
        $stmt->bindParam("currentTime", $currentTime);
        $stmt->execute();

    }

}