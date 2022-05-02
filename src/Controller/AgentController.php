<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Agent;
use App\Form\SearchType;
use App\Repository\AgentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgentController extends AbstractController
{


    #[Route('/admin/agents', name: 'app_agent')]
    public function index(EntityManagerInterface $em, Request $request, AgentRepository $agentRepository): Response
    {
        $error = null;
        $search = new Search();
        $search->page = $request->get('page',1); // pagination ici on se trouve sur la page 1 (affichage de base)


        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);



        if (($form->isSubmitted()) && ($form->isValid())) {
            $agents = $agentRepository->findWidthSearch($search);
            if (!$agents)
            {
            $error = "Malheureusement la recherche effectué n'a rien donnée";
            }
        } else {
            $agents = $agentRepository->findWidthSearch($search);
        }


        return $this->render('agent/index.html.twig', [
            'agents' => $agents,
            'error' => $error,
            'form' => $form->createView()
        ]);
    }
}
