<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AreaType;
use App\Entity\Area;
use App\Repository\AreaRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Controller\createForm;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;


class AreaController extends AbstractController
{
    #[Route('/area/createArea', name: 'desti_create1')]
    public function create1Action(ManagerRegistry $doctrine, Request $request, AreaRepository $areRepository): Response
    {
        $area = new Area();
        $form = $this->createForm(AreaType::class, $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($area);
            $em->flush();

            return $this->redirectToRoute('desti_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('area/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/area/delete/{id}', name: 'area_delete')]
    public function deleteAction(ManagerRegistry $doctrine, $id)
    {
        $em = $doctrine->getManager();
        $area = $em->getRepository('App\Entity\Area')->find($id);
        $em->remove($area);
        $em->flush();

        $this->addFlash(
            'error',
            'Area deleted'
        );
        return $this->redirectToRoute('desti_list');

    }

    #[Route('/area/{id}/edit', name: 'area_edit', methods: ['GET', 'POST'])]
    public function edit(ManagerRegistry $doctrine, int $id, Request $request, Area $area, AreaRepository $areaRepository, SluggerInterface $slugger): Response
    {
        $em = $doctrine->getManager();
        $area = $em->getRepository('App\Entity\Area')->find($id);
        $form = $this->createForm(AreaType::class, $area);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($area);
            $em->flush();
            return $this->redirectToRoute('desti_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('area/edit.html.twig', [
            'area' => $area,
            'form' => $form,
        ]);

    }




}