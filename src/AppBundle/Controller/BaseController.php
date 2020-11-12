<?php

namespace AppBundle\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use SysSecurityBundle\Entity\Client;
use TransactionApiBundle\Entity\NoPersist\ResponseBlob;
use TransactionApiBundle\Entity\Transaction;

class BaseController extends Controller
{
    const TRANSACTION_OK = 200;
    const TRANSACTION_ERROR = 500;
    const TRANSACTION_CRITICAL = 300;

    const KYA_WEBSITE="https://www.kya-energy.com";
    const BASE_URL="https://www.kya-pay.kya-energy.com";
    const PAYDUNYA_RETURN_URL="https://www.kya-pay-dev.kya-energy.com/8004064b17546e4380ce83d1be75b50dkfj/api/kya/paydunya/payment/return";
    const PAYDUNYA_CALLBACK_URL="https://www.kya-pay-dev.kya-energy.com/8004064b17546e4380ce83d1be75b50dkfj/api/kya/paydunya/payment/confirm";


    /*test*/
    const TEST_PAYDUNYA_KEY_MAIN="RbXP0VEg-17rE-AtO8-DmIW-9p72ddc8ue9D";

    const TEST_PAYDUNYA_KEY_PUBLIC="test_public_eSvYJ5FDh8KQIlgqWZN9Gb2htOE";

    const TEST_PAYDUNYA_KEY_PRIVATE="test_private_JG63T2Ek1RHzuAPX959XOIx2PjP";

    const TEST_PAYDUNYA_TOKEN="StWrrJixVo7wuOtwuLBX";

//    const TEST_PAYDUNYA_KEY_PUBLIC="test_public_d4QHFMS7OyYFzFrsWYMzgkD2ta4";
//
//    const TEST_PAYDUNYA_KEY_PRIVATE="test_private_pKeVnBBC26kMQLnxCwWgjia9oU7";
//
//    const TEST_PAYDUNYA_TOKEN="uzHfqzXhNq6chJFpUWdX";

//    /*===end test ======*/


    const PAYGATE_INIT_PAY_URL = "https://paygateglobal.com/api/v1/pay";

    const PAYGATE_TRANSACTION_URL="https://paygateglobal.com/v1/page?token=";

    const PAYDUNYA_INIT_PAY_URL_TEST = "https://app.paydunya.com/sandbox-api/v1/checkout-invoice/create";

    const PAYDUNYA_INIT_PAY_URL = "https://app.paydunya.com/api/v1/checkout-invoice/create";

    const SMS_ZEDEKA_HOST="api.smszedekaa.com";

    const SMS_ZEDEKA_CLIENT_ID="54911dcd-e69c-4030-9328-4b848c64c4db";

    const SMS_ZEDEKA_SENDER="KYA";

    const KYA_STUDENT_AMOUNT_DAY=1000;
    const KYA_STUDENT_AMOUNT_WEEK=5000;
    const KYA_STUDENT_AMOUNT_MONTH=15000;

    const KYA_ACADEMIC_AMOUNT_DAY=1500;
    const KYA_ACADEMIC_AMOUNT_WEEK=7000;
    const KYA_ACADEMIC_AMOUNT_MONTH=25000;
    const KYA_ACADEMIC_AMOUNT_TRIMESTER=70000;
    const KYA_ACADEMIC_AMOUNT_SEMESTER=130000;
    const KYA_ACADEMIC_AMOUNT_ANNUAL=250000;

    const KYA_ENTERPRISE_AMOUNT_DAY=2000;
    const KYA_ENTERPRISE_AMOUNT_WEEK=12000;
    const KYA_ENTERPRISE_AMOUNT_MONTH=40000;
    const KYA_ENTERPRISE_AMOUNT_TRIMESTER=100000;
    const KYA_ENTERPRISE_AMOUNT_SEMESTER=180000;
    const KYA_ENTERPRISE_AMOUNT_ANNUAL=300000;


    protected function ClientRepo () {
        return $this->getDoctrine()->getRepository("SysSecurityBundle:Client");
    }

    protected function VerificationRepo () {
        return $this->getDoctrine()->getRepository("SysSecurityBundle:Verification");
    }

    protected function ClientLoginRepo () {
        return $this->getDoctrine()->getRepository("SysSecurityBundle:ClientLogin");
    }

    protected function TransactionRepo () {
        return $this->getDoctrine()->getRepository("TransactionApiBundle:Transaction");
    }

    protected function LicenceKeyRepo () {
        return $this->getDoctrine()->getRepository("SysSecurityBundle:LicenceKey");
    }

    protected function errorResponseBlob($message = "",$error=-1)
    {
        $response = new ResponseBlob();
        $response->setError($error);
        $response->setMessage($message);
        return $response;
    }

    protected function okResponseBlob($data)
    {
        $response = new ResponseBlob();
        $response->setError(0);
        $response->setMessage("");
        $response->setData($data);
        return $response;
    }


    protected function serialize($data)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer->serialize($data, 'json');

    }

    protected function deserialize($data, $obj)
    {

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer->deserialize($data, $obj, 'json');
    }
    protected function getNowTimeStamp () {
        $date = new \DateTime();
        return $date->getTimestamp();
    }

    function generateRandomString($length){
        $str= ''.$this->getNowTimeStamp();
        //$res = sha1($str);
        $res=hash('sha3-512',$str);

        $total='';
        $value2='';
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if(strlen($res)>6){
            $value1=substr($res,1,$length);

            while (strlen($total)< $length){
                $value2.=$this->generate_string($permitted_chars,$length);
                $result=$value1.$value2;
                $total.=$result;
            }

            if(strlen($total)>$length){
                $total=substr($total,1,$length);
            }
            return $total;
        }
    }
    function generate_string($input, $strength = 16) {
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }

    function generateRandomNumber($length){
        $st=mt_rand(1000,9999);
        return $st;
    }
    function generateRandomNumberBasedOnTimestamp($length){

        $ss=strtotime(date('Y-m-d H:i:s'));
        $st=mt_rand(1000,9999);
        $all=intval($ss)+intval($st);

        $response=substr("".$all,1,$length);
        return $response;
    }



    /* create log entries for transactions */

    public function logTransactions($data,$status,$response,$client_id,$type,LoggerInterface $logger)
    {
        if($status == self::TRANSACTION_OK) {
            $logger->info('Client: '.$client_id. ' Type:'. $type ,[
                'data' => $data,
                'response' => $response
            ]);
        }

        if($status == self::TRANSACTION_ERROR) {
            $logger->error('Client: '.$client_id. ' Type:'. $type ,[
                'data' => $data,
                'response' => $response
            ]);
        }

        if($status == self::TRANSACTION_CRITICAL) {
            $logger->critical('Client: '.$client_id. ' Type:'. $type ,[
                'data' => $data,
                'response' => $response
            ]);
        }
    }

    public function getChannel($pay_method=0){

        $channel='';
        $channel_in_french='';

        switch (intval($pay_method)){
            case 1:
                $channel='card';
                $channel_in_french='Carte Bancaire';

                break;

            case 2:
                $channel='wari';
                $channel_in_french='Wari';
                break;

            case 4:
                $channel='mtn-benin';
                $channel_in_french= 'Mtn -Benin';
                break;

            case 5:
                $channel='moov-benin';
                $channel_in_french= 'Moov -Benin';

                break;

            case 6:
                $channel='mtn-ci';
                $channel_in_french= 'Mtn -Côte d\'Ivoire';

                break;

            case 7:
                $channel='orange-money-ci';
                $channel_in_french= 'Orange Money -Côte d\'Ivoire';

                break;

            case 8:
                $channel='orange-money-senegal';
                $channel_in_french= 'Orange Money -Senegal';

                break;

            case 9:
                $channel='free-money-senegal';
                $channel_in_french= 'Free Money -Senegal';

                break;

            case 10:
                $channel='api-cash-senegal';
                $channel_in_french= 'Api Cash -Senegal';

                break;

            case 11:
                $channel='wizall-senegal';
                $channel_in_french= 'Wizall -Senegal';

                break;
        }

        $data=[];
        $data['channel']=$channel;
        $data['channel_in_french']=$channel_in_french;
        return $data;
    }

    /*
     * INIT paygate transaction
     */

    public function initPaygateTransaction($client_id, $phone_number=null, $amount,$type,$amount_category){
        $transaction = new Transaction();

        $details = "Achat Clé d'activation de KYA-SolDesign";
        $source=0;
        $data=[];

        if (preg_match("#^[9,7]{1}[0-3]{1}[0-9]{6}$#", $phone_number))
        {
            $details = "Achat Clé d'activation de KYA-SolDesign à travers T-Money.";
            $source=1;
        } else {
            if(preg_match("#^[9]{1}[6-9]{1}[0-9]{6}$#", $phone_number)){
                $details = "Achat Clé d'activation de KYA-SolDesign à travers Flooz.";
                $source=2;
            }
        }

        if($source==0) {
            $data['response'] = false;
            $data['transaction'] = null;
            return $data;
        }

        $transaction->setDetails($details);
        $transaction->setClientId($client_id);
        $transaction->setAmount(intval($amount));
        $transaction->setState(0);
        $transaction->setPaymentMode($source);
        $transaction->setProvider('PAYGATE');
        $transaction->setType($type);
        $transaction->setAmountCategory($amount_category);
        $transaction->setUsername($phone_number);
        $transaction->setCreatedAt(strtotime(date('Y-m-d H:i:s')));
        $transaction->setUpdatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($transaction);
        $em->flush();

        $data['response'] = true;
        $data['transaction'] = $transaction;
        return $data;
    }

    public function getAmountToPay($licence_type,$amount_category){
        $amount=0;
        if(intval($licence_type)==1){
            //enterprise
            switch (intval($amount_category)){
                case 1:
                    $amount=BaseController::KYA_ENTERPRISE_AMOUNT_DAY;
                    break;
                case 2:
                    $amount=BaseController::KYA_ENTERPRISE_AMOUNT_WEEK;
                    break;
                case 3:
                    $amount=BaseController::KYA_ENTERPRISE_AMOUNT_MONTH;
                    break;
                case 4:
                    $amount=BaseController::KYA_ENTERPRISE_AMOUNT_TRIMESTER;
                    break;
                case 5:
                    $amount=BaseController::KYA_ENTERPRISE_AMOUNT_SEMESTER;
                    break;
                case 6:
                    $amount=BaseController::KYA_ENTERPRISE_AMOUNT_ANNUAL;
                    break;
            }
        }

        if(intval($licence_type)==2){
            //academic
            switch (intval($amount_category)){
                case 1:
                    $amount=BaseController::KYA_ACADEMIC_AMOUNT_DAY;
                    break;
                case 2:
                    $amount=BaseController::KYA_ACADEMIC_AMOUNT_WEEK;
                    break;
                case 3:
                    $amount=BaseController::KYA_ACADEMIC_AMOUNT_MONTH;
                    break;
                case 4:
                    $amount=BaseController::KYA_ACADEMIC_AMOUNT_TRIMESTER;
                    break;
                case 5:
                    $amount=BaseController::KYA_ACADEMIC_AMOUNT_SEMESTER;
                    break;
                case 6:
                    $amount=BaseController::KYA_ACADEMIC_AMOUNT_ANNUAL;
                    break;
            }
        }

        if(intval($licence_type)==3){
            //students
            switch (intval($amount_category)){
                case 1:
                    $amount=BaseController::KYA_STUDENT_AMOUNT_DAY;
                    break;
                case 2:
                    $amount=BaseController::KYA_STUDENT_AMOUNT_WEEK;
                    break;
                case 3:
                    $amount=BaseController::KYA_STUDENT_AMOUNT_MONTH;
                    break;
            }
        }

        return $amount;
    }

    public function getDelay($amount_category){
        $delay=0;

        switch (intval($amount_category)){
            case 1:
                $delay=1;
                break;
            case 2:
                $delay=7;
                break;
            case 3:
                $delay=30;
                break;
            case 4:
                $delay=90;
                break;
            case 5:
                $delay=180;
                break;
            case 6:
                $delay=360;
                break;
        }


        return $delay;
    }

    public function savePaygateTempClient($data){
        if(
            isset($data["first_name"]) && $data["first_name"]!=null &&
            isset($data["last_name"]) && $data["last_name"]!=null &&
            isset($data["address"]) && $data["address"]!=null &&
            isset($data["country_selected"]) && $data["country_selected"]!=null &&
            isset($data["city"]) && $data["city"]!=null &&
            isset($data["phone_number"]) && $data["phone_number"]!=null
        ){
            /*save client personal infos as temporary client*/

            $temp_client=new Client();

            $temp_client->setFirstName($data["first_name"]);
            $temp_client->setLastName($data["last_name"]);
            $temp_client->setAddress($data["address"]);
            $temp_client->setCountry($this->getCountry($data["country_selected"]));
            $temp_client->setCity($data["city"]);
            $temp_client->setPhoneNumber($data["phone_number"]);

            if(isset($data["email"]) && $data["email"]!=null){
                $temp_client->setEmail($data["email"]);
            }

            if(isset($data["job_title"]) && $data["job_title"]!=null){
                $temp_client->setJobTitle($data["job_title"]);
            }

            if(isset($data["organisation"]) && $data["organisation"]!=null){
                $temp_client->setOrganisation($data["organisation"]);
            }

            $temp_client->setUsername($data["phone_number"]);

            $temp_client->setStatus(0);
            $temp_client->setCategory(0);
            $temp_client->setCreatedAt(strtotime(date('Y-m-d H:i:s')));
            $temp_client->setUpdatedAt(new \DateTime());

            $em=$this->getDoctrine()->getManager();
            $em->persist($temp_client);
            $em->flush();

            $dat=[];
            $dat["status"]=true;
            $dat["clientId"]=$temp_client->getId();

            return $dat;
        }else{
            $dat=[];
            $dat["status"]=false;
            $dat["clientId"]=null;

            return $dat;
        }

    }


    public function savePaydunyaTempClient($data){
        if(
            isset($data["first_name"]) && $data["first_name"]!=null &&
            isset($data["last_name"]) && $data["last_name"]!=null &&
            isset($data["address"]) && $data["address"]!=null &&
            isset($data["country_selected"]) && $data["country_selected"]!=null &&
            isset($data["city"]) && $data["city"]!=null &&
            isset($data["email"]) && $data["email"]!=null
        ){
            /*save client personal infos as temporary client*/

            $temp_client=new Client();

            $temp_client->setFirstName($data["first_name"]);
            $temp_client->setLastName($data["last_name"]);
            $temp_client->setAddress($data["address"]);
            $temp_client->setCountry($this->getCountryName($data["country_selected"]));
            $temp_client->setCity($data["city"]);
            $temp_client->setEmail($data["email"]);
            $temp_client->setUsername($data["email"]);

            if(isset($data["phone_number"]) && $data["phone_number"]!=null){
                $temp_client->setPhoneNumber($data["phone_number"]);
            }

            if(isset($data["job_title"]) && $data["job_title"]!=null){
                $temp_client->setJobTitle($data["job_title"]);
            }

            if(isset($data["organisation"]) && $data["organisation"]!=null){
                $temp_client->setOrganisation($data["organisation"]);
            }

            $temp_client->setStatus(0);
            $temp_client->setCategory(1);
            $temp_client->setCreatedAt(strtotime(date('Y-m-d H:i:s')));
            $temp_client->setUpdatedAt(new \DateTime());

            $em=$this->getDoctrine()->getManager();
            $em->persist($temp_client);
            $em->flush();

            $dat=[];
            $dat["status"]=true;
            $dat["clientId"]=$temp_client->getId();

            return $dat;
        }
        $dat=[];
        $dat["status"]=false;
        $dat["clientId"]=null;

        return $dat;
    }

    public function initPayDunyaTransaction($client_id,$email,$amount,$type,$amount_category,$payment_channel='Carte Bancaire',$source=1){

        $transaction = new Transaction();
        $details = "Achat Clé d'activation de KYA-SolDesign à travers"." ".$payment_channel;
        /*source is (wari,card,mtn,etc..)
        *add +2 to source coz 1 and 2 are for flooz and tmoney
        */
        $source=$source+2;

        $transaction->setDetails($details);
        $transaction->setClientId($client_id);
        $transaction->setAmount(intval($amount));
        $transaction->setState(0);
        $transaction->setPaymentMode($source);
        $transaction->setProvider('PAYDUNYA');
        $transaction->setType($type);
        $transaction->setUsername($email);
        $transaction->setAmountCategory($amount_category);
        $transaction->setCreatedAt(strtotime(date('Y-m-d H:i:s')));
        $transaction->setUpdatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($transaction);
        $em->flush();

        return $transaction;
    }

    public function sendZedekaMessage($destination,$body="ok"){
        $host=BaseController::SMS_ZEDEKA_HOST;
        $ApiKey=$this->getParameter('sms_zedeka_api_key');
        $ClientId=$this->getParameter('sms_zedeka_client_id');

        $SenderId=BaseController::SMS_ZEDEKA_SENDER;
        $MobileNumber=$destination;

        $URL="https://".$host."/api/v2/SendSMS?ApiKey=".$ApiKey."&ClientId=".$ClientId."&SenderId=".$SenderId."&Message=".$body."&MobileNumbers=".$MobileNumber."";

        // $URL="https://api.smszedekaa.com/api/v2/SendSMS?ApiKey=ISnqx7tbigE7OQxnGnsBuY4xrZC3m2Uj7wRpbOuIjtk=&ClientId=54911dcd-e69c-4030-9328-4b848c64c4db&SenderId=KYA&Message=HELLO&MobileNumbers=22893643212";

        $guzzleClient = new \GuzzleHttp\Client();
        $response = $guzzleClient->request('GET', $URL);

        return $response;
    }

    public function sendLicenceCodeByEmail($destination,$body="ok"){
        $transport = \Swift_MailTransport::newInstance();

        //$path = __DIR__.'/../../web/assets/asset/img/mail-cover.jpg';

        $mailer = new \Swift_Mailer($transport);
        $now = (new \DateTime('now'))->format('d/m H:i');
        // Create a message
        $message = (new \Swift_Message('Votre Licence pour payement du '.$now));
        //$bannerImg = $message->embed(\Swift_Image::fromPath($path));
        $message
            //->setFrom(['noreplykabadelivery@kya-energy.com' => 'KYA-ENERGY-GROUP'])
            ->setFrom(['kya.energy2020@gmail.com' => 'KYA-ENERGY-GROUP'])
            ->setTo([
                $destination
            ])
            ->setBody(
                $this->renderView(
                    'TransactionApiBundle:Default:mail.html.twig',
                    array('code' => $body)
                )
            )
            ->setContentType('text/html');

        // Send the message
        $result = $mailer->send($message);

        return 0;
    }

    public function getCountryName($country_index){

        $client = new \GuzzleHttp\Client();

        $request=$client->get('https://restcountries-v1.p.rapidapi.com/all',[
            'headers'=>[
                'x-rapidapi-host' => 'restcountries-v1.p.rapidapi.com',
                'x-rapidapi-key' => 'f49f196328msh9e4e7174e7c19dbp115e1ajsn1bbc1d7ba6dd'
            ]
        ]);
        $res = $request->getBody()->getContents();

        $response=json_decode($res,true);

        $country='';

        $countryObject=$response[$country_index];

        if($countryObject){
            $country=$countryObject["name"];
        }


        return $country;
    }
}
