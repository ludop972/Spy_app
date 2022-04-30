<?php

namespace App\Controller;

use App\Entity\Agent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgentController extends AbstractController
{
    #[Route('/admin/agents', name: 'app_agent')]
    public function index(EntityManagerInterface $em): Response
    {
        $agents = $em->getRepository(Agent::class)->findAll();

        return $this->render('agent/index.html.twig',[
            'agents' => $agents
        ]);
    }
}
