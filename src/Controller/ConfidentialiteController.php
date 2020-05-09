<?php

namespace App\Controller;

use App\Entity\Confidentialite;
use App\Form\ConfidentialiteType;
use App\Repository\ConfidentialiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/confidentialite")
 */
class ConfidentialiteController extends AbstractController
{
    /**
     * @Route("/", name="confidentialite_index", methods={"GET"})
     */
    public function index(ConfidentialiteRepository $confidentialiteRepository): Response
    {
        return $this->render('confidentialite/index.html.twig', [
            'confidentialites' => $confidentialiteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="confidentialite_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $confidentialite = new Confidentialite();
        $form = $this->createForm(ConfidentialiteType::class, $confidentialite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($confidentialite);
            $entityManager->flush();

            return $this->redirectToRoute('confidentialite_index');
        }

        return $this->render('confidentialite/new.html.twig', [
            'confidentialite' => $confidentialite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="confidentialite_show", methods={"GET"})
     */
    public function show(Confidentialite $confidentialite): Response
    {
        return $this->render('confidentialite/show.html.twig', [
            'confidentialite' => $confidentialite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="confidentialite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Confidentialite $confidentialite): Response
    {
        $form = $this->createForm(ConfidentialiteType::class, $confidentialite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('confidentialite_index');
        }

        return $this->render('confidentialite/edit.html.twig', [
            'confidentialite' => $confidentialite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="confidentialite_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Confidentialite $confidentialite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$confidentialite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($confidentialite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('confidentialite_index');
    }
}
