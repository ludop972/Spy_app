<?php

namespace App\Controller;

use App\Entity\Hideouts;
use App\Entity\Mission;
use App\Form\HideoutsType;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MissionDetailsController extends AbstractController
{
    #[Route('/mission/details/{id}', name: 'app_mission_details')]
    public function index(EntityManagerInterface $em, $id): Response
    {
        $mission = $em->getRepository(Mission::class)->findOneBy(['id' => $id]);
        return $this->render('mission_details/index.html.twig', [
            'mission' => $mission
        ]);
    }

    #[Route('/admin/add_mission', name: 'app_mission_add')]
    public function addMission(Request $request , EntityManagerInterface $em): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if (!$mission->missionError()) {
                $this->addFlash('error', "Votre nouvelle mission n'a pas pu être ajoutée car elle contient des erreurs. Veuillez vérifier les éléments suivants : Compétence(s) du ou des agents / Nationalité des agents ou contacts / Pays de la planque.");
                return $this->redirectToRoute('app_home');
            }else{
                $this->addFlash('success', 'La mission : '.$mission->getTitle().' a bien été créé.');
            }
                $em->persist($mission);
                $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('mission_details/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/edit_mission/{id}', name: 'app_mission_edit')]
    public function edit(EntityManagerInterface $em,Request $request, Mission $mission): Response
    {
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$mission->missionError()) {
                $this->addFlash('error', "Votre mission n'a pas pu être modifiée car elle contient des erreurs. Veuillez vérifier les éléments suivants : Spécialitée(s) du ou des agents / Nationalité des agents ou contacts / Pays de la planque.");
                return $this->redirectToRoute('app_home');
            } else {
                $this->addFlash('success', "La mission : ". $mission->getTitle()." à bien été modifiée !");
            }
            $em->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('mission_details/edit.html.twig', [
            'missions' => $mission,
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/delete_mission/{id}', name: 'app_mission_delete')]
    public function delete(Request $request, Mission $mission, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $mission->getId(), $request->request->get('_token'))) {
            $em->remove($mission);
            $em->flush();
            $this->addFlash('success', "La mission : ". $mission->getTitle()." à bien été supprimée !");
        }

        return $this->redirectToRoute('app_home');
    }
}
