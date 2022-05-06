<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactDetailsController extends AbstractController
{
    #[Route('/admin/contact/details/{id}', name: 'app_contact_details')]
    public function index(EntityManagerInterface $em, $id): Response
    {
        $contact = $em->getRepository(Contact::class)->findOneBy(['id' => $id]);

        return $this->render('contact_details/index.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/admin/add_contact', name: 'app_contact_add')]
    public function addContact(Request $request , EntityManagerInterface $em): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($contact);
            $em->flush();
            $this->addFlash('success', 'Le contact : '.$contact->getCodeName().' a bien été ajoutée.');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact_details/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/edit_contact/{id}', name: 'app_contact_edit')]
    public function editContact(Request $request, EntityManagerInterface $em, Contact $contact): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'Le Contact : '.$contact->getCodeName().' a bien été modifiée');
            $em->flush();
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact_details/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/delete_contact/{id}', name: 'app_contact_delete')]
    public function removeContact(EntityManagerInterface $em, Contact $contact, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contact->getId(), $request->request->get('_token'))) {
            $em->remove($contact);
            $em->flush();
            $this->addFlash('success', 'Le Contact : '.$contact->getCodeName().' a bien été supprimée');
        }
        return $this->redirectToRoute('app_contact');

    }
}
