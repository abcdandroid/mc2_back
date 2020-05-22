<?php /** @noinspection ALL */

/**
 * Created by PhpStorm.
 * User: ahmad
 * Date: 02/15/2020
 * Time: 20:06
 */
class SmsManager
{


    public function __construct($system = true)
    {
        //date_default_timezone_set("Asia/Tehran");
        $action = app::get(action);
        switch ($action) {
            case prepareCode:
                $this->prepareCode();
                break;
            case verifyCode:
                $this->verifyCode();
                break;
            case registration:
                $this->registration();
        }
    }

    public function prepareCode()
    {
//http://drkamal3.com/Mechanic/index.php?route=sms&action=prepareCode&mobile=147  00:49  //1685    20:19
        $conn = MyPDO::getInstance();
        $mobile = app::get(mobile);


        //remove from sms
        $q = "delete from sms where mobile=:mobile";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("mobile", $mobile);
        $stmt->execute();


        $code = rand(1000, 9999);
        $q = "insert into sms (mobile,code) values (:mobile,:code)";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("mobile", $mobile);
        $stmt->bindParam("code", $code);
        $stmt->execute();
        echo $code;
        //send sms

        /*
         $username = '09215142663';
         $password = '8991';
         $to = $mobile;
         $from = '500040001426';


         ini_set("soap.wsdl_cache_enabled", "0");
         $sms_client = new SoapClient('http://api.payamak-panel.com/post/send.asmx?wsdl',
             array('encoding' => 'UTF-8'));
         $param["username"] = "09215142663";
         $param["password"] = "8991";
         $param["from"] = "50004000142663";
         $param["to"] = ["$mobile"];
         $param["text"] = "کد فعال سازی شما $code می باشد";
         $param["isflash"] = false;
         $data = $sms_client->SendSimpleSMS($param)->SendSimpleSMSResult;
        */


        //var_dump($data);/**/


        //echo json_encode(array(message => sendSmsOk, code => $smsRest));

    }


    public function verifyCode()
    {
        //https://drkamal3.com/Mechanic/index.php?route=sms&action=verifyCode&mobile=091232177&code=1622
        $conn = MyPDO::getInstance();
        $code = app::get(code);
        $mobile = app::get(mobile);
        $q = "SELECT * FROM sms where mobile=:mobile and code =:code";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("mobile", $mobile);
        $stmt->bindParam("code", $code);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            //check timer
            date_default_timezone_set('UTC');
            $a = $stmt->fetch(PDO::FETCH_ASSOC)["timer"];
            $date = new DateTime();
            $current_timestamp = $date->getTimestamp();

            $deltaTime = $current_timestamp - strtotime($a);

            if ($deltaTime < 60 * 20) { //after 20 min from sms send time out
                $q = "SELECT * FROM entrance where mobile=:mobile";
                $stmt = $conn->prepare($q);
                $stmt->bindParam("mobile", $mobile);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($stmt->rowCount() == 0) {
                    echo(registrationStep1);
                } else {
                    echo json_encode(array("entranceId" => $result["id"], "type" => $result["type"]));
                }

            } else {
                echo "time out";
            }
            $q = "delete FROM sms where mobile=:mobile";
            $stmt = $conn->prepare($q);
            $stmt->bindParam("mobile", $mobile);
            $stmt->execute();
        } else {
            echo errorCode;
        }

    }

    public function registration()
    {
        $conn = MyPDO::getInstance();
        $mobile = app::get(mobile);
        $type = app::get(type);


        $q = "SELECT * FROM entrance where mobile=:mobile";
        $stmt = $conn->prepare($q);
        $stmt->bindParam("mobile", $mobile);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() == 0) {

            $q = "insert into entrance (mobile,type) values (:mobile,:type)";
            $stmt = $conn->prepare($q);
            $stmt->bindParam("mobile", $mobile);
            $stmt->bindParam("type", $type);
            $stmt->execute();
            $id = $conn->lastInsertId();
            echo json_encode(array(message => registrationStep2, registerId => $id));


        } else {
            echo "duplicate user";
        }


    }

}