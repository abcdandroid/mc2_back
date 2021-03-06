<?php


class MainPage
{


    public function getEtcData()
    {
        $conn = MyPDO::getInstance();
        $q = "SELECT * FROM `etcetera`";
        $stmt = $conn->prepare($q);
        $stmt->execute();
        $etcData = array();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($etcData, $result);
        }
        echo json_encode($etcData);
    }


    /*
  Mechanics
        $offset = app::get("offset");
        $jobId = $_REQUEST["jobId"];
        $regionId = $_REQUEST["regionId"];
        $x=app::get("x");
        $y=app::get("y");
        $sortBy = $_REQUEST["sortBy"];
*/
    /*
     * Store:
            $lastId = app::get("lastId");
            $carId = $_REQUEST["carId"];
            $goodId = $_REQUEST["goodId"];
            $warrantyId = $_REQUEST["warrantyId"];
            $countryId = $_REQUEST["countryId"];
            $isStock = $_REQUEST["isStock"];

    */
    /*
     * Questions:
     *
            $lastId = app::get("lastId");
            $carId = $_REQUEST["carId"];
            $titleId = $_REQUEST["titleId"];
            $sortBy = $_REQUEST["sortBy"];
            $showMyQuestion = $_REQUEST["showMyQuestion"];
            $entrance_id = app::get("entrance_id");
            $offset = app::get("offset");
    */
    /*
     * Admin:
     *
     */

    public function getMainPageData()
    {


        $conn = MyPDO::getInstance();
        $q = "SELECT * FROM `main_page`";
        $stmt = $conn->prepare($q);
        $stmt->execute();
        $mainPageData = array();

        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($result['field'] == 3) {
                $params = explode(',', $result['params']);
                $params[0] = json_decode(app::getCarsById($params[0]))[0];
                $params[1] = array('id' => $params[1], 'name' => app::getGoodById($params[1]));
                $params[2] = array('id' => $params[2], 'name' => app::getWarrantyById($params[2]));
                $params[3] = array('id' => $params[3], 'name' => app::getCountryById($params[3]));
                $result['params'] = array('type' => 'store', 'detail' => array("car" => $params[0], "goood" => $params[1], "warranty" => $params[2], "country" => $params[3], "isStockActive" => $params[4]));
            } else if ($result['field'] == 7) {
                $store = new Store();
                $result['params'] = array('type' => 'goood', 'detail' => $store->getStore3($result['params'])[0]);
            } else if ($result['field'] == 2) {
                $params = explode(',', $result['params']);
                $params[0] = json_decode(app::getCarsById($params[0]))[0];
                $params[1] = array('id' => $params[1], 'name' => app::getTitleById($params[1]));
                $result['params'] = array('type' => 'questionList', 'detail' => array('car' => $params[0], 'title' => $params[1], 'sortBy' => $params[2], 'showMyQuestion' => $params[3]));
            } else if ($result['field'] == 6) {
                $question = new QandA();
                $result['params'] = array('type' => 'question', 'detail' => $question->getQuestions($result['params'])[0]);
            } else if ($result['field'] == 1) {
                $params = explode(',', $result['params']);
                $params[0] = app::getJobsById($params[0])[0];
                $params[1] = app::getRegionById($params[1]);
                $result['params'] = array("type" => 'mechanicList', 'detail' => array('job' => $params[0], 'region' => $params[1], 'sortBy' => $params[2]));
            } else if ($result['field'] == 5) {
                $mechanic = new Mechanic();
                $result['params'] = array('type' => 'mechanic', 'detail' => $mechanic->getMechanics($result['params'])[0]);
            }
            array_push($mainPageData, $result);
        }

        $viewpagerData = array();
        $q2 = "SELECT * FROM `viewpager_place`";
        $stmt2 = $conn->prepare($q2);
        $stmt2->execute();

        while ($result = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            array_push($viewpagerData, $result);
        }

        echo json_encode(array("mainPageData" => $mainPageData, "viewpagerData" => $viewpagerData));


    }


}