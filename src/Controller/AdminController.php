<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Contactus;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_list')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $contactus = $doctrine->getRepository('App\Entity\Contactus')->findAll();
        return $this->render('admin/index.html.twig', [
            'contactus' => $contactus,
        ]);
    }


}
