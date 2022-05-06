<?php

namespace App\Controller;

use App\Classe\Search;
use App\Form\SearchType;
use App\Repository\HideoutsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HideoutsController extends AbstractController
{
    #[Route('/admin/hideouts', name: 'app_hideouts')]
    public function index(Request $request, HideoutsRepository $hideoutsRepository): Response
    {
        $search = new Search();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $hideouts = $hideoutsRepository->findWidthSearch($search);

        return $this->render('hideouts/index.html.twig', [
            'hideouts' => $hideouts,
            'form' => $form->createView()
        ]);
    }
}
