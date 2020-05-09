<?php

namespace App\Controller;

use App\Entity\SystemeInformation;
use App\Form\SystemeInformationType;
use App\Repository\SystemeInformationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Confidentialite;
use App\Repository\ConfidentialiteRepository;
use App\Entity\Domaine;
use App\Repository\DomaineRepository;
use App\Entity\TypologyMI;
use App\Repository\TypologyMIRepository;

/**
 * @Route("/systeme/information")
 */
class SystemeInformationController extends AbstractController
{
    /**
     * @Route("/", name="systeme_information_index", methods={"GET"})
     */
    public function index(SystemeInformationRepository $systemeInformationRepository): Response
    {
        return $this->render('systeme_information/index.html.twig', [
            'systeme_informations' => $systemeInformationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/aggridview", name="aggridview", methods={"GET","POST"})
     */
    public function aggrid(SystemeInformationRepository $systemeInformationRepository, 
                            DomaineRepository $domaineRepository, 
                            ConfidentialiteRepository $confidentialiteRepository,
                            TypologyMIRepository $typologyMIRepository): response
    {
        return $this->render('ag-grid.html.twig', [
            'systeme_informations' => $systemeInformationRepository->findAll(),
            'domaines' => $domaineRepository->findAll(),
            'confidentialites' => $confidentialiteRepository->findAll(),
            'types' => $typologyMIRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="systeme_information_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $systemeInformation = new SystemeInformation();
        $form = $this->createForm(SystemeInformationType::class, $systemeInformation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($systemeInformation);
            $entityManager->flush();

            return $this->redirectToRoute('systeme_information_index');
        }

        return $this->render('systeme_information/new.html.twig', [
            'systeme_information' => $systemeInformation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/form/create", name="form_create")
     */
    public function create(Request $request, ObjectManager $manager) {

        if ($request->request->count()>0){
            $usualName = new SystemeInformation();
            $usualName->setUsualName($request->request->get('usualName'))
                    ->setSiiName($request->request->get('SiiName'))
                    ->setDescription($request->request->get('Description'))
                    ->setConfidentialite($request->request->get('confidentialiteName'))
                    ->setDomaine($request->request->get('domaineName'))
                    ->setTypologyMI($request->request->get('shotName'))
                    ->setTypologyMI($request->request->get('longName'))
                    ->setCreateAt(new \DateTime());

            $manager->persist($usualName);
            $manager->flush();
        }
        return $this->render('form/create.html.twig');
    }

    /**
     * @Route("/{id}", name="systeme_information_show", methods={"GET"})
     */
    public function show(SystemeInformation $systemeInformation): Response
    {
        return $this->render('systeme_information/show.html.twig', [
            'systeme_information' => $systemeInformation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="systeme_information_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SystemeInformation $systemeInformation): Response
    {
        $form = $this->createForm(SystemeInformationType::class, $systemeInformation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('systeme_information_index');
        }

        return $this->render('systeme_information/edit.html.twig', [
            'systeme_information' => $systemeInformation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="systeme_information_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SystemeInformation $systemeInformation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$systemeInformation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($systemeInformation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('systeme_information_index');
    }

}
