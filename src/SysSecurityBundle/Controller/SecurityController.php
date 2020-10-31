<?php

namespace SysSecurityBundle\Controller;

use AppBundle\Controller\BaseController;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SysSecurityBundle\Entity\ClientLogin;

class SecurityController extends BaseController
{

    /**
     * @Route("/test/rest/country")
     */

    public function testRestCountry(){


$client = new Client();
//$request = new \GuzzleHttp\Psr7\Request();

//$request->setRequestUrl('https://restcountries-v1.p.rapidapi.com/all');
//$request->setRequestMethod('GET');
//$request->setHeaders(array(
//    'x-rapidapi-host' => 'restcountries-v1.p.rapidapi.com',
//    'x-rapidapi-key' => 'f49f196328msh9e4e7174e7c19dbp115e1ajsn1bbc1d7ba6dd'
//));

//$client->enqueue($request)->send();
//$response = $client->getResponse();

        $request=$client->get('https://restcountries-v1.p.rapidapi.com/all',[
            'headers'=>[
                'x-rapidapi-host' => 'restcountries-v1.p.rapidapi.com',
                'x-rapidapi-key' => 'f49f196328msh9e4e7174e7c19dbp115e1ajsn1bbc1d7ba6dd'
            ]
        ]);
        $res = $request->getBody()->getContents();

        $response=json_decode($res,true);

//        foreach ($response as $i=>$value){
//            if($value["name"]=="Ivory Coast")
//        }

        return new JsonResponse($response);
    }


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
     * @Route("/8004064b17546e4380ce83d1be75b50dkfj/api/kya/sol/design/login")
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
            }else{
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



}
