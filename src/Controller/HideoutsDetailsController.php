<?php

namespace App\Controller;

use App\Entity\Hideouts;
use App\Form\HideoutsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HideoutsDetailsController extends AbstractController
{
    #[Route('/admin/hideouts/details/{id}', name: 'app_hideouts_details')]
    public function index(EntityManagerInterface $em, $id): Response
    {
        $hideout = $em->getRepository(Hideouts::class)->findOneBy(['id' => $id]);
        return $this->render('hideouts_details/index.html.twig', [
            'hideout' => $hideout
        ]);
    }

    #[Route('/admin/add_hideout', name: 'app_hideouts_add')]
    public function addHideouts(Request $request , EntityManagerInterface $em): Response
    {
        $hideout = new Hideouts();
        $form = $this->createForm(HideoutsType::class, $hideout);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($hideout);
            $em->flush();
            $this->addFlash('success', 'La Planque : '.$hideout->getAlias().' a bien été ajoutée.');
            return $this->redirectToRoute('app_hideouts');
        }

        return $this->render('hideouts_details/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/edit_hideout/{id}', name: 'app_hideouts_edit')]
    public function editHideouts(Request $request, EntityManagerInterface $em, Hideouts $hideout): Response
    {
        $form = $this->createForm(HideoutsType::class, $hideout);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'La Planque : '.$hideout->getAlias().' a bien été modifiée');
            $em->flush();
            return $this->redirectToRoute('app_hideouts');
        }

        return $this->render('hideouts_details/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/delete_hideout/{id}', name: 'app_hideouts_delete')]
    public function removeHideout(EntityManagerInterface $em, Hideouts $hideout, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $hideout->getId(), $request->request->get('_token'))) {
            $em->remove($hideout);
            $em->flush();
            $this->addFlash('success', 'La Planque : '.$hideout->getAlias().' a bien été supprimée');
        }
        return $this->redirectToRoute('app_hideouts');

    }
}
