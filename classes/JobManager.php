<?php


class JobManager
{

    public function __construct()
    {
    }

    public function getAllJobs()
    {
        $conn = MyPDO::getInstance();
        $q = "select * from jobs";
        $stmt = $conn->prepare($q);
        $stmt->execute();
        $jobs = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($jobs, $result);
        }
        echo json_encode($jobs);
    }


    public function getMediasByJobId()
    {
        $jobId = app::get("jobId");
        $conn = MyPDO::getInstance();
        $q = "select * from users where job_id=:job_id";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("job_id", $jobId);
        $stmt->execute();
        $users = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)["id"]) {
            array_push($users, $result);
        }

        $medias = array();
        foreach ($users as $userId) {
            $q = "select * from medias where user_id=:user_id";
            $stmt = $conn->prepare($q);
            $stmt->bindParam("user_id", $userId);
            $stmt->execute();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($medias, $result);
            }
        }
        echo json_encode($medias);
    }

    public function getMediasByLocationId()
    {
        $locationId = app::get("locationId");
        $conn = MyPDO::getInstance();

        $q = "select * from users where location_id=:location_id";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("location_id", $locationId);
        $stmt->execute();
        $users = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)["id"]) {
            array_push($users, $result);
        }

        $medias = array();
        foreach ($users as $userId) {
            $q = "select * from medias where user_id=:user_id";
            $stmt = $conn->prepare($q);
            $stmt->bindParam("user_id", $userId);
            $stmt->execute();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($medias, $result);
            }
        }
        echo json_encode($medias);
    }

    public function getMediaDetailByMediaId()
    {
        $conn = MyPDO::getInstance();
        $mediaId = app::get("mediaId");
        $q = "SELECT * FROM medias INNER JOIN users ON medias.user_id=users.id WHERE id=:media_id";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("media_id", $mediaId);
        $stmt->execute();
        $mediasByDetail = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)["id"]) {
            array_push($mediasByDetail, $result);
        }
        echo json_encode($mediasByDetail);
    }

    public function getMediasByFilter()
    {

        /*    $location_id=$_GET['location_id'];
            $job_id=$_GET['job_id'];
            $last_media_id=$_GET['last_media_id'];
            $search_key=$_GET['search_key'];

            $user_id=$_GET['user_id'];



            $filter="";
            if ($location_id!=0)
            {

                $filter="province=".$location_id;

            }



            if ($job_id!=0)
            {
                if ($location_id!=0)
                {
                    $filter=$filter." and ";
                }

                $filter=$filter." category=".$job_id;
            }








            if ($filter!="")
            {
                $filter="where ".$filter;
            }



            $filter2="";

            if ($last_media_id!=0)
            {
                if ($filter!="")
                {
                    $filter2=" and ";
                }
                else
                {
                    $filter2=" where ";
                }


                $filter2=$filter2."id<".$last_media_id;
            }



            if ($filter!=""||$filter2!="")
            {
                $filter3=" and title like '%$search_key%'";
            }
            else
            {
                $filter3="where title like '%$search_key%'";
            }*/
        $search_key = app::get("searchKey");;
        $conn = MyPDO::getInstance();
        $q = "select * from admin where  media_desc like '%$search_key%' order by id desc";
        echo $q;
        $stmt = $conn->prepare($q);
        $stmt->execute();
        $medias = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($medias, $result);
        }
        echo json_encode($medias);


        /*        $query="select * from ad $filter $filter2 $filter3 order by id desc limit 10";*/
    }

    public function getAdminMedias()
    {
        $conn = MyPDO::getInstance();
        $lastId = app::get("lastId");
        if ($lastId == 0)
            $q = "select * from admin order by id desc limit 8";
        else
            $q = "select * from admin where id<$lastId order by id desc limit 8";


        $stmt = $conn->prepare($q);
        $stmt->execute();
        $medias = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result["fileSize"] = $this->getRemoteFileSize($result["url"]);
            array_push($medias, $result);
        }

        header('Content-Type: application/json');
        echo json_encode($medias);
    }


    public function getFilteredData()
    {
        $conn = MyPDO::getInstance();
        $lastId = app::get("lastId");
        $search = app::get("search");



        if ($lastId == 0) {
            $q = "select * from admin where media_desc like %$search% order by id desc ";
            echo $q;
        } else
            $q = "select * from admin where id<$lastId and media_desc like %$search% order by id desc ";


        $stmt = $conn->prepare($q);
        $stmt->execute();
        $medias = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $result["fileSize"] = $this->getRemoteFileSize($result["url"]);
            array_push($medias, $result);

        }
        echo json_encode($medias);
    }

    function getRemoteFileSize($url, $formatSize = true, $useHead = true)
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

        $size = $clen;
        switch ($clen) {
            case $clen < 1024:
                $size = $clen . ' B';
                break;
            case $clen < 1048576:
                $size = round($clen / 1024, 2) . ' KiB';
                break;
            case $clen < 1073741824:
                $size = round($clen / 1048576, 2) . ' MiB';
                break;
            case $clen < 1099511627776:
                $size = round($clen / 1073741824, 2) . ' GiB';
                break;
        }

        return $clen; // return formatted size
    }


}