<?php

namespace App\Controller;

use App\Form\AreaType;
use App\Form\DestinationType;
use App\Repository\AreaRepository;
use App\Repository\DestinationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Destination;
use App\Entity\Area;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\createForm;

class DestiController extends AbstractController
{
    #[Route('/desti', name: 'desti_list')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $destinations = $doctrine->getRepository('App\Entity\Destination')->findAll();
        $areas = $doctrine->getRepository('App\Entity\Area')->findAll();
        return $this->render('desti/index.html.twig', [
            'destinations' => $destinations,'areas'=>$areas
        ]);
    }
    #[Route('/desti/destiByArea/{id}', name: 'destiByArea')]
    public function destiByAreaAction(ManagerRegistry $doctrine,$id): Response
    {
        $area = $doctrine->getRepository(Area::class)->find($id);
        $destinations=$area->getDestinations();
        $areas = $doctrine->getRepository('App\Entity\Area')->findAll();
        return $this->render('desti/index.html.twig', [
            'destinations' => $destinations,'areas'=>$areas
        ]);
    }

    #[Route('/desti/detail/{id}', name: 'desti_details')]
    public function detailsAction(ManagerRegistry $doctrine,$id)
    {
        $destinations = $doctrine->getRepository('App\Entity\Destination')->find($id);

        return $this->render('desti/details.html.twig', [
            'destinations' => $destinations
        ]);
    }
    #[Route('/desti/delete/{id}', name: 'desti_delete')]
    public function deleteAction(ManagerRegistry $doctrine,$id)
    {
        $em = $doctrine->getManager();
        $destination = $em->getRepository('App\Entity\Destination')->find($id);
        $em->remove($destination);
        $em->flush();

        $this->addFlash(
            'error',
            'Destination deleted'
        );
        return $this->redirectToRoute('desti_list');

    }


    #[Route('/desti/create', name: 'desti_create')]
    public function createAction(ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger, DestinationRepository $destinationRepository)

    {
        $destination=new Destination();
        $form=$this->createForm(DestinationType::class, $destination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image=$form->get('Image')->getData();
            if($image){
                $originalFilename=pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename= $slugger ->slug($originalFilename);
                $newFilename=$safeFilename . '-' . uniqid() . '.' . $image ->guessExtension();

                try{
                    $image->move(
                        $this->getParameter('destiImages_directory'),
                        $newFilename
                    );
                }catch(FileException $e){
                    $this->addFlash(
                        'error',
                        'Cannot upload'
                    );
                }
                $destination->setImage($newFilename);
            }else{
                $this->addFlash(
                    'error',
                    'Cannot upload'
                );
            }

            $em = $doctrine->getManager();
            $em->persist($destination);
            $em->flush();

            $this->addFlash(
                'notice',
                'Destination Added'
            );
            return $this->redirectToRoute('desti_list');
        }
        return $this->renderForm('desti/create.html.twig',['form'=> $form,]);
    }
#[Route('/desti/{id}/edit', name: 'desti_edit', methods: ['GET', 'POST'])]
    public function edit(ManagerRegistry $doctrine, int $id ,Request $request, Destination $destination, DestinationRepository $destinationRepository, SluggerInterface $slugger): Response
    {
        $em = $doctrine->getManager();
        $account = $em ->getRepository('App\Entity\Destination')->find($id);

        $form = $this->createForm(DestinationType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image=$form->get('Image')->getData();
            if($image){
                $originalFilename=pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename= $slugger ->slug($originalFilename);
                $newFilename=$safeFilename . '-' . uniqid() . '.' . $image ->guessExtension();

                try{
                    $image->move(
                        $this->getParameter('destiImages_directory'),
                        $newFilename
                    );
                }catch(FileException $e){
                    $this->addFlash(
                        'error',
                        'Cannot upload'
                    );
                }
                $account->setImage($newFilename);
            }else{
                $this->addFlash(
                    'error',
                    'Cannot upload'
                );
            }

            $em = $doctrine->getManager();
            $em ->persist($destination);
            $em ->flush();

            return $this->redirectToRoute('desti_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('desti/edit.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

}



