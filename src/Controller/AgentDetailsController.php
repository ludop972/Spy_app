<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Form\AgentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgentDetailsController extends AbstractController
{
    #[Route('/agent/details/{id}', name: 'app_agent_details')]
    public function index(EntityManagerInterface $em, $id): Response
    {
        $oneAgent = $em->getRepository(Agent::class)->findOneBy(['id' => $id]);
        return $this->render('agent_details/index.html.twig', [
            'agents' => $oneAgent
        ]);
    }

    #[Route('/admin/add_agent', name: 'app_add_agent')]
    public function addAgent(Request $request, EntityManagerInterface $em): Response
    {
        $agent = new Agent();

        $form = $this->createForm(AgentType::class, $agent);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($agent);
            $em->flush();
            $this->addFlash('success', 'L\'agent : '.$agent->getIdCode().' ajoutée avec succès');
            return $this->redirectToRoute('app_agent');
        }

        return $this->render('agent_details/add.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/edit_agent/{id}', name: 'app_edit_agent')]
    public function modifyAgent(Request $request, EntityManagerInterface $em, Agent $agent): Response
    {
        $form = $this->createForm(AgentType::class, $agent);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', 'L\'agent : '.$agent->getIdCode().' modifiée avec succès');
            return $this->redirectToRoute('app_agent');
        }

        return $this->render('agent_details/add.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/delete_agent/{id}', name: 'app_delete_agent')]
    public function deleteAgent(EntityManagerInterface $em, Agent $agent, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $agent->getId(), $request->request->get('_token'))) {
            $em->remove($agent);
            $em->flush();
            $this->addFlash('success', 'L\'agent : '. $agent->getIdCode().' supprimée avec succès');
        }
            return $this->redirectToRoute('app_agent');

    }
}
