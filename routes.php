<?php
/**
 * Created by PhpStorm.
 * User: ahmad
 * Date: 02/15/2020
 * Time: 19:49
 */

define("DB_PASSWORD", "8513050518ahmad");
define("DB_USERNAME", "drkamal3_com_ahmad");
define("DB_NAME", "drkamal3_com_mechanic");
define("DB_HOST", "localhost");
define('DB_CHARSET', 'UTF-8');
define('PDO_DSN', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET);


define("route", "route");
define("action", "action");
//routes
define("sms", "sms");
define("jobs", "jobs");

//actions
define("prepareCode", "prepareCode");
define("verifyCode", "verifyCode");
define("registration", "registration");

//data
define("mobile", "mobile");
define("code", "code");
define("type", "type");
define("userId", "userId");


//responses
define("error", "error");
define("ok", "ok");
define("errorCode", "errorCode");


//keys
define("message", "message");
define("loginId", "loginId");
define("registerId", "registerId");

//values
define("login", "login");
define("sendSmsOk", "sendSmsOk");
define("registrationStep1", "registrationStep1");
define("registrationStep2", "registrationStep2");


define("mp_mechanic_list", 1);  //1-offset 2-jobId  3-regionId  4-x  5-y  6-sortBy
define("mp_store", 2);          //1-lastId  2-carId  3-goodId  4-warrantyId  5-countryId  6-isStock
define("mp_question", 3);       //1-lastId  2-carId  3-titleId  4-sortBy  5-showMyQuestion  6-entrance_id  7-offset
define("mp_admin", 4);          //1-
define("mp_movie", 5);          //1-url
define("mp_good", 6);           //1-preview  2-sentence_2  3-sentence_3  4-sentence_1  5-voice  6-price_time  7-good_id  8-suitable_car  9-is_stock  10-phone  11-fileSize  12-price  13-warranty  14-company  15-id  16-thumbnails  17-made_by  18-good_desc  19-status
define("mp_answer", 7);         //1-address  2-job_ids  3-region_id  4-about  5-type  6-store_image_3  7-store_image_2  8-movies  9-y_location  10-a_entrance_id  11-score  12-store_image_1  13-mechanic_image  14-a_id  15-a_voice_url  16-name  17-store_name  18-phone_number  19-a_text  20-q_id  21-x_location  22-a_status  23-fileSize
define("mp_mechanic",8);        //1-entrance_id  2-address  3-job_ids  4-job_ids  5-region_id  6-about  7-store_image_3  8-sto`re_image_2  9-movies  10-y_location  11-score  12-store_image_1  13-mechanic_image  14-name  15-store_name  16-phone_number  17-id  18-x_location