<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Target;
use App\Form\ContactType;
use App\Form\TargetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TargetDetailsController extends AbstractController
{
    #[Route('/admin/target/details', name: 'app_target_details')]
    public function index(EntityManagerInterface $em, $id): Response
    {
        $target = $em->getRepository(Target::class)->findOneBy(['id' => $id]);
        return $this->render('target_details/index.html.twig', [
            'target' => $target
        ]);
    }

    #[Route('/admin/add_target', name: 'app_target_add')]
    public function addTarget(Request $request , EntityManagerInterface $em): Response
    {
        $target = new Target();
        $form = $this->createForm(TargetType::class, $target);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($target);
            $em->flush();
            $this->addFlash('success', 'La Cible : '.$target->getCodeName().' a bien été ajoutée.');
            return $this->redirectToRoute('app_target');
        }

        return $this->render('target_details/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/edit_target/{id}', name: 'app_target_edit')]
    public function editTarget(Request $request, EntityManagerInterface $em, Target $target): Response
    {
        $form = $this->createForm(TargetType::class, $target);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'La Cible : '.$target->getCodeName().' a bien été modifiée');
            $em->flush();
            return $this->redirectToRoute('app_target');
        }

        return $this->render('target_details/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/delete_target/{id}', name: 'app_target_delete')]
    public function removeTarget(EntityManagerInterface $em, Target $target, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $target->getId(), $request->request->get('_token'))) {
            $em->remove($target);
            $em->flush();
            $this->addFlash('success', 'La Cible : '.$target->getCodeName().' a bien été supprimée');
        }
        return $this->redirectToRoute('app_target');

    }
}
