<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Specialities;
use App\Form\AgentType;
use App\Form\SpecialitiesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialitiesController extends AbstractController
{
    #[Route('/admin/specialities', name: 'app_specialities')]
    public function index(EntityManagerInterface $em): Response
    {
        $specialities = $em->getRepository(Specialities::class)->findAll();

        return $this->render('specialities/index.html.twig', [
            'specialities' => $specialities
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
    public function modifyAgent(Request $request, EntityManagerInterface $em, Specialities $specialities): Response
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
