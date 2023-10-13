<?php

namespace App\Controller;
use App\Service\Algo;

use App\Entity\Message;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private $algo;
    private $em;
    private $clientRepository;
    
    public function __construct(Algo $algo,EntityManagerInterface $em, ClientRepository $clientRepository)
    {
        $this->algo = $algo;
        $this->em=$em;
        $this->clientRepository=$clientRepository;
    }


    #[Route('/ping', name: 'app_home')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'pong sc',
        ]);
    }

    #[Route('/algo', name: 'app_algo')]
    public function algo(Request $request): JsonResponse
    {
        //organisme,email,numero,localite,distance,nbrmenage,ptkioske,contractduration//organisme,email,numero,localite,distance,nbrmenage,ptkioske,contractduration
        //$feno=$this->algo->add(56);
       // dd($feno);

        $devis = $this->algo->generateDevis(
            $request->request->get('nbrmenage'),
            $request->request->get('contractduration'),
            $request->request->get('distance'),
            $request->request->get('ptkioske'),
        );

        return $this->json([
            'devis' => $devis,
            'organisme'=>$request->request->get('organisme'),
            'email'=>$request->request->get('email'),
            'numero'=>$request->request->get('numero'),
            'localite'=>$request->request->get('localite'),
            'distance'=>$request->request->get('distance'),
            'nbrmenage'=>$request->request->get('nbrmenage'),
            'ptkioske'=>$request->request->get('ptkioske'),
            'contractduration'=>$request->request->get('contractduration')
        ]);
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(Request $request): JsonResponse
    {
        $nbrClient= $this->clientRepository->countClient();
        //dd($nbrClient);
        return $this->json(['nbrClient'=>$nbrClient]);
    }

    #[Route('/message', name: 'app_message')]
    public function message(Request $request): JsonResponse
    {
        //NomOrOrganisme,email,numero,communicationMeans,volyvoltAware,objet,message

        $message = new Message();

        $message->setEmail($request->request->get('email'));
        $message->setNomOrOrganisme($request->request->get('NomOrOrganisme'));
        $message->setNumero($request->request->get('numero'));
        $message->setCommunicationMeans($request->request->get('communicationMeans'));
        $message->setVolyvoltAware($request->request->get('volyvoltAware'));
        $message->setObjet($request->request->get('objet'));
        $message->setMessage($request->request->get('message'));


        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($message);

            $this->em->flush();
            $this->em->commit();

        } catch (\Exception $e) {

            $this->em->rollback();
            throw $e;
        }

        return $this->json(['message'=>'ok']);

    }
}