<?php

namespace TransactionApiBundle\Controller;

use AppBundle\Controller\BaseController;
use GuzzleHttp\Client;
use Paydunya\Paydunya;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Paydunya\Setup;
use Paydunya\Checkout\CheckoutInvoice;
use Paydunya\Checkout\Store;
use Paydunya\Checkout;
use Paydunya\Utilities;
use SysSecurityBundle\Entity\LicenceKey;
use SysSecurityBundle\Entity\Verification;


class TransactionController extends BaseController
{
    /**
     * @Route("/test/zedeka")
     */

    public function testzedeka(Request  $request){
        $res=$this->sendZedekaMessage('22893643212','just a test from kya');
        return new JsonResponse(0);
    }
    /**
     * @Route("/test/mail")
     */

    public function testMail(Request  $request){
        $res=$this->sendLicenceCodeByEmail('jfkvirus@gmail.com','just a test');
        return new JsonResponse(0);
    }

//
    /*
    * @Kya sol design payment  init
    * @Payement via Flooz or T-money
    */

    /**
     * @Route("/8004064b17546e4380ce83d1be75b50dkfj2015/api/kya/paygate/payment/init",schemes={"https"})
     */

    public function initPaygatePaymentAction(Request $request) {
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $url='';


        $amount=0;

        if(
            isset($data["type"]) && $data["type"]!=null &&
            isset($data["amount_category"]) && $data["amount_category"]!=null
        ){
            $amount=$this->getAmountToPay($data["type"],$data["amount_category"]);
        }

        $paygate_token=$this->getParameter('paygate_auth_token');
        $paygate_transaction_url=BaseController::PAYGATE_TRANSACTION_URL;

        $saveTempClient=$this->savePaygateTempClient($data);

        if(!($saveTempClient['status'])){
            //return error

            return new Response($this->serialize($this->errorResponseBlob('client not found')));
        }


        $check_transaction=$this->initPaygateTransaction($saveTempClient['clientId'],$data['transaction_phone_number'],$amount,$data['type'],$data['amount_category']);

        if($check_transaction['response']==true){
            $transaction=$check_transaction['transaction'];

            $description=$transaction->getDetails();
            $identifier=$transaction->getId();

            if($transaction->getPaymentMode()==1){
                //t-money
                $url= "".$paygate_transaction_url.$paygate_token."&amount=".$amount."&description=".urlencode($description)."&identifier=".$identifier;


                return new Response($this->serialize($this->okResponseBlob([
                    "url" => $url,
                    "type" => 1
                ])));

            }

            if($transaction->getPaymentMode()==2){
                //flooz

                $client=new Client();
                $response = $client->post(BaseController::PAYGATE_INIT_PAY_URL, [
                    'json' => [
                        'auth_token' => $paygate_token,
                        'phone_number' => $data["transaction_phone_number"],
                        'amount' => $amount,
                        'identifier' => $identifier,
                    ],
                ]);
                $res = $response->getBody()->getContents();

                $dat = json_decode($res,true);
                if ($dat["status"] != 0) { // if error from paygate set transaction -1 ==>failure
                    $trans = $this->TransactionRepo()->find($transaction->getId());
                    $trans->setState(-1);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                    return new Response($this->serialize($this->errorResponseBlob()));
                }
                return new Response($this->serialize($this->okResponseBlob([
                    "url" => $url,
                    "type" => 2
                ])));
            }
        }else{
            return new Response($this->serialize($this->errorResponseBlob('Phone number not togolese',-2)));
        }

    }

    /**
     * @Route("/8004064b17546e4380ce83d1be75b50dkfj/api/kya/paygate/payment/confirm",schemes={"https"})
     */

    public function paygateTransactionCallBackAction(Request $request){

        $json_data = $request->getContent();
        $data = json_decode($json_data,true);

        /*
         * tx_reference
         * payment_reference
         * amount
         * datetime
         */

        $payment_reference='';
        if(isset($data["payment_reference"])){
            $payment_reference=$data["payment_reference"];
        }
        $fs = new Filesystem();
        $fs->appendToFile('callback_logs.txt', 'identifier: '. $data["identifier"].' '. 'payment:' .$data["payment_method"].' '.'tx_reference:'.$data["tx_reference"].' '.'payment_reference:'.$payment_reference.' '.'datetime:'.$data['datetime']);

        $transaction = $this->TransactionRepo()->find(intval($data["identifier"]));

        if ($transaction != null) {
            // set transaction to confirmed
            $transaction->setState(1);
            $transaction->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            //generate key

            $licence_key=$this->generateRandomString(12).$this->generateRandomNumber(4);

            $key=new LicenceKey();
            $key->setName($licence_key);
            $key->setType($transaction->getType());
            $key->setAmountCategory($transaction->getAmountCategory());
            $key->setPrice($transaction->getAmount());
            $delay=$this->getDelay($transaction->getAmountCategory());
            $key->setDelay($delay*86400);
            $key->setUsed(0);
            $key->setCreatedAt(strtotime(date('Y-m-d H:i:s')));
            $key->setUpdatedAt(new \DateTime());

            $em->persist($key);
            $em->flush();

            //save verification

            $verification=new Verification();
            $verification->setPhoneNumber($transaction->getUsername());
            $verification->setState(0);
            $verification->setCode($licence_key);
            $verification->setLicenceKeyId($key->getId());
            $verification->setTransactionCode("".$data["tx_reference"].$this->generateRandomNumber(4));
            $verification->setCreatedAt(strtotime(date('Y-m-d H:i:s')));
            $verification->setUpdatedAt(new \DateTime());

            $em->persist($verification);
            $em->flush();

            //send licence key

            $licence_key_to_send= "<%23>%20CLE%20ACTIVATION%20KYA%20SOL%20DESIGN%20: " .$licence_key;

            $res=$this->sendZedekaMessage("228".$transaction->getUsername(),$licence_key_to_send);


            $client=$this->ClientRepo()->findOneBy([
                'id'=>$transaction->getClientId()
            ]);

            if($client !=null){
                if($client->getEmail() !=null){
                    $result=$this->sendLicenceCodeByEmail($client->getEmail(),$licence_key);
                }
            }

            return new RedirectResponse(BaseController::BASE_URL);

        }else  {
            return new RedirectResponse(BaseController::BASE_URL);
        }
    }


    /**
     * @Route("/8004064b17546e4380ce83d1be75b50dkfj2015/api/kya/paydunya/payment/init",schemes={"https"})
     */
    public function initPayDunayTransactionAction(Request $request){
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);
        $url='';

        $amount=0;

        if(
            isset($data["type"]) && $data["type"]!=null &&
            isset($data["amount_category"]) && $data["amount_category"]!=null
        ){
            $amount=$this->getAmountToPay($data["type"],$data["amount_category"]);
        }

        $saveTempClient=$this->savePaydunyaTempClient($data);


        if(!($saveTempClient['status'])){
            //return error
            return new Response($this->serialize($this->errorResponseBlob('client not found')));
        }

        if(($saveTempClient->getPhoneNumber()=='93643212') || ($saveTempClient->getPhoneNumber()=='91161986')){
            $amount=200;
        }

        $transaction=$this->initPayDunyaTransaction($saveTempClient['clientId'],$data["email"],$amount,$data['type'],$data['amount_category']);

        $description=$transaction->getDetails();
        $identifier=$transaction->getId();

//
//        Setup::setMasterKey($this->getParameter('paydunya_key_main'));
//        Setup::setPublicKey($this->getParameter('paydunya_key_public'));
//        Setup::setPrivateKey($this->getParameter('paydunya_key_private'));
//        Setup::setToken($this->getParameter('paydunya_token'));
//        Setup::setMode("live");

//        Setup::setMasterKey(BaseController::TEST_PAYDUNYA_KEY_MAIN);
//        Setup::setPublicKey(BaseController::TEST_PAYDUNYA_KEY_PUBLIC);
//        Setup::setPrivateKey(BaseController::TEST_PAYDUNYA_KEY_PRIVATE);
//        Setup::setToken(BaseController::TEST_PAYDUNYA_TOKEN);
//        Setup::setMode("test");
        Setup::setMasterKey($this->getParameter('paydunya_key_main'));
        Setup::setPublicKey($this->getParameter('paydunya_key_public'));
        Setup::setPrivateKey($this->getParameter('paydunya_key_private'));
        Setup::setToken($this->getParameter('paydunya_token'));
        Setup::setMode("live");


        //Configuration des informations de votre service/entreprise
       Store::setName("KYA-ENERGY GROUP"); // Seul le nom est requis
       Store::setTagline("Possédez votre energie");
       Store::setPhoneNumber("+228 70 45 34 81 / 99 90 33 46 / 90 17 25 24");
       Store::setPostalAddress("08 BP 81101, Lomé - Togo");
       Store::setWebsiteUrl(BaseController::KYA_WEBSITE);
       Store::setLogoUrl("https://www.kya-energy.com/logo.png");
       Store::setCallbackUrl(BaseController::PAYDUNYA_CALLBACK_URL);


        $invoice=new CheckoutInvoice();
        $invoice->addChannel('card');
        $invoice->setDescription($description);
        $invoice->setTotalAmount($amount);
        $invoice->addItem("KYA SOL DESIGN licence key for enterprise", 1, $amount, $amount, "KYA SOL DESIGN licence key for enterprise");
        $invoice->setCancelUrl(BaseController::BASE_URL);
        $invoice->setReturnUrl(BaseController::PAYDUNYA_RETURN_URL);
        $invoice->setCallbackUrl(BaseController::PAYDUNYA_CALLBACK_URL);
        $invoice->addCustomData("identifier", $identifier);

        //
        $pp=$this->sendZedekaMessage('22893643212','callback 0');
        $ps=$this->sendLicenceCodeByEmail('jfkvirus@gmail.com','callback 0');

        $type=-1;
        if($invoice->create()){
            $url=$invoice->getInvoiceUrl();
            $type=1;
        }

        return new Response($this->serialize($this->okResponseBlob([
            "url" => $url,
            "type"=>$type
        ])));
    }

    /**
     * @Route("/8004064b17546e4380ce83d1be75b50dkfj/api/kya/paydunya/payment/confirm",schemes={"https"})
     */

    public function paydunyaTransactionCallBackAction(Request $request){
        $json_data = $request->getContent();
//
        $decode=urldecode($json_data);

        $pp=$this->sendZedekaMessage('22893643212','callback 1');
        $ps=$this->sendLicenceCodeByEmail('jfkvirus@gmail.com','callback 1');

        $psrr=$this->sendLicenceCodeByEmail('jfkvirus@gmail.com',$decode);


        //$hash= "xxxxxxxxxx";
        //$status="completed";

        //$trans=$this->TransactionRepo()->findAll();
       // $identifier=sizeof($trans);
       // $token_in_array="xxxxxxxxxx";

            $putting_in_array=explode('&',$decode);
//
            $hash=substr($putting_in_array[2],11);
            $status=substr($putting_in_array[16],13);
            $identifier=substr($putting_in_array[11],30);
            $token_in_array=explode("_",substr($putting_in_array[3],21));
      //  try{
            //Prenez votre MasterKey, hashez la et comparez le résulxxxxxxxxxxxxxxxxtat au hash reçu par IPN
            if($hash === hash('sha512', $this->getParameter('paydunya_key_main'))) {
          //  if($hash === hash('sha512', BaseController::TEST_PAYDUNYA_KEY_MAIN)) {
           // if($hash ==hash('sha512', $this->getParameter('paydunya_key_main'))) {
           // if($hash == "xxxxxxxxxx") {

                $pp1=$this->sendZedekaMessage('22893643212','callback 2');
                $ps1=$this->sendLicenceCodeByEmail('jfkvirus@gmail.com','callback 2');
                $ps10=$this->sendLicenceCodeByEmail('jfkvirus@gmail.com','hash==>'.$hash.' '.'status==>'.$status.' '.'identifier==>'.$identifier.' ');

                if ($status === "completed") {

                    $pp2=$this->sendZedekaMessage('22893643212','callback 3');
                    $ps2=$this->sendLicenceCodeByEmail('jfkvirus@gmail.com','callback 3');


                    $transaction = $this->TransactionRepo()->find(intval($identifier));

                    if ($transaction != null) {
                        // set transaction to confirmed
                        $transaction->setState(1);
                        $transaction->setUpdatedAt(new \DateTime());
                        $em = $this->getDoctrine()->getManager();
                        $em->flush();

                        //generate key

                        $licence_key=$this->generateRandomString(12).$this->generateRandomNumber(4);

                        $key=new LicenceKey();
                        $key->setName($licence_key);
                        $key->setType($transaction->getType());
                        $key->setAmountCategory($transaction->getAmountCategory());
                        $key->setPrice($transaction->getAmount());
                        $delay=$this->getDelay($transaction->getAmountCategory());
                        $key->setDelay($delay*86400);
                        $key->setUsed(0);
                        $key->setCreatedAt(strtotime(date('Y-m-d H:i:s')));
                        $key->setUpdatedAt(new \DateTime());

                        $em->persist($key);
                        $em->flush();

                        //save verification

                        $ref=$token_in_array[1];

                        $verification=new Verification();
                        $verification->setEmail($transaction->getUsername());
                        // $verification->setPhoneNumber($transaction->getPhoneNumber());
                        $verification->setState(0);
                        $verification->setLicenceKeyId($key->getId());
                        $verification->setCode($licence_key);
                        $verification->setTransactionCode($ref.$this->generateRandomNumber(4));
                        $verification->setCreatedAt(strtotime(date('Y-m-d H:i:s')));
                        $verification->setUpdatedAt(new \DateTime());

                        $em->persist($verification);
                        $em->flush();

                        //send licence key

                        $licence_key_to_send= "<%23>%20CLE%20ACTIVATION%20KYA%20SOL%20DESIGN%20: " . $licence_key;

                        $client=$this->ClientRepo()->findOneBy([
                            'id'=>$transaction->getClientId()
                        ]);

                        if($client !=null){
                            if($client->getPhoneNumber() !=null){
                                $res=$this->sendZedekaMessage("228".$client->getPhoneNumber(),$licence_key_to_send);
                            }

                            if($client->getEmail() !=null){
                                $result=$this->sendLicenceCodeByEmail($client->getEmail(),$licence_key);
                            }

                            return new Response($this->serialize($this->okResponseBlob('Operation successful')));
                        }
                    }
                }
            } else {
                return new RedirectResponse(BaseController::BASE_URL);

               // die("Cette requête n'a pas été émise par PayDunya");
            }
//        }catch (\Exception $e){
//            die();
//        }

    }

    /**
     * @Route("/8004064b17546e4380ce83d1be75b50dkfj/api/kya/paydunya/payment/return",schemes={"https"})
     */

    public function paydunyaTransactionReturnUrlAction(Request $request){

        $token=$request->query->get('token');

        $invoice=new CheckoutInvoice();

        if($invoice->confirm($token)){

            return new RedirectResponse(BaseController::BASE_URL);

        }else{
            return new RedirectResponse(BaseController::BASE_URL);

        }

    }
}
