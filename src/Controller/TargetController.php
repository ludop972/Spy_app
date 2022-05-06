<?php

namespace App\Controller;

use App\Classe\Search;
use App\Form\SearchType;
use App\Repository\TargetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TargetController extends AbstractController
{
    #[Route('/admin/target', name: 'app_target')]
    public function index(Request $request, TargetRepository $targetRepository): Response
    {
        $search = new Search();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $targets = $targetRepository->findWidthSearch($search);

        return $this->render('target/index.html.twig', [
            'targets' => $targets,
            'form' => $form->createView()
        ]);
    }
}
