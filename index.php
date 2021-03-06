<?php
/**
 * Created by PhpStorm.
 * User: ahmad
 * Date: 02/15/2020
 * Time: 17:48
 */
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
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
include "classes/Mechanic.php";
include "classes/MainPage.php";
include "classes/test.php";


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
    case "sendSmsForAnswers":
        $a = new QandA();
        $a->sendSmsForAnswers();
        break;
    case "getEtcData":
        $a = new MainPage();
        $a->getEtcData();
        break;
    case "addToCalledMechanic":
        $a = new Mechanic();
        $a->addToCalledMechanic();
        break;
    case "getAllJobs":
        $a = new JobManager();
        $a->getAllJobs();
        break;
    case "getMediasByJobId":
        $a = new JobManager();
        $a->getMediasByJobId();
        break;
    case "aparatApiTest":
        $a = new Medias();
        $a->aparatApiTest();
        break;
    case "getAdminMediasFromAparatApi":
        $a = new Medias();
        $a->getAdminMediasFromAparatApi();
        break;
    case "getAdminMediasFromAparatApiWebView":
        $a = new Medias();
        $a->getAdminMediasFromAparatApiWebView();
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
        $a = new Medias();
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
    case "errorReport":
        $a = new QandA();
        $a->errorReport();
        break;
    case "getQuestions":
        $a = new QandA();
        $a->getQuestions();
        break;
    case "addToCounterQuestion":
        $a = new QandA();
        $a->addToCounterQuestion();
        break;
    case "getAnswers":
        $a = new QandA();
        $a->getAnswers();
        break;
    case "getAnswersById":
        $a = new QandA();
        $a->getAnswers(17);
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
    case "getStore3":
        $a = new Store();
        $a->getStore3();
        break;
    case "getAllWarranties":
        $a = new Store();
        $a->getAllWarranties();
        break;
    case "getCountriesAndWarranties":
        $a = new Store();
        $a->getCountriesAndWarranties();
        break;
    case "getAllCountries":
        $a = new Store();
        $a->getAllCountries();
        break;
    case "addToSold":
        $a = new Store();
        $a->addToSold();
        break;
    case "getAds":
        $a = new Ads();
        $a->getAds();
        break;
    case "sendImageAddress":
        $a = new QandA();
        $a->sendImageAddress();
        break;
    case "searchTitle":
        $a = new QandA();
        $a->searchTitle();
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
    case "getMechanics":
        $a = new Mechanic();
        $a->getMechanics();
        break;
    case "searchJob":
        $a = new Mechanic();
        $a->searchJob();
        break;
    case "searchRegion":
        $a = new Mechanic();
        $a->searchRegion();
        break;
    case "getMainPageData":
        $a = new MainPage();
        $a->getMainPageData();
        break;
    case "getMechanicMovies":
        $a = new Mechanic();
        $a->getMechanicMovies();
        break;
    case "test":
        $a = new QandA();
        $a->calculate("https://dl.songsara.net/RaMt!N/99/1-Farvardin/Chris%20Snelling%20-%20Gentle%20Moments%20%282020%29%20SONGSARA.NET/03%20Paper%20Planes.mp3");
        break;
    case "addTestMechanic":
        $a = new UploadedFiles();
        $a->addTestMechanic();
        break;
    case "addTestQuestionsAndAnswers":
        $a = new UploadedFiles();
        $a->addTestQuestionsAndAnswers();
        break;
    case "addTestAnswers":
        $a = new UploadedFiles();
        $a->addTestAnswers();
        break;
    case "addTestGoods":
        $a = new UploadedFiles();
        $a->addTestGoods();
        break;
    case "fileSize":
        $a = new Medias();
        $a->curl_get_file_size("https://as7.cdn.asset.aparat.com/aparat-video/ab3f273159ec4f059a1bd67b39ea3f6423814589-360p.mp4");
        break;
    case "testMsg":
        $a = new test();
        $a->testMsg();
        break;
    case "testMath":
        $a = new test();
        $a->testMath();
        break;
    default :
        echo "not valid route";
}

