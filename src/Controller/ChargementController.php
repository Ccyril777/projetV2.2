<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ChargementController extends AbstractController
{
    /**
     * @Route("/chargement", name="chargement")
     */
    public function index()
    {
        return $this->render('chargement/index.html.twig', [
            'controller_name' => 'ChargementController',
        ]);
    }
}
