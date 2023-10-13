<?php

namespace App\Controller;

use Carbon\Carbon;
use App\Entity\Consomation;
use App\Entity\ConsomationPredit;
use App\Repository\ClientRepository;
use App\Repository\AppareilRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConsomationRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ConsomationPreditRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use GuzzleHttp\Client;

class ConsomationController extends AbstractController
{
    private $clientRepository;
    private $appareilRepository;
    private $consomationPreditRepository;
    private $consomationRepository;
    private $em;



    public function __construct(ClientRepository $clientRepository, AppareilRepository $appareilRepository,ConsomationPreditRepository $consomationPreditRepository,ConsomationRepository $consomationRepository ,  EntityManagerInterface $em)
    {
       $this->clientRepository= $clientRepository;
       $this->appareilRepository= $appareilRepository;
       $this->consomationPreditRepository=$consomationPreditRepository;
       $this->consomationRepository=$consomationRepository;
       $this->em=$em;
       
    }

    #[Route('/getPredictionToIA/{id}', name: 'app_get_prediction_to_IA')]
    public function getPredictionToIA($id): JsonResponse
    {

        // Remplacez l'URL de l'API Symfony par votre URL réelle
        $apiEndpoint = "https://uhhcqpx260.execute-api.us-east-1.amazonaws.com/xgbvolyvolt";

        // Données à envoyer à l'API
       /* $data = [
            ["quarter" => 1, "month" => 1, "year" => 2023, "day_of_year" => 9],
            ["quarter" => 1, "month" => 2, "year" => 2023, "day_of_year" => 44],
            ["quarter" => 4, "month" => 10, "year" => 2023, "day_of_year" => 2],
            ["quarter" => 4, "month" => 10, "year" => 2023, "day_of_year" => 9],
            ["quarter" => 4, "month" => 10, "year" => 2023, "day_of_year" => 16],
            ["quarter" => 4, "month" => 10, "year" => 2023, "day_of_year" => 23],
            ["quarter" => 4, "month" => 10, "year" => 2023, "day_of_year" => 30],
            ["quarter" => 4, "month" => 11, "year" => 2023, "day_of_year" => 6],
            ["quarter" => 4, "month" => 11, "year" => 2023, "day_of_year" => 11],
            ["quarter" => 4, "month" => 11, "year" => 2023, "day_of_year" => 18],
            ["quarter" => 4, "month" => 11, "year" => 2023, "day_of_year" => 23],
            ["quarter" => 4, "month" => 11, "year" => 2023, "day_of_year" => 30]
        ];*/

       /* $jsonData = '{"data": 
            [
                [1.0, 1.0, 2023,9], 
                [1,2,2023,44], 
                [4,10,2023,2], 
                [4,10,2023,9],
                [4, 10, 2023, 16],
                [4, 10, 2023, 23], 
                [4,10,2023, 30], 
                [4,11, 2023,6], 
                [4,11, 2023,11], 
                [4,11, 2023,18], 
                [4,11, 2023, 23], 
                [4,11, 2023, 30]]}'
                ;*/

        $jsonData = '{"data": 
            [
                [4,10,2023,2], 
                [4,10,2023,9],
                [4, 10, 2023, 16],
                [4, 10, 2023, 23], 
                [4,10,2023, 30], 
                [4,11, 2023,6], 
                [4,11, 2023,11], 
                [4,11, 2023,18], 
                [4,11, 2023, 23], 
                [4,11, 2023, 30]]}';

        $phpArray = json_decode($jsonData, true);

        // If you want to access the 'data' key specifically
        $dataArray = $phpArray['data'];

        //dump($dataArray);

        // Création du client Guzzle
        $client = new Client();

        // Envoi de la requête POST à l'API Symfony
        $response = $client->post($apiEndpoint, [
            'json' => ['data' => $dataArray]
        ]);

        // Récupération du contenu de la réponse
        $responseData = $response->getBody()->getContents();
        //ltrim($responseData, '[');

        if(!$responseData){
            return $this->json(['error'=>'pas de reponse venant du serveur de l,IA'],500);
        }

        //processing data
        $dataPredit = ltrim($responseData, '['); 
        $dataPredit= rtrim($dataPredit, ']');

        //dd($dataPredit);

        $arrayPredit= explode(',',$dataPredit);
        //dd($arrayPredit);
        $phpArray=$phpArray['data'];
        $dataConsoString = array();

        foreach ($arrayPredit as $key=>$predit){
            $consomationPredit= new ConsomationPredit();

            //START

            $year = $phpArray[$key][2];
            $month = $phpArray[$key][1];
            $day = $phpArray[$key][3];

            //dump($arrayPredit[$key]);
            //processing data
            if($key == 0){
                
            $dataConsoString[$key]=substr($predit,1,7);
            } else {
                
            $dataConsoString[$key]=substr($predit,2,8);
            }
            //$dataConsoString=rtrim('"',$dataConsoString);

            //dump($dataConsoString);

            $consoPreditString = (float) $dataConsoString[$key];

            //dd($consoPreditString);

            $consomationPredit->setConsomation($consoPreditString);

            //$startWeek= $now->startOfWeek();
            //$endWeek=$now->endOfWeek();
            //$date=Carbon::createFromDate(2023,9,30,'Europe/Berlin');
            $date=Carbon::create($year, $month, $day, 0, 0, 0);
            
            $consomationPredit->setStartWeek($date->startOfWeek());
            $consomationPredit->setConsomationReel(false);

            //tsy maints atao anatin io fa misy conflit start sy ny end
            $startWeek=$date->startOfWeek()->toDateTimeString();
            
            

            $consomationPredit->setStartWeek(new \DateTime($startWeek));
            $consomationPredit->setEndWeek($date->endOfWeek());



            $client= $this->clientRepository->findOneById($id);

            if(!$client){
                return $this->json(['error' => "couldn't find the client by id = ".$id ],403);
            }

            //dd($client);
            $consomationPredit->setClient($client);

            //dd($date->endOfWeek(Carbon::SUNDAY));
            //dd($consomationPredit);

            //dd($consomationPredit);
            $this->em->getConnection()->beginTransaction();
            try {

                $this->em->persist($consomationPredit);
                $this->em->flush();
                $this->em->commit();

            } catch (\Exception $e) {

                $this->em->rollback();
                throw $e;
            }

        }
        //dd($arrayPredit);
        //dd($dataConsoString);
        
        // Vous pouvez retourner la réponse si nécessaire
        return new JsonResponse(['response' => 'ok']);

    }

    #[Route('/consomation', name: 'app_consomation', methods:'POST')]
    public function consomation(Request $request): JsonResponse
    {
        //consomation clientId appareilId

        $consomation= new Consomation();

        $consomation->setConsomation($request->request->get('consomation'));
        $consomation->setDate(new \DateTime());

       // $client = $this->clientRepository->findOneBy
        $client= $this->clientRepository->findOneById($request->request->get('clientId'));

        if(!$client){
            return $this->json(['error'=>'pas de client pour le consomation'], 403 );
        }

        $consomation->setClientId($client);

        $appareil=$this->appareilRepository->findOneById($request->request->get('appareilId'));

        if(!$appareil){
            return $this->json(['error'=>'pas de appareil pour le consomation'], 403 );
        }

        $consomation->setAppareilId($appareil);

        //dd($consomation);

        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($consomation);

            $this->em->flush();
            $this->em->commit();

        } catch (\Exception $e) {

            $this->em->rollback();
            return $this->json(['error' => "couldn't register the consomation in bdd"],500);
            throw $e;
        }

        return $this->json([
            'success' => 'volyvolt vous remercie',
            //'path' => 'src/Controller/ConsomationController.php',
        ]);
    }

    #[Route('/newConsomation', name: 'app_new_consomation', methods:'POST')]
    public function newConsomation(Request $request): JsonResponse
    {
        //consomation clientId appareilId

        $consomation= new Consomation();

        $year = $request->request->get('years');
        
        $month = $request->request->get('month');
        $day = $request->request->get('day');

        $date=Carbon::create($year, $month, $day, 0, 0, 0);

        $consomation->setConsomation($request->request->get('consomation'));
        $consomation->setDate($date);

       // $client = $this->clientRepository->findOneBy
        $client= $this->clientRepository->findOneById($request->request->get('clientId'));
        if(!$client){
            return $this->json(['error'=>'pas de client pour le consomation'], 403 );
        }

        $consomation->setClientId($client);

        $consomationPredit = $this->consomationPreditRepository->findOneBy([
            'client'=> $client, 
            'startWeek' => $date->startOfWeek(),
        ]);

        if(!$consomationPredit){
            return $this->json(['error'=>"couldn't find the consomation predit for the current consomation"], 403 );
        }

        //dd($consomationPredit);
        if ($consomationPredit){
            $consomationPredit->setConsomationReel(true);
            $consomation->setConsomationPredit($consomationPredit);    
        }
        $appareil=$this->appareilRepository->findOneById($request->request->get('appareilId'));

        if(!$appareil){
            return $this->json(['error'=>'pas de appareil pour le consomation'], 403 );
        }

        $consomation->setAppareilId($appareil);

        //dd($consomation);

        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($consomation);

            if($consomationPredit){
                $this->em->persist($consomationPredit);
            }

            $this->em->flush();
            $this->em->commit();

        } catch (\Exception $e) {

            $this->em->rollback();
            return $this->json(['error' => "couldn't register the consomation in bdd"],500);

            throw $e;
        }

        return $this->json([
            'success' => 'volyvolt vous remercie',
            //'path' => 'src/Controller/ConsomationController.php',
        ]);
    }

    #[Route('/newConsomationPredit', name: 'app_new_consomation_predit')]
    public function newConsomationPredit(Request $request): JsonResponse
    {

        $consomationPredit = new ConsomationPredit();
        //$now=Carbon::now();

        $year = $request->request->get('year');
        $month = $request->request->get('month');
        $day = $request->request->get('day');

        $consomationPredit->setConsomation($request->request->get('consomation'));

        //$startWeek= $now->startOfWeek();
        //$endWeek=$now->endOfWeek();
        //$date=Carbon::createFromDate(2023,9,30,'Europe/Berlin');
        $date=Carbon::create($year, $month, $day, 0, 0, 0);
        
        $consomationPredit->setStartWeek($date->startOfWeek());
        $consomationPredit->setConsomationReel(false);

        //tsy maints atao anatin io fa misy conflit start sy ny end
        $startWeek=$date->startOfWeek()->toDateTimeString();
        
        

        $consomationPredit->setStartWeek(new \DateTime($startWeek));
        $consomationPredit->setEndWeek($date->endOfWeek());



        $client= $this->clientRepository->findOneById($request->request->get('clientId'));

        if(!$client){
            return $this->json(['error'=>"could't find client by id = ".$request->request->get('clientId')],403);
        }
        //dd($client);
        $consomationPredit->setClient($client);

        //dd($date->endOfWeek(Carbon::SUNDAY));
        //dd($consomationPredit);

        
        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($consomationPredit);

            $this->em->flush();
            $this->em->commit();

        } catch (\Exception $e) {

            $this->em->rollback();
            return $this->json(['error' => "couldn't register the consomationPredit in bdd"],500);

            throw $e;
        }
        
        return $this->json([
            'success' => 'volyvolt vous remercie',
            //'path' => 'src/Controller/ConsomationController.php',
        ]);

    }

    #[Route('/getConsomationPredit/{id}', name: 'app_get_consomation_predit_by_user')]
    public function getConsomationPreditbyUser($id): JsonResponse
    {

        $consomationPredit=$this->consomationPreditRepository->findConsomationPredictedByClient($id,false);
        $consomation = array();
        $labelConsomation = array();

        foreach ($consomationPredit as $key => $cPredit) {
            $consomation[$key] = $cPredit->getConsomation();
            $labelConsomation[$key] = Carbon::instance($cPredit->getStartWeek())->locale('fr_FR')->isoFormat('MMM Do ');//.' - '.Carbon::instance($cPredit->getEndWeek())->locale('fr_FR')->isoFormat('MMM Do ');
        }


        return $this->json(['consomation'=>$consomation, 'label'=>$labelConsomation]);
    }

    #[Route('/getConsomationReelandPredit/{id}', name: 'app_get_reel_and_predit_consomation_by_user')]
    public function getConsomationReelAndPreditbyUser($id): JsonResponse
    {
        //$client = $this->clientRepository->findOneById($id);

        $_consomationPredit=$this->consomationPreditRepository->findConsomationPredictedByClient($id,true);

       /* if(!$_consomationPredit){
            return $this->json(['error'=> "couldn't find any consomation predit"],403);
        }*/

        $consomationPredit = array();
        $labelConsomation = array();
        $consomation = array();

        //dd($_consomationPredit);

        foreach ($_consomationPredit as $key => $cPredit) {
            $consomationPredit[$key] = $cPredit->getConsomation();
            $labelConsomation[$key] = Carbon::instance($cPredit->getStartWeek())->locale('fr_FR')->isoFormat('MMM Do ');//.' - '.Carbon::instance($cPredit->getEndWeek())->locale('fr_FR')->isoFormat('MMM Do ');
            //dd($cPredit);
           // $_consomation = $this->consomation()
            $_consomation = $this->consomationRepository->findByConsomationPredit($cPredit);

            $dataConsomation=0;
            $i=0;
            foreach ($_consomation as $cons ){
                $dataConsomation = $dataConsomation+$cons->getConsomation();
                $i++;
            }
            $consomation[$key]=$dataConsomation/$i;
        }


        return $this->json(['consomationPredit'=>$consomationPredit,'consomation'=>$consomation, 'label'=>$labelConsomation]);
    }

    #[Route('/getConsomation/{id}', name: 'app_get_consomation_by_user')]
    public function getConsomationbyUser($id): JsonResponse
    {
        $_consomation = $this->consomationRepository->findLastConsomationByUser($id);
        //dd($consomation);

        $consomation = array();

        foreach ($_consomation as $key => $cons){
            $consomation [$key]['id'] = $cons->getId();
            $consomation[$key]['appareilId']= $cons->getAppareilId()->getAppareilId();
            $consomation[$key]['consomation']= $cons->getConsomation();
            $consomation[$key]['date']= Carbon::instance($cons->getDate())->locale('fr_FR')->isoFormat('lll');
            //$consomation[$key]['']= $cons->get;

        }
        return $this->json($consomation);
    }

    #[Route('/consomationfromrasp', name: 'app_post_form_rasp', methods:'POST')]
    public function getConsomationfromrasp(Request $request): JsonResponse
    {
                //set VersoPhotoCIN
                $file = $request->files->get('file');

                if(!$file){
                    return $this->json(['error'=> 'there is any file sent'],403);
                }
                //dd($file);

                $filename = md5(uniqid()) . '.' . 'txt';//$file->guessClientExtension();
                $path = $this->getParameter('kernel.project_dir') . '/public/consomation';
                $file->move($path, $filename);
                
                //return $this->json($filename);

                $data = array();
               // $dataSingle = array();
                $i=0;

             /*  // dd($this->getParameter('kernel.project_dir').'\public\consomation');
                if ($file instanceof UploadedFile) {
                    $raw = file_get_contents($this->getParameter('kernel.project_dir')."\public\consomation\\".$filename);
                    //dd($raw);
                    $data = explode('\n',$raw);
                    dd($data);
                    }
                 dd('tsy mety');   

                //dd($file->readfile());*/

                try{

                //for local
                //$fh = fopen($this->getParameter('kernel.project_dir')."\public\consomation\\".$filename,'r');
                
                // for deployed
                $fh = fopen($this->getParameter('kernel.project_dir')."/public/consomation/".$filename,'r');
                } catch (\Exception $e) {
                    return $this->json([
                        'error'=>"couldn't find file at ".$this->getParameter('kernel.project_dir')."\public\consomation\\".$filename,
                        'error message' => $e->getMessage()
                ],500);
                }
                    while ($line = fgets($fh)) {
                    // <... Do your work with the line ...>
                   // dd($line);
                    $data[$i]=$line;
                    $i=$i+1;
                    }
                fclose($fh);
                
               // return $this->json(count($data));
               // dump($data);

                $dataClient = explode(';',$data[0]);
                //dd($dataClient);
                //getclientID
                $client = $this->clientRepository->findOneByClientId($dataClient[0]);
                  //  dd($client);

                if(!$client){
                    return $this->json(['error'=>"couldn't find client by id".$dataClient[0]],401);
                }
                
            
                //getAppareilID
                $dataAppareil= explode(';',$data[2]); 
                
                
                $appareil = $this->appareilRepository->findOneByAppareilId($dataAppareil[0]);
               // dd($appareil);
                //send two
                if(!$appareil){
                    return $this->json(['error'=>"couldn't find appareil by id ".$dataAppareil[0]]);
                }
    //new consomation

                for ($i=count($data)-10; $i<count($data); $i=$i+2){

                //return $this->json('mety');

                $dataConsomation= explode(';',$data[2]);

                //return $this->json($dataConsomation);

                $date=$dataConsomation[4];
                $hm=$dataConsomation[3];

                $Uvolt=$dataConsomation[1];
                $Iampere = $dataConsomation[2];
                
                try{  
                $datetime=Carbon::createFromFormat('Y:m:d H:i', $date.' '.$hm); 
                } catch (\Exception $e)
                {
                    
                return $this->json([
                    'error'=>"couldn't create date from".$date.' '.$hm,
                    'error message' => $e->getMessage()
                ],401);

                }
                //$datetime=Carbon::createFromFormat('Y:m:d H:i', $date.' '.$hm);
                
                // add first data consomation
                $consomation = new Consomation();
                
                //date
                $datetimeString=$datetime->toDateTimeString();
                $consomation->setDate(new \DateTime($datetimeString));

                // $client = $this->clientRepository->findOneBy

                 $consomation->setClientId($client);
                 $consomation->setAppareilId($appareil);

                 $consomation->setConsomation($Uvolt*$Iampere+300);
                 //$consomation1->setConsomation($Uvolt*$Iampere);
         
                 $consomationPredit = $this->consomationPreditRepository->findOneBy([
                     'client'=> $client,
                     'startWeek' => $datetime->startOfWeek(),
                 ]);

                 if(!$consomationPredit){
                    return $this->json(['error'=>"couldn't find the the consomation predict"],500);
                 }

                 if($consomationPredit){
                    $consomationPredit->setConsomationReel(true);
                    $consomation->setConsomationPredit($consomationPredit);
                 }

                // return $this->json('mety');

                 $this->em->getConnection()->beginTransaction();
                try {

                    $this->em->persist($consomation);

                    if($consomationPredit){
                        $this->em->persist($consomationPredit);
                    }

                    $this->em->flush();
                    $this->em->commit();

                } catch (\Exception $e) {

                    return $this->json(['error'=> "couldn't register consomation into bdd", 'error message'=> $e->getMessage()]);
                    $this->em->rollback();
                    throw $e;
                }

                 //dump($consomation);
                }
         
    //dd($consomationPredit);
         

               // dd();


        return $this->json(['mess'=>'Ok']);
    }


}

/*

<?php
namespace App\TimeController;

use Carbon\Carbon;

class TimeController
{
    public function transformTime(Carbon $datetime){
        $now=Carbon::now();
        
        $datetime->locale('fr_FR');

        //differnce refa 2 jours
        if($datetime->diffInDays($now,false)<2){
            //difference de deux heurs 
               if($datetime->diffInHours($now,false)<2){
                $date=$datetime->diffForHumans();                
                }
                else{
                    $date=$datetime->calendar();

                }          
        } 
        else{
            $date=$datetime->isoFormat('ddd LT');
        }
  
        return $date;

*/
