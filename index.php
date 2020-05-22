<?php
/**
 * Created by PhpStorm.
 * User: ahmad
 * Date: 02/15/2020
 * Time: 17:48
 */

include "routes.php";
include "Response.php";
include "app.php";
include "MyPDO.php";
include "classes/SmsManager.php";
include "classes/JobManager.php";
include "classes/Medias.php";
include "classes/QandA.php";
include "classes/Store.php";
include "classes/Ads.php";
include "classes/UploadedFiles.php";

/*
 * todo
 *
 * generate random code
 * clear user from sms table
 * insert code and phone number to sms table
 * send code to user
 *
 * ---
 *
 * compare code and phone number
 * */


$route = app::get(route);
//select items from data base you need. avoid using * in selects make less pressure in database
switch ($route) {
    case sms:
        new SmsManager(false);
        break;
    case "getAllJobs":
        $a = new JobManager();
        $a->getAllJobs();
        break;
    case "getMediasByJobId":
        $a = new JobManager();
        $a->getMediasByJobId();
        break;
    case "getMediasByLocationId":
        $a = new JobManager();
        $a->getMediasByLocationId();
        break;
    case "getMediaDetailByMediaId":
        $a = new JobManager();
        $a->getMediaDetailByMediaId();
        break;
    case "getMedias":
        $a = new JobManager();
        $a->getMedias();
        break;
    case "getAdminMedias":
        $a = new JobManager();
        $a->getAdminMedias();
        break;
    case "getAdminMediasByFilter":
        $a = new JobManager();
        $a->getMediasByFilter();
        break;
    case "getFilteredData":
        $a = new JobManager();
        $a->getFilteredData();
        break;
    case "questionsFetchAll":
        $a = new QandA();
        $a->questionsFetchAll();
        break;
    case "questionAdd":
        $a = new QandA();
        $a->questionAdd();
        break;
    case "questionFavorite":
        $a = new QandA();
        $a->questionFavorite();
        break;
    case "questionMy":
        $a = new QandA();
        $a->questionMy();
        break;
    case "questionChangeState":
        $a = new QandA();
        $a->questionChangeState();
        break;
    case "searchCar":
        $a = new QandA();
        $a->searchCar();
        break;
    case "addToCounterQuestion":
        $a = new QandA();
        $a->addToCounterQuestion();
        break;
    case "searchGood":
        $a = new Store();
        $a->searchGood();
        break;
    case "store":
        $a = new Store();
        $a->getStore();
        break;
    case "getGoodsByCarAndGoodName":
        $a = new Store();
        $a->getGoodsByCarAndGoodName();
        break;
    case "totalSearchInStore":
        $a = new Store();
        $a->totalSearchInStore();
        break;
    case "getGoodsByCar":
        $a = new Store();
        $a->getGoodsByCar();
        break;
    case "searchAutoCompleteGoodOrCar":
        $a = new Store();
        $a->searchAutoCompleteGoodOrCar1();
        break;
    case "searchGoodOrCar2":
        $a = new Store();
        $a->searchGoodOrCar2();
        break;
    case "getStore2":
        $a = new Store();
        $a->getStore2();
        break;
    case "getAds":
        $a = new Ads();
        $a->getAds();
        break;
    case "sendImageAddress":
        $a = new QandA();
        $a->sendImageAddress();
        break;
    case "upload":
        $a = new UploadedFiles();
        $a->upload();
        break;
    case "audioUpload":
        $a = new UploadedFiles();
        $a->audioUpload();
        break;
    case "addNewMechanic":
        $a = new UploadedFiles();
        $a->addNewMechanic();
        break;
    case "test":
        $a = new QandA();
        $a->calculate(126.813803317113144433,59.65869140625112233);
        break;
    default :
        echo "not valid route";
}

