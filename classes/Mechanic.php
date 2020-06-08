<?php


class Mechanic
{
//SELECT users.x_location,(pow(users.x_location,2)) as pw from users ORDER BY pw ASC

    public function getMechanics()
    {
        //http://drkamal3.com/Mechanic/index.php?route=getStore3&lastId=0&carId=0&goodId=0&warrantyId=0&countryId=0&isStock=0#
        $conn = MyPDO::getInstance();
        $offset = app::get("offset");
        $jobId = $_REQUEST["jobId"];
        $regionId = $_REQUEST["regionId"];
        $x=app::get("x");
        $y=app::get("y");
        $sortBy = $_REQUEST["sortBy"];
/**/
        if ($jobId == -1 && $regionId != -1) {
            $errorJobArray = array();
            $tmpJobArray = array();
            $tmpJobArray["id"] = "-2";
            $tmpJobArray["entrance_id"] = "0";
            $tmpJobArray["movies"] = "0";
            $tmpJobArray["job_ids"] = "0";
            $tmpJobArray["region_id"] = "0";
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
            array_push($errorJobArray, $tmpJobArray);
            echo json_encode($errorJobArray);
            die();
        } else if ($jobId != -1 && $regionId == -1) {
            $errorRegionArray = array();
            $tmpRegionArray = array();
            $tmpRegionArray["id"] = "-3";
            $tmpRegionArray["entrance_id"] = "0";
            $tmpRegionArray["movies"] = "0";
            $tmpRegionArray["job_ids"] = "0";
            $tmpRegionArray["region_id"] = "0";
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
            array_push($errorRegionArray, $tmpRegionArray);
            echo json_encode($errorRegionArray);
            die();
        } else if ($jobId == -1 && $regionId == -1) {
            $errorRegionAndJobArray = array();
            $tmpRegionAndJobArray = array();
            $tmpRegionAndJobArray["id"] = "-4";
            $tmpRegionAndJobArray["entrance_id"] = "0";
            $tmpRegionAndJobArray["movies"] = "0";
            $tmpRegionAndJobArray["job_ids"] = "0";
            $tmpRegionAndJobArray["region_id"] = "0";
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
            array_push($errorRegionAndJobArray, $tmpRegionAndJobArray);
            echo json_encode($errorRegionAndJobArray);
            die();
        }

        if ($jobId != 0) {
            $jobFilter = " job_ids like '%,$jobId,%' or job_ids like '$jobId,%' or job_ids like '%,$jobId' or job_ids =$jobId ";
        } else {
            $jobFilter = "  job_ids like '%' ";
        }
        if ($regionId != 0) {
            $regionFilter = " region_id = '$regionId' ";
        } else {
            $regionFilter = " region_id like '%' ";
        }


        if ($sortBy == 0 /*default recently */) {
            $offset = $offset * 5;
            $limit = " order by users.id  desc limit $offset,5 ";
        } else if ($sortBy == 1 /*score */) {
            $offset = $offset * 5;
            $limit = " order by users.score desc limit $offset,5 ";
        }else if ($sortBy == 2 /*location */) {
            $offset = $offset * 5;
            $limit = " ORDER BY pow((pow(users.x_location-$x,2)+pow(users.y_location-$y,2)),0.5) limit $offset,5 ";
        }

        $mechanics = array();
        $q = "SELECT users.* FROM `users` where   ($jobFilter)  and  $regionFilter  $limit  ";


        $stmt = $conn->prepare($q );
        $stmt->execute();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result["job_ids"] = app::getJobsById($result["job_ids"]);
            $result["region_id"] = app::getRegionById($result["region_id"]);
            $result["movies"] = app::getMoviesBySize($result["movies"]);
            array_push($mechanics, $result);
        }

        header('Content-Type: application/json');
        echo json_encode($mechanics); /* */
    }


    public function searchJob()
    {
        $conn = MyPDO::getInstance();
        $search = app::get("search");

        $q = "select * from jobs where name like '%$search%' order by name asc ";


        /* if($search="*") $q = "select * from cars";*/

        $stmt = $conn->prepare($q);
        $stmt->execute();
        $jobs = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($jobs, $result);
        }
        echo json_encode($jobs);
    }
    public function searchRegion()
    {
        $conn = MyPDO::getInstance();
        $search = app::get("search");

        $q = "select * from regions where name like '%$search%' order by name asc ";


        /* if($search="*") $q = "select * from cars";*/

        $stmt = $conn->prepare($q);
        $stmt->execute();
        $regions = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($regions, $result);
        }
        echo json_encode($regions);
    }
}