<?php

namespace App\Controller;

use App\Classe\Search;
use App\Form\SearchType;
use App\Repository\MissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, MissionRepository $missionRepository): Response
    {
        $search = new Search();
        $search->page = $request->get('page',1);
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $missions = $missionRepository->findWidthSearch($search);
        return $this->render('home/index.html.twig', [
            'missions' => $missions,
            'form' => $form->createView()
        ]);
    }
}
