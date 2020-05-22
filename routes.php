<?php
/**
 * Created by PhpStorm.
 * User: ahmad
 * Date: 02/15/2020
 * Time: 19:49
 */

define("DB_PASSWORD","8513050518ahmad");
define("DB_USERNAME","drkamal3_com_ahmad");
define("DB_NAME","drkamal3_com_mechanic");
define("DB_HOST","localhost");
define('DB_CHARSET', 'UTF-8');
define('PDO_DSN', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET);


define("route","route");
define("action","action");
//routes
define("sms","sms");
define("jobs","jobs");

//actions
define("prepareCode","prepareCode");
define("verifyCode","verifyCode");
define("registration","registration");

//data
define("mobile","mobile");
define("code","code");
define("type","type");
define("userId","userId");


//responses
define("error","error");
define("ok","ok");
define("errorCode","errorCode");


//keys
define("message","message");
define("loginId","loginId");
define("registerId","registerId");

//values
define("login","login");
define("sendSmsOk","sendSmsOk");
define("registrationStep1","registrationStep1");
define("registrationStep2","registrationStep2");
