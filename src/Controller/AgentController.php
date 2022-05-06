<?php

namespace App\Controller;

use App\Classe\Search;
use App\Form\SearchType;
use App\Repository\AgentRepository;
use App\Repository\CountryRepository;
use App\Repository\SpecialitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgentController extends AbstractController
{
    #[Route('/admin/agents', name: 'app_agent')]
    public function index( Request $request, AgentRepository $agentRepository, CountryRepository $countryRepository, SpecialitiesRepository $specialitiesRepository): Response
    {
        $search = new Search();
        $search->page = $request->get('page',1); // pagination ici on se trouve sur la page 1 (affichage de base)

        $filters = $request->get("nationality");
        $filters2 = $request->get("specialities");

        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        $agents = $agentRepository->findWidthSearch($search,$filters,$filters2);
        $nationalities = $countryRepository->findAll();
        $specialities = $specialitiesRepository->findAll();

        //on vérifie si on a une requete ajax
       if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('agent/_content.html.twig', [
                    'specialities' => $specialities,
                    'agents' => $agents,
                    'form' => $form->createView(),
                ])]);
        }
        return $this->render('agent/index.html.twig', [
            'nationalities' => $nationalities,
            'specialities' => $specialities,
            'agents' => $agents,
            'form' => $form->createView(),
        ]);
    }
}
