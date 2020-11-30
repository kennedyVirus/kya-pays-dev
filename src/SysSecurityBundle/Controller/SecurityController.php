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

        $login_array=[];
        $testing_login_array=[];

        if(
            isset($data["start"]) && $data["start"] !=null &&
            isset($data["end"]) && $data["end"] !=null
        ){
            if($data["start"]=='' || $data["end"]==''){
                return new Response($this->serialize($this->errorResponseBlob('start or end date null',-1)));
            }

            $start_date=strtotime($data["start"].' '.'00:00:01');
            $end_date=strtotime($data["end"].' '.'23:59:59');

            if(intval($start_date) > intval($end_date)){
                return new Response($this->serialize($this->errorResponseBlob('start date greater than end date',-2)));
            }
        }

        //check if security key is sent and correct

        if(isset($data["server_key"]) && $data["server_key"] !=null){

            if(md5('kyapay')==$data["server_key"]){
                $query = $this->getDoctrine()->getManager()
                    ->createQuery('SELECT c FROM SysSecurityBundle:ClientLogin c WHERE 
        ( c.createdAt < :ends AND c.createdAt > :starts ) ')

                    ->setParameter('ends', $end_date)
                    ->setParameter('starts', $start_date)
                ;
                $logins = $query->execute();

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

                        $logs["key"]='-';
                        $logs["code_sms"]='-';
                        $logs["key_used"]='-';

                        //get licence code

                        $licence_key=$this->LicenceKeyRepo()->find($login->getLicenceKeyId());

                        if($licence_key !=null){
                            $logs["key"]=$licence_key->getName();
                            $logs["code_sms"]=$licence_key->getCode();
                            $logs["key_used"]=$licence_key->getUsed();
                        }

                        if($this->checkIfClientLoginNotATest($client,$login->getCreatedAt())){
                            array_unshift($login_array,$logs);
                        }else{
                            array_unshift($testing_login_array,$logs);
                        }
                    }
                }
            }
        }

        $data=[];
        $data["logins"]=$login_array;
        $data["testing_logins"]=$testing_login_array;
        $data["logins_nb"]=count($login_array);
        $data["testing_logins_nb"]=count($testing_login_array);

        return new Response($this->serialize($this->okResponseBlob($data)));
    }

    /**
     *@Route("/8004064b17546e4380ce83d1be75b50dkfj/api/kya/sol/design/transaction/stat/get",schemes={"https"})
     */

    public function getTransactionsAction(Request $request){

        $json_data=$request->getContent();
        $data=json_decode($json_data,true);

        $start_date=strtotime(date('Y-m-d 00:00:01'));
        $end_date=strtotime(date('Y-m-d 23:59:59'));

        $transaction_array=[];
        $testing_transaction_array=[];

        $total=0;

        $international_card_nb=0;
        $international_card=0;
        $international_wari_nb=0;
        $international_wari=0;

        $togo_flooz_nb=0;
        $togo_flooz=0;
        $togo_tmoney_nb=0;
        $togo_tmoney=0;

        $benin_mtn_nb=0;
        $benin_mtn=0;
        $benin_moov_nb=0;
        $benin_moov=0;

        $ci_mtn_nb=0;
        $ci_mtn=0;
        $ci_orange_nb=0;
        $ci_orange=0;

        $senegal_orange_nb=0;
        $senegal_orange=0;
        $senegal_free_nb=0;
        $senegal_free=0;
        $senegal_apicash_nb=0;
        $senegal_apicash=0;
        $senegal_wizall_nb=0;
        $senegal_wizall=0;

        if(
            isset($data["start"]) && $data["start"] !=null &&
            isset($data["end"]) && $data["end"] !=null
        ){
            if($data["start"]=='' || $data["end"]==''){
                return new Response($this->serialize($this->errorResponseBlob('start or end date null',-1)));
            }

            $start_date=strtotime($data["start"].' '.'00:00:01');
            $end_date=strtotime($data["end"].' '.'23:59:59');

            if(intval($start_date) > intval($end_date)){
                return new Response($this->serialize($this->errorResponseBlob('start date greater than end date',-2)));
            }
        }

        //check if security key is sent and correct

        if(isset($data["server_key"]) && $data["server_key"] !=null){

            if(md5('kyapay')==$data["server_key"]){

                $query = $this->getDoctrine()->getManager()
                    ->createQuery('SELECT c FROM TransactionApiBundle:Transaction c WHERE 
        ( c.createdAt < :ends AND c.createdAt > :starts ) ')

                    ->setParameter('ends', $end_date)
                    ->setParameter('starts', $start_date)
                ;
                $transactions = $query->execute();

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
                        $trans["delay"]=$this->getDelay($transaction->getAmountCategory());
                        $trans["details"]=$transaction->getDetails();
                        $trans["created_at"]=date('d-m-Y H:i',$transaction->getCreatedAt());

                        $trans["key"]='-';
                        $trans["code_sms"]='-';
                        $trans["key_used"]='-';

                        //get licence code

                        $licence_key=$this->LicenceKeyRepo()->findOneBy([
                            'transactionId'=>$transaction->getId()
                        ]);

                        if($licence_key !=null){
                            $trans["key"]=$licence_key->getName();
                            $trans["code_sms"]=$licence_key->getCode();
                            $trans["key_used"]=$licence_key->getUsed();
                        }

                        if($transaction->getState()==1){
                            $total+=intval($transaction->getAmount());

                            switch (intval($transaction->getPaymentMode())){
                                case 1:
                                    $togo_tmoney+=intval($transaction->getAmount());
                                    $togo_tmoney_nb++;
                                    break;

                                case 2:
                                    $togo_flooz+=intval($transaction->getAmount());
                                    $togo_flooz_nb++;
                                    break;

                                case 3:
                                    $international_card+=intval($transaction->getAmount());
                                    $international_card_nb++;
                                    break;

                                case 4:
                                    $international_wari+=intval($transaction->getAmount());
                                    $international_wari_nb++;
                                    break;

                                case 5:
                                    $benin_mtn+=intval($transaction->getAmount());
                                    $benin_mtn_nb++;
                                    break;

                                case 6:
                                    $benin_moov+=intval($transaction->getAmount());
                                    $benin_moov_nb++;
                                    break;

                                case 7:
                                    $ci_mtn+=intval($transaction->getAmount());
                                    $ci_mtn_nb++;
                                    break;

                                case 8:
                                    $ci_orange+=intval($transaction->getAmount());
                                    $ci_orange_nb++;
                                    break;

                                case 9:
                                    $senegal_orange+=intval($transaction->getAmount());
                                    $senegal_orange_nb++;
                                    break;

                                case 10:
                                    $senegal_free+=intval($transaction->getAmount());
                                    $senegal_free_nb++;
                                    break;

                                case 11:
                                    $senegal_apicash+=intval($transaction->getAmount());
                                    $senegal_apicash_nb++;
                                    break;

                                case 12:
                                    $senegal_wizall+=intval($transaction->getAmount());
                                    $senegal_wizall_nb++;
                                    break;
                            }
                        }
                        if($this->checkIfTransactionNotATest($client,$transaction->getAmount(),$transaction->getCreatedAt())){
                            array_unshift($transaction_array,$trans);
                        }else{
                            array_unshift($testing_transaction_array,$trans);
                        }
                    }
                }
            }
        }

        $data=[];
        $data["country_stats"]=[];
        $data["country_stats"]["international"]=[];
        $data["country_stats"]["international"]["card"]=[];
        $data["country_stats"]["international"]["card"]["total"]=$international_card;
        $data["country_stats"]["international"]["card"]["nb"]=$international_card_nb;
        $data["country_stats"]["international"]["wari"]=[];
        $data["country_stats"]["international"]["wari"]["total"]=$international_wari;
        $data["country_stats"]["international"]["wari"]["nb"]=$international_wari_nb;

        $data["country_stats"]["togo"]=[];
        $data["country_stats"]["togo"]["tmoney"]=[];
        $data["country_stats"]["togo"]["tmoney"]["total"]=$togo_tmoney;
        $data["country_stats"]["togo"]["tmoney"]["nb"]=$togo_tmoney_nb;
        $data["country_stats"]["togo"]["flooz"]=[];
        $data["country_stats"]["togo"]["flooz"]["total"]=$togo_flooz;
        $data["country_stats"]["togo"]["flooz"]["nb"]=$togo_flooz_nb;

        $data["country_stats"]["benin"]=[];
        $data["country_stats"]["benin"]["mtn"]=[];
        $data["country_stats"]["benin"]["mtn"]["total"]=$benin_mtn;
        $data["country_stats"]["benin"]["mtn"]["nb"]=$benin_mtn_nb;
        $data["country_stats"]["benin"]["moov"]=[];
        $data["country_stats"]["benin"]["moov"]["total"]=$benin_moov;
        $data["country_stats"]["benin"]["moov"]["nb"]=$benin_moov_nb;

        $data["country_stats"]["ci"]=[];
        $data["country_stats"]["ci"]["mtn"]=[];
        $data["country_stats"]["ci"]["mtn"]["total"]=$ci_mtn;
        $data["country_stats"]["ci"]["mtn"]["nb"]=$ci_mtn_nb;
        $data["country_stats"]["ci"]["orange"]=[];
        $data["country_stats"]["ci"]["orange"]["total"]=$ci_orange;
        $data["country_stats"]["ci"]["orange"]["nb"]=$ci_orange_nb;

        $data["country_stats"]["senegal"]=[];
        $data["country_stats"]["senegal"]["free"]=[];
        $data["country_stats"]["senegal"]["free"]["total"]=$senegal_free;
        $data["country_stats"]["senegal"]["free"]["nb"]=$senegal_free_nb;
        $data["country_stats"]["senegal"]["orange"]=[];
        $data["country_stats"]["senegal"]["orange"]["total"]=$senegal_orange;
        $data["country_stats"]["senegal"]["orange"]["nb"]=$senegal_orange_nb;
        $data["country_stats"]["senegal"]["apicash"]=[];
        $data["country_stats"]["senegal"]["apicash"]["total"]=$senegal_apicash;
        $data["country_stats"]["senegal"]["apicash"]["nb"]=$senegal_apicash_nb;
        $data["country_stats"]["senegal"]["wizall"]=[];
        $data["country_stats"]["senegal"]["wizall"]["total"]=$senegal_wizall;
        $data["country_stats"]["senegal"]["wizall"]["nb"]=$senegal_wizall_nb;

        $data["transactions"]=$transaction_array;
        $data["testing_transactions"]=$testing_transaction_array;

        $data["transactions_nb"]=count($transaction_array);
        $data["testing_transactions_nb"]=count($testing_transaction_array);

        $data["total"]=[];
        $data["total"]["total"]=$total;
        $data["total"]["international"]=$international_card+$international_wari;
        $data["total"]["togo"]=$togo_flooz+$togo_tmoney;
        $data["total"]["others"]=$benin_moov+$benin_mtn+$ci_mtn+$ci_orange+$senegal_wizall+$senegal_apicash+$senegal_orange+$senegal_free;

        return new Response($this->serialize($this->okResponseBlob($data)));
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

            $length=strlen($transaction->getUsername());
            if($length>7){
                $phone_number=substr($transaction->getUsername(),$length-8);

                if($this->checkIfPhoneNumberValid($phone_number)){
                    $res=$this->sendZedekaMessage("228".$phone_number,$unlock_code_to_send);
                }
            }

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
