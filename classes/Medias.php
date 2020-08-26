<?php


class Medias
{
    public function getAdminMedias()
    {
        include_once './../../vendor/autoload.php';
        $conn = MyPDO::getInstance();
        $offset = app::get("offset");
        $offset = $offset * 10;
        $q = "SELECT * FROM `admin` limit $offset,10";
        $stmt = $conn->prepare($q);
        $stmt->execute();
        $mechanic_movie = array();

        $ffmpeg = FFMpeg\FFMpeg::create(array(
            'ffmpeg.binaries' => getcwd() . '/' . 'ffmpeg',
            'ffprobe.binaries' => getcwd() . '/' . 'ffprobe',
            'timeout' => 3600,
            'ffmpeg.threads' => 20,
        ));


        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $movieName = substr(basename($result["movie_url"]), 0, strlen(basename($result["movie_url"])) - 4) . ".jpg";

            $imagePath = 'Movie Admin Previews/' . $movieName;
            /* */
            if (file_exists($imagePath)) {
                $result["movie_preview"] = $imagePath;
            } else {
                $video = $ffmpeg->open($result["movie_url"]);
                $video
                    ->frame(FFMpeg\Coordinate\TimeCode::fromString("00:00:00:01"))
                    ->save($imagePath, false, false);
                $result["movie_preview"] = $imagePath;
                /* */
            }


            $result["movie_size"] = app::getRemoteFileSize2($result["movie_url"]);
            //$result["movie_size"] = 0;
            array_push($mechanic_movie, $result);
        }

        if (!in_array(false, $mechanic_movie)) {
            echo json_encode($mechanic_movie);
        } else {
            $array1 = array();
            array_push($array1, array("id" => "0", "movie_size" => -1, "movie_url" => "", "movie_desc" => "", "movie_offset" => -1, "movie_preview" => ""));
            echo json_encode($mechanic_movie);
        }
    }


    public function getAdminMediasFromAparatApi()
    {
        include_once './../../vendor/autoload.php';
        $conn = MyPDO::getInstance();
        $offset = app::get("offset");
        $offset = $offset * 10;
        $q = "SELECT * FROM `admin` limit $offset,10";
        $stmt = $conn->prepare($q);
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
            $err = curl_error($curl);
            $response = json_decode($response, true); //because of true, it's in an array
            $result["movie_preview"] = $response["video"]["small_poster"];
            //$result["movie_size"] = $this->curl_get_file_size("https://as8.cdn.asset.aparat.com/aparat-video/b429055bfcbbfe929ed72d25451dd62823788371-240p.mp4") ;
            //$result["movie_size"] = $response["video"]["size"];
            //    $result["movie_size"] = 22;
            array_push($mechanic_movie, $result);
        }

        curl_close($curl);
        if (!in_array(false, $mechanic_movie)) {
            echo json_encode($mechanic_movie);
        } else {
            $array1 = array();
            array_push($array1, array("id" => "0", "movie_size" => -1, "movie_url" => "", "movie_desc" => "", "movie_offset" => -1, "movie_preview" => ""));
            echo json_encode($mechanic_movie);
        }
    }

    function curl_get_file_size()
    {

        $url = app::get("url");
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);

        $data = curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

        curl_close($ch);
        echo $size;
    }

    public
    function getMechanicMoviesFromAparatApi()
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
            array_push($array1, array("id" => "0", "user_id" => -1, "movie_size" => -1, "movie_url" => "", "movie_desc" => "", "movie_offset" => -1, "movie_preview" => ""));
            //return ($array1);

            echo json_encode($mechanic_movie);
        }/**/
    }

    public
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
            $result["movie_preview"] = $response["video"]["small_poster"];
            $result["movie_size"] = $response["video"]["size"];
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

    function aparatApiTest()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.aparat.com//etc/api/mostviewedvideos",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $response = json_decode($response, true); //because of true, it's in an array
        echo json_encode($response);
    }
}