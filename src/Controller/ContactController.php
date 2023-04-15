<?php

namespace App\Controller;

use App\Entity\Contactus;
use App\Form\ContactusType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContactusRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Persistence\ManagerRegistry;
class   ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function createAction(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger)
    {
        $contactus = new Contactus();
        $form = $this->createForm(ContactusType::class, $contactus);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($contactus);
            $em->flush();

            $this->addFlash(
                'notice',
                'Contact Sented'
            );
            return $this->redirectToRoute('desti_list');
        }
        return $this->renderForm('contact/index.html.twig', ['form' => $form,]);
    }
    #[Route('/contact/delete/{id}', name: 'contact_delete')]
    public function deleteAction(ManagerRegistry $doctrine,$id)
    {
        $em = $doctrine->getManager();
        $contact = $em->getRepository('App\Entity\Contactus')->find($id);
        $em->remove($contact);
        $em->flush();

        $this->addFlash(
            'error',
            'Contact deleted'
        );
        return $this->redirectToRoute('admin_list');
    }
}
