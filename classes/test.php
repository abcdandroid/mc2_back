<?php

//require __DIR__ . '/vendor/autoload.php';
require './../../vendor/autoload.php';

use Melipayamak\MelipayamakApi;


use MathPHP\Algebra;

class Test
{
    public function testMsg()
    {/*
        ini_set("soap.wsdl_cache_enabled", "0");
         $sms_client = new SoapClient('http://api.payamak-panel.com/post/Send.asmx?wsdl',
               array('encoding' => 'UTF-8'));
          $param["username"] = "09215142663";
         $param["password"] = "8991";
         $param["text"] = "1234";
         $param["to"] = ["09159521477"];
         $param["bodyId"] = "26657";
         echo $sms_client->SendByBaseNumber2؛($param)->SendByBaseNumber2Result;
 */

        $username = "09215142663";
        $password = "8991";
        $api = new MelipayamakApi($username, $password);

        $sms = $api->sms("soap");
        $to = "09396991020";
        $text = "2122";
        $bodyId = 26657;

        $response = $sms->sendByBaseNumber($text, $to, $bodyId);

        echo ($response);


        /*

        $sms = $api->sms();
        $to = '09396991020';
        $from = '50004000142663';
        $text = 'تست وب سرویس ملی پیامک';
        $response = $sms->send($to, $from, $text);
        $json = json_decode($response);
        echo $json->Value;*/


    }

    public function testMath()
    {

        echo "math" . Algebra::gcd(8, 12);

    }


}

