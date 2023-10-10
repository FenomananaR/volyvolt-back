<?php

namespace App\Controller;
use App\Service\Algo;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private $algo;
    private $em;
    
    public function __construct(Algo $algo,EntityManagerInterface $em)
    {
        $this->algo = $algo;
        $this->em=$em;
    }


    #[Route('/ping', name: 'app_home')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'pong',
        ]);
    }

    #[Route('/algo', name: 'app_algo')]
    public function algo(): JsonResponse
    {
        $feno=$this->algo->add(56);
        dd($feno);
        return $this->json([
            'message' => 'pong',
        ]);
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