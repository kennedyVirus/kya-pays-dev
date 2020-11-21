<?php

namespace SysSecurityBundle\Controller;

use AppBundle\Controller\BaseController;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SysSecurityBundle\Entity\ClientLogin;
use SysSecurityBundle\Entity\LicenceKey;
use SysSecurityBundle\Entity\Verification;

class SecurityController extends BaseController
{




//    public function oldlogin(Request $request){
//        $json_data = $request->getContent();
//        $data = json_decode($json_data,true);
//
//        if(
//            isset($data["code"]) && $data["code"]!=null &&
//            isset($data["mac_address"]) && $data["mac_address"]!=null &&
//            isset($data["type"]) && $data["type"]!=null
//        ){
//            if(intval($data["type"])==1){
//                //enterprise
//                if(isset($data["email"]) && $data["email"]!=null){
//                    $check_code_sent=$this->VerificationRepo()->findOneBy([
//                        'code'=> $data["code"],
//                        'state'=>0
//                    ]);
//                    $client=$this->ClientRepo()->findOneBy([
//                        'email'=>$data['email']
//                    ]);
//                    if($check_code_sent ==null ){
//                        return new Response($this->serialize($this->errorResponseBlob('No pending Licence key found',304)));
//                    }
//
//                    if( $client==null){
//                        return new Response($this->serialize($this->errorResponseBlob('User not found',300)));
//                    }
//                    //licence key
//                    $licence_key=$this->LicenceKeyRepo()->find($check_code_sent->getLicenceKeyId());
//
//                    if($licence_key !=null){
//                        if($licence_key->getName()==$data["code"]){
//                            //check if not already used
//
//                            if($licence_key->getUsed()==1){
//                                return new Response($this->serialize($this->errorResponseBlob('License Key already used',302)));
//                            }else{
//                                //check with mac address if  already used with this pc
//
//                                $check_mac_address_already_used=$this->ClientLoginRepo()->findOneBy([
//                                    'macAddress'=>$data["mac_address"],
//                                    'licenceKeyId'=>$licence_key->getId(),
//                                ]);
//
//                                if($check_mac_address_already_used !=null){
//                                    return new Response($this->serialize($this->errorResponseBlob('License Key already used',302)));
//                                }else{
//                                    //save new login entry
//                                    $login=new ClientLogin();
//                                    $login->setMacAddress($data["mac_address"]);
//                                    $login->setLicenceKeyId($licence_key->getId());
//                                    if(isset($data["ip_address"]) && $data["ip_address"]!=null){
//                                        $login->setIpAddress($data["ip_address"]);
//                                    }
//                                    $login->setClientId($client->getId());
//                                    $login->setCreatedAt(strtotime(date('Y-m-d H:i:s')));
//                                    $login->setUpdatedAt(new \DateTime());
//                                    $em=$this->getDoctrine()->getManager();
//                                    $em->persist($login);
//
//                                    //set licence key to used
//                                    $licence_key->setUsed(1);
//                                    $licence_key->setUpdatedAt(new \DateTime());
//
//                                    //set verification to state 1 =>verified
//                                    $check_code_sent->setState(1);
//                                    $check_code_sent->setUpdatedAt(new \DateTime());
//
//                                    $em->flush();
//
//                                    $response=[];
//                                    $response["delay"]=$licence_key->getDelay();
//
//                                    return new Response($this->serialize($this->okResponseBlob($response)));
//                                }
//                            }
//                        }else{
//                            //wrong key
//                            return new Response($this->serialize($this->errorResponseBlob('Wrong Licence key',301)));
//                        }
//                    }else{
//                        return new Response($this->serialize($this->errorResponseBlob('Wrong Licence key',301)));
//                    }
//                }else{
//                    return new Response($this->serialize($this->errorResponseBlob('Invalid parameters',303)));
//                }
//            }else{
//                if(isset($data["phone_number"]) && $data["phone_number"]!=null){
//
//                    $check_code_sent=$this->VerificationRepo()->findOneBy([
//                        'code'=> $data["code"],
//                        'state'=>0
//                    ]);
//
//                    $client=$this->ClientRepo()->findOneBy([
//                        'phoneNumber'=>$data["phone_number"]
//                    ]);
//                    if($check_code_sent ==null ){
//                        return new Response($this->serialize($this->errorResponseBlob('No pending Licence key found',304)));
//                    }
//
//                    if($client==null){
//                        return new Response($this->serialize($this->errorResponseBlob('User not found',300)));
//                    }
//
//                    //licence key
//                    $licence_key=$this->LicenceKeyRepo()->find($check_code_sent->getLicenceKeyId());
//
//                    if($licence_key !=null){
//                        if($licence_key->getName()==$data["code"] ){
//
//                            if($licence_key->getType()!=intval($data["type"])){
//                                return new Response($this->serialize($this->errorResponseBlob('Wrong Licence key',301)));
//                            }
//
//                            //check if not already used
//
//                            if($licence_key->getUsed()==1){
//                                return new Response($this->serialize($this->errorResponseBlob('License Key already used',302)));
//                            }else{
//                                //check with mac address if  already used with this pc
//
//                                $check_mac_address_already_used=$this->ClientLoginRepo()->findOneBy([
//                                    'macAddress'=>$data["mac_address"],
//                                    'licenceKeyId'=>$licence_key->getId(),
//                                ]);
//
//                                if($check_mac_address_already_used !=null){
//                                    return new Response($this->serialize($this->errorResponseBlob('License Key already used',302)));
//                                }else{
//                                    //save new login entry
//                                    $login=new ClientLogin();
//                                    $login->setMacAddress($data["mac_address"]);
//                                    $login->setLicenceKeyId($licence_key->getId());
//                                    if(isset($data["ip_address"]) && $data["ip_address"]!=null){
//                                        $login->setIpAddress($data["ip_address"]);
//                                    }
//                                    $login->setClientId($client->getId());
//                                    $login->setCreatedAt(strtotime(date('Y-m-d H:i:s')));
//                                    $login->setUpdatedAt(new \DateTime());
//                                    $em=$this->getDoctrine()->getManager();
//                                    $em->persist($login);
//
//                                    //set licence key to used
//                                    $licence_key->setUsed(1);
//                                    $licence_key->setUpdatedAt(new \DateTime());
//
//                                    //set verification to state 1 =>verified
//                                    $check_code_sent->setState(1);
//                                    $check_code_sent->setUpdatedAt(new \DateTime());
//
//                                    $em->flush();
//
//                                    $response=[];
//                                    $response["delay"]=$licence_key->getDelay();
//                                    $response["type"]=$licence_key->getType();
//
//                                    return new Response($this->serialize($this->okResponseBlob($response)));
//                                }
//                            }
//                        }else{
//                            //wrong key
//                            return new Response($this->serialize($this->errorResponseBlob('Wrong Licence key',301)));
//                        }
//                    }else{
//                        return new Response($this->serialize($this->errorResponseBlob('Wrong Licence key',301)));
//                    }
//
//                }else{
//                    return new Response($this->serialize($this->errorResponseBlob('Invalid parameters',303)));
//                }
//            }
//        }
//        else{
//            return new Response($this->serialize($this->errorResponseBlob('Invalid parameters',303)));
//        }
//    }

    /**
     * @Route("/8004064b17546e4380ce83d1be75b50dkfj/api/kya/sol/design/login",schemes={"https"})
     */
    public function login(Request $request){
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);

        if(
            isset($data["code"]) && $data["code"]!=null &&
            isset($data["mac_address"]) && $data["mac_address"]!=null &&
            isset($data["type"]) && $data["type"]!=null
        ){
            //check if either email or phone number is set
            $no_parameter=false;
            $parameter_title='';
            $parameter='';
            if(isset($data["email"]) && $data["email"]!=null ){
                $no_parameter=true;
                $parameter_title='email';
                $parameter=$data["email"];
            }
            if(isset($data["phone_number"]) && $data["phone_number"]!=null ){
                $no_parameter=true;
                $parameter_title='phone_number';
                $parameter=$data["phone_number"];
            }

            if($no_parameter==false){
                return new Response($this->serialize($this->errorResponseBlob('Invalid parameters',303)));
            }
            else{
                $check_code_sent=$this->VerificationRepo()->findOneBy([
                    'code'=> $data["code"],
                    'state'=>0
                ]);
                $client=$this->ClientRepo()->findOneBy([
                    $parameter_title => $parameter
                ]);
                if($check_code_sent ==null ){
                    return new Response($this->serialize($this->errorResponseBlob('No pending Licence key found',304)));
                }
                /*check if client not null*/
                if($client==null){
                    return new Response($this->serialize($this->errorResponseBlob('User not found',300)));
                }
                //licence key
                $licence_key=$this->LicenceKeyRepo()->find($check_code_sent->getLicenceKeyId());

                if($licence_key !=null){
                    if($licence_key->getName()==$data["code"]){
                        //check if not already used

                        if($licence_key->getUsed()==1){
                            return new Response($this->serialize($this->errorResponseBlob('License Key already used',302)));
                        }else{
                            //check with mac address if  already used with this pc

                            $check_mac_address_already_used=$this->ClientLoginRepo()->findOneBy([
                                'macAddress'=>$data["mac_address"],
                                'licenceKeyId'=>$licence_key->getId(),
                            ]);

                            if($check_mac_address_already_used !=null){
                                return new Response($this->serialize($this->errorResponseBlob('License Key already used',302)));
                            }else{
                                //save new login entry
                                $login=new ClientLogin();
                                $login->setMacAddress($data["mac_address"]);
                                $login->setLicenceKeyId($licence_key->getId());
                                if(isset($data["ip_address"]) && $data["ip_address"]!=null){
                                    $login->setIpAddress($data["ip_address"]);
                                }
                                $login->setClientId($client->getId());
                                $login->setCreatedAt(strtotime(date('Y-m-d H:i:s')));
                                $login->setUpdatedAt(new \DateTime());
                                $em=$this->getDoctrine()->getManager();
                                $em->persist($login);

                                //set licence key to used
                                $licence_key->setUsed(1);
                                $licence_key->setUpdatedAt(new \DateTime());

                                //set verification to state 1 =>verified
                                $check_code_sent->setState(1);
                                $check_code_sent->setUpdatedAt(new \DateTime());

                                $em->flush();

                                $response=[];
                                $response["delay"]=$licence_key->getDelay();

                                return new Response($this->serialize($this->okResponseBlob($response)));
                            }
                        }
                    }else{
                        //wrong key
                        return new Response($this->serialize($this->errorResponseBlob('Wrong Licence key',301)));
                    }
                }else{
                    return new Response($this->serialize($this->errorResponseBlob('Wrong Licence key',301)));
                }
            }
        }
        else{
            return new Response($this->serialize($this->errorResponseBlob('Invalid parameters',303)));
        }
    }


    public function alogin(Request $request){
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);

        if(
            isset($data["code"]) && $data["code"]!=null &&
            isset($data["mac_address"]) && $data["mac_address"]!=null &&
            isset($data["type"]) && $data["type"]!=null
        ){
            if(intval($data["type"])==1){
                //enterprise
                if(isset($data["email"]) && $data["email"]!=null){
                    $check_code_sent=$this->VerificationRepo()->findOneBy([
                        'code'=> $data["code"],
                        'state'=>0
                    ]);
                    $client=$this->ClientRepo()->findOneBy([
                        'email'=>$data['email']
                    ]);
                    if($check_code_sent ==null ){
                        return new Response($this->serialize($this->errorResponseBlob('No pending Licence key found',304)));
                    }

                    if( $client==null){
                        $client_2=$this->ClientRepo()->findOneBy([
                            'phone_number'=>$data['phone_number']
                        ]);
                        if($client_2==null){
                            return new Response($this->serialize($this->errorResponseBlob('User not found',300)));

                        }
                    }
                    //licence key
                    $licence_key=$this->LicenceKeyRepo()->find($check_code_sent->getLicenceKeyId());

                    if($licence_key !=null){
                        if($licence_key->getName()==$data["code"]){
                            //check if not already used

                            if($licence_key->getUsed()==1){
                                return new Response($this->serialize($this->errorResponseBlob('License Key already used',302)));
                            }else{
                                //check with mac address if  already used with this pc

                                $check_mac_address_already_used=$this->ClientLoginRepo()->findOneBy([
                                    'macAddress'=>$data["mac_address"],
                                    'licenceKeyId'=>$licence_key->getId(),
                                ]);

                                if($check_mac_address_already_used !=null){
                                    return new Response($this->serialize($this->errorResponseBlob('License Key already used',302)));
                                }else{
                                    //save new login entry
                                    $login=new ClientLogin();
                                    $login->setMacAddress($data["mac_address"]);
                                    $login->setLicenceKeyId($licence_key->getId());
                                    if(isset($data["ip_address"]) && $data["ip_address"]!=null){
                                        $login->setIpAddress($data["ip_address"]);
                                    }
                                    $login->setClientId($client->getId());
                                    $login->setCreatedAt(strtotime(date('Y-m-d H:i:s')));
                                    $login->setUpdatedAt(new \DateTime());
                                    $em=$this->getDoctrine()->getManager();
                                    $em->persist($login);

                                    //set licence key to used
                                    $licence_key->setUsed(1);
                                    $licence_key->setUpdatedAt(new \DateTime());

                                    //set verification to state 1 =>verified
                                    $check_code_sent->setState(1);
                                    $check_code_sent->setUpdatedAt(new \DateTime());

                                    $em->flush();

                                    $response=[];
                                    $response["delay"]=$licence_key->getDelay();

                                    return new Response($this->serialize($this->okResponseBlob($response)));
                                }
                            }
                        }else{
                            //wrong key
                            return new Response($this->serialize($this->errorResponseBlob('Wrong Licence key',301)));
                        }
                    }else{
                        return new Response($this->serialize($this->errorResponseBlob('Wrong Licence key',301)));
                    }
                }else{
                    return new Response($this->serialize($this->errorResponseBlob('Invalid parameters',303)));
                }
            }else{
                if(isset($data["phone_number"]) && $data["phone_number"]!=null){

                    $check_code_sent=$this->VerificationRepo()->findOneBy([
                        'code'=> $data["code"],
                        'state'=>0
                    ]);

                    $client=$this->ClientRepo()->findOneBy([
                        'phoneNumber'=>$data["phone_number"]
                    ]);
                    if($check_code_sent ==null ){
                        return new Response($this->serialize($this->errorResponseBlob('No pending Licence key found',304)));
                    }

                    if($client==null){
                        return new Response($this->serialize($this->errorResponseBlob('User not found',300)));
                    }


                    //licence key
                    $licence_key=$this->LicenceKeyRepo()->find($check_code_sent->getLicenceKeyId());

                    if($licence_key !=null){
                        if($licence_key->getName()==$data["code"] ){

                            if($licence_key->getType()!=intval($data["type"])){
                                return new Response($this->serialize($this->errorResponseBlob('Wrong Licence key',301)));
                            }

                            //check if not already used

                            if($licence_key->getUsed()==1){
                                return new Response($this->serialize($this->errorResponseBlob('License Key already used',302)));
                            }else{
                                //check with mac address if  already used with this pc

                                $check_mac_address_already_used=$this->ClientLoginRepo()->findOneBy([
                                    'macAddress'=>$data["mac_address"],
                                    'licenceKeyId'=>$licence_key->getId(),
                                ]);

                                if($check_mac_address_already_used !=null){
                                    return new Response($this->serialize($this->errorResponseBlob('License Key already used',302)));
                                }else{
                                    //save new login entry
                                    $login=new ClientLogin();
                                    $login->setMacAddress($data["mac_address"]);
                                    $login->setLicenceKeyId($licence_key->getId());
                                    if(isset($data["ip_address"]) && $data["ip_address"]!=null){
                                        $login->setIpAddress($data["ip_address"]);
                                    }
                                    $login->setClientId($client->getId());
                                    $login->setCreatedAt(strtotime(date('Y-m-d H:i:s')));
                                    $login->setUpdatedAt(new \DateTime());
                                    $em=$this->getDoctrine()->getManager();
                                    $em->persist($login);

                                    //set licence key to used
                                    $licence_key->setUsed(1);
                                    $licence_key->setUpdatedAt(new \DateTime());

                                    //set verification to state 1 =>verified
                                    $check_code_sent->setState(1);
                                    $check_code_sent->setUpdatedAt(new \DateTime());

                                    $em->flush();

                                    $response=[];
                                    $response["delay"]=$licence_key->getDelay();
                                    $response["type"]=$licence_key->getType();

                                    return new Response($this->serialize($this->okResponseBlob($response)));
                                }
                            }
                        }else{
                            //wrong key
                            return new Response($this->serialize($this->errorResponseBlob('Wrong Licence key',301)));
                        }
                    }else{
                        return new Response($this->serialize($this->errorResponseBlob('Wrong Licence key',301)));
                    }

                }else{
                    return new Response($this->serialize($this->errorResponseBlob('Invalid parameters',303)));
                }
            }
        }
        else{
            return new Response($this->serialize($this->errorResponseBlob('Invalid parameters',303)));
        }
    }

    /**
     *@Route("/8004064b17546e4380ce83d1be75b50dkfj/api/kya/sol/design/unlock/key",schemes={"https"})
     */

    public function unlockLicenceKeyAction(Request $request){
        $json_data = $request->getContent();
        $data = json_decode($json_data,true);

        if(
            isset($data["code"]) && $data["code"]!=null &&
            isset($data["phone_number"]) && $data["phone_number"]!=null
        ){
            $verification=$this->VerificationRepo()->findOneBy([
                'phoneNumber'=>$data["phone_number"],
                'unlockCode'=>$data["code"]
            ]);

            if($verification == null){
                return new Response($this->serialize($this->errorResponseBlob('licence key id not found',-2)));
            }

            $licence_key=$this->LicenceKeyRepo()->find($verification->getLicenceKeyId());

            if($licence_key==null){
                return new Response($this->serialize($this->errorResponseBlob('licence key not found',-3)));
            }

            $key=$licence_key->getName();

            return new Response($this->serialize($this->okResponseBlob(['key'=>$key])));
        }else{
            return new Response($this->serialize($this->errorResponseBlob('parameter null')));

        }

    }


    /**
     *@Route("/8004064b17546e4380ce83d1be75b50dkfj/api/kya/sol/design/logins/stat/get",schemes={"https"})
     */

    public function getClientLoginsAction(Request $request){

        $json_data=$request->getContent();
        $data=json_decode($json_data,true);

        $start_date=strtotime(date('Y-m-d 00:00:01'));
        $end_date=strtotime(date('Y-m-d 23:59:59'));

        if(
            isset($data["start"]) && $data["start"] !=null &&
            isset($data["end"]) && $data["end"] !=null
        ){
            $start_date=strtotime($data["start"]);
            $end_date=strtotime($data["end"]);
        }

        $query = $this->getDoctrine()->getManager()
            ->createQuery('SELECT c FROM SysSecurityBundle:ClientLogin c WHERE 
        ( c.createdAt < :ends AND c.createdAt > :starts ) ')

            ->setParameter('ends', $end_date)
            ->setParameter('starts', $start_date)
        ;
        $logins = $query->execute();

        $login_array=[];
        $testing_login_array=[];

        if($logins !=null){
            foreach ($logins as $login){
                $logs=[];
                $logs["id"]=$login->getId();
                $logs["client"]=[];
                $client=$this->ClientRepo()->find($login->getClientId());
                if($client !=null){
                    $logs["client"]=$this->clientToArray($client);
                }
                $logs["mac_address"]=$login->getMacAddress();
                $logs["ip_address"]=$login->getIpAddress();


                $logs["created_at"]=date('d-m-Y H:i',$login->getCreatedAt());


                    //get licence code

                    $licence_key=$this->LicenceKeyRepo()->find($login->getLicenceKeyId());

                    if($licence_key !=null){
                        $logs["key"]=$licence_key->getName();
                        $logs["code_sms"]=$licence_key->getCode();
                        $logs["key_used"]=$licence_key->getUsed();
                    }

                if($this->checkIfClientLoginNotATest($client,$login->getCreatedAt())){
                    array_push($login_array,$logs);
                }else{
                    array_push($testing_login_array,$logs);
                }

            }
        }
        return new Response($this->serialize($this->okResponseBlob(['logins'=>$login_array,'testing_logins'=>$testing_login_array])));
    }

    /**
     *@Route("/8004064b17546e4380ce83d1be75b50dkfj/api/kya/sol/design/transaction/stat/get",schemes={"https"})
     */

    public function getTransactionsAction(Request $request){

        $json_data=$request->getContent();
        $data=json_decode($json_data,true);

        $start_date=strtotime(date('Y-m-d 00:00:01'));
        $end_date=strtotime(date('Y-m-d 23:59:59'));

        if(
            isset($data["start"]) && $data["start"] !=null &&
            isset($data["end"]) && $data["end"] !=null
        ){
            $start_date=strtotime($data["start"]);
            $end_date=strtotime($data["end"]);
        }

        $query = $this->getDoctrine()->getManager()
            ->createQuery('SELECT c FROM TransactionApiBundle:Transaction c WHERE 
        ( c.createdAt < :ends AND c.createdAt > :starts ) ')

            ->setParameter('ends', $end_date)
            ->setParameter('starts', $start_date)
        ;
        $transactions = $query->execute();

        $transaction_array=[];
        $testing_transaction_array=[];

        if($transactions !=null){
            foreach ($transactions as $transaction){
                $trans=[];
                $trans["id"]=$transaction->getId();
                $trans["client"]=[];
                $client=$this->ClientRepo()->find($transaction->getClientId());
                if($client !=null){
                    $trans["client"]=$this->clientToArray($client);
                }
                $trans["state"]=$transaction->getState();

                $trans["source"]=$this->getChannel($transaction->getPaymentMode())["channel_in_french"];
                $trans["provider"]=$transaction->getProvider();
                $trans["amount"]=$transaction->getAmount();
                $trans["type"]=$transaction->getType();
                $trans["username"]=$transaction->getUsername();
                $trans["details"]=$transaction->getDetails();
                $trans["created_at"]=date('d-m-Y H:i',$transaction->getCreatedAt());

                $trans["key"]='';
                $trans["code_sms"]='';

                //get licence code

                $licence_key=$this->LicenceKeyRepo()->findOneBy([
                    'transactionId'=>$transaction->getId()
                ]);

                if($licence_key !=null){
                    $trans["key"]=$licence_key->getName();
                    $trans["code_sms"]=$licence_key->getCode();
                    $trans["key_used"]=$licence_key->getUsed();
                }

                if($this->checkIfTransactionNotATest($client,$transaction->getAmount(),$transaction->getCreatedAt())){
                    array_push($transaction_array,$trans);
                }else{
                    array_push($testing_transaction_array,$trans);
                }

            }
        }
        //return new Response($this->serialize($this->okResponseBlob(['transactions'=>$transaction_array,'testing_transactions'=>$testing_transaction_array])));
        return new Response($this->serialize($this->okResponseBlob(['transactions'=>$transaction_array])));
    }


    /**
     * @Route("/8004064b17546e4380ce83d1be75b50dkfj/api/generate/code")
     */

    public function generateCoddAction(Request $request){

        $json_data = $request->getContent();
        $data = json_decode($json_data,true);

        /*
         * tx_reference
         * payment_reference
         * amount
         * datetime
         */
//        $data["payment_reference"]="O28686712-194956";
//        $data["identifier"]="197";
//        $data["payment_method"]="T-Money";
//        $data["tx_reference"]=194956;
//        $data["datetime"]="2020-11-18T14:44:25.000Z";


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

            $code_to_unlock_licence_key=$this->generateRandomNumberBasedOnTimestamp(6);


            $key=new LicenceKey();
            $key->setName($licence_key);
            $key->setCode($code_to_unlock_licence_key);
            $key->setType($transaction->getType());
            $key->setTransactionId($transaction->getId());
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
            $verification->setUnlockCode($code_to_unlock_licence_key);
            $verification->setLicenceKeyId($key->getId());
            $verification->setTransactionCode("".$data["tx_reference"].$this->generateRandomNumber(4));
            $verification->setCreatedAt(strtotime(date('Y-m-d H:i:s')));
            $verification->setUpdatedAt(new \DateTime());

            $em->persist($verification);
            $em->flush();

            //send licence key unlock code instead .user will enter on the page to show the licence key

            // $licence_key_to_send= "<%23>%20CLE%20ACTIVATION%20KYA%20SOL%20DESIGN%20: " .$licence_key;
            $unlock_code_to_send= "Veuillez+entrer+ce+code+d%27activation+sur+le+site+web+pour+d%C3%A9bloquer+votre+licence+d%27activation+KYA-SolDesign: " .$code_to_unlock_licence_key;

            $res=$this->sendZedekaMessage("228".$transaction->getUsername(),$unlock_code_to_send);


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
}
