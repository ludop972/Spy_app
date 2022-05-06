<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Specialities;
use App\Form\SearchType;
use App\Form\SpecialitiesType;
use App\Repository\SpecialitiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SpecialitiesController extends AbstractController
{
    #[Route('/admin/specialities', name: 'app_specialities')]
    public function index( EntityManagerInterface $em,Request $request, SpecialitiesRepository $specialitiesRepository): Response
    {
        $search = new Search();
        $search->page = $request->get('page',1); // pagination ici on se trouve sur la page 1 (affichage de base)

        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        $specialities = $specialitiesRepository->findWidthSearch($search);

        return $this->render('specialities/index.html.twig', [
            'specialities' => $specialities,
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/add_specialities', name: 'app_specialities_add')]
    public function addSpecialities(Request $request , EntityManagerInterface $em): Response
    {
        $specialities = new Specialities();
        $form = $this->createForm(SpecialitiesType::class, $specialities);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($specialities);
            $em->flush();
            $this->addFlash('success', 'La spécialité : '.$specialities->getName().' a bien été ajoutée.');
            return $this->redirectToRoute('app_specialities');
        }

        return $this->render('specialities/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/edit_specialities/{id}', name: 'app_specialities_edit')]
    public function editSpecialities(Request $request, EntityManagerInterface $em, Specialities $specialities): Response
    {
        $form = $this->createForm(SpecialitiesType::class, $specialities);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'La spécialité : '.$specialities->getName().' a bien été modifiée');
            $em->flush();
            return $this->redirectToRoute('app_specialities');
        }

        return $this->render('specialities/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/delete_specialities/{id}', name: 'app_specialities_delete')]
    public function removeSpecialities(EntityManagerInterface $em, Specialities $specialities, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $specialities->getId(), $request->request->get('_token'))) {
            $em->remove($specialities);
            $em->flush();
            $this->addFlash('success', 'La spécialité : '.$specialities->getName().' a bien été supprimée');
        }
        return $this->redirectToRoute('app_specialities');

    }
}
