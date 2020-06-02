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
 *
 * @Route("/systeme/information")
 */
class SystemeInformationController extends AbstractController
{

    /**
     *
     * @Route("/", name="systeme_information_index", methods={"GET"})
     */
    public function index(SystemeInformationRepository $systemeInformationoli): Response
    {
        return $this->render('systeme_information/index.html.twig', [
            'systeme_informations' => $systemeInformationoli->findAll()
        ]);
    }

    /**
     *
     * @Route("/aggridview", name="aggridview", methods={"GET","POST"})
     */
    // Symfony injecte des dépendances afin d'élaborer une réponse.
    public function aggrid(SystemeInformationRepository $systemeInformationRepository, DomaineRepository $domaineRepository, ConfidentialiteRepository $confidentialiteRepository, TypologyMIRepository $typologyMIRepository, Request $request): response
    {
        // Récupération du Manager, grâce au SystemeInformationController, qui se trouve dans Doctrine; c'est une récupération par héritage.
        $manager = $this->getDoctrine()->getManager();

        // Si la requête n'est pas vide
        // PS : La requête est vide lorsque l'on charge le path pour la première fois.
        if ($request->request->count() > 0) {
            //
            $si = new SystemeInformation();
            // Recontistution d'une chaîne de caractère en format JSON à partir de la requête dont l'élément 'Description' n'est qu'un fragment de JSON,
            // afin de pouvoir convertir la chaîne de caractère en objet.
            $desc = "{\"list\":[" . $request->request->get('Description') . "]}";
            // Transformation d'une chaîne de caractères en format JSON en un objet.
            $object = json_decode($desc);
            // Récupération d'un tableau d'objet. Chacun de ces objets correspond à un fragment JSON.
            // Il représente une action unitaire réaliser par l'utilisateur dans le tableau AG-Grid
            $maliste = $object->{'list'};
            // Le mapping est un procédé permettant de définir la correspondance entre deux modèles de données.
            // L'accès aux données se fait à travers les requêtes SQL, fortement typées selon la structure des données.
            // Le mapping permet aux utilisateurs d'accéder aux données à travers un ensemble de fonctions
            // sans se soucier de la structures des bases de données.
            $idrowmapping = [];
            // Un foreach fournit une façon simple de parcourir les tableaux. Cela ne fonctionne que pour le tableaux et les obljets.
            // Cela émet une erreur si l'on tente de l'utiliser sur une variable d'un autre type.
            foreach ($maliste as $rowAgridComplet) {
                $ops = $rowAgridComplet->{'operation'};
                $rowAgrid = $rowAgridComplet->{'data'};
                if ($ops == "addupdate") {
                    // Vérifie si la clef ID n'existe pas dans le tableau
                    if (! array_key_exists('id', $rowAgrid)) {
                        // Vérifie si une clef n'existe pas dans le tableau
                        if (array_key_exists($rowAgridComplet->{'idrow'}, $idrowmapping)) {
                            $si = $systemeInformationRepository->findOneById($idrowmapping[$rowAgridComplet->{'idrow'}]);
                        }
                    } else {
                        // Si la clef existe dans le tableau, on retourne tous les ID de la base de données.
                        $si = $systemeInformationRepository->findOneById($rowAgrid->{'id'});
                    }
                    // Vérifie si la clef usual_name existe dans un tableau
                    if (array_key_exists('usual_name', $rowAgrid)) {
                        $si->setUsualName($rowAgrid->{'usual_name'});
                    } else {
                        // S'il n'y a pas de données, la cellule est vide.
                        $si->setUsualName('');
                    }
                    // Vérifie si la clef sii_name existe dans le tableau
                    if (array_key_exists('sii_name', $rowAgrid)) {
                        $si->setSiiName($rowAgrid->{'sii_name'});
                    } else {
                        // S'il n'y a pas de données, la cellule est vide.
                        $si->setSiiName('');
                    }
                    // Vérifie si la clef description existe dans le tableau
                    if (array_key_exists('description', $rowAgrid)) {
                        $si->setDescription($rowAgrid->{'description'});
                    } else {
                        // S'il n'y a pas de données, la cellule est vide.
                        $si->setDescription('');
                    }
                    // Vérifie si la clef confidentialite existe dans le tableau
                    if (array_key_exists('confidentialite', $rowAgrid)) {
                        $newconf = $confidentialiteRepository->findOneById($rowAgrid->{'confidentialite'}->{'id'});
                        $si->setConfidentialite($newconf);
                    } else {
                        // S'il n'y en a pas, c'est le premier inscrit dans la base de données qui est affiché.
                        $confs = $confidentialiteRepository->findAll();
                        $conf = $confs[1];
                        $si->setConfidentialite($conf);
                    }
                    // Vérifie si la clef domaine existe dans le tableau
                    if (array_key_exists('domaine', $rowAgrid)) {
                        $newdomaine = $domaineRepository->findOneById($rowAgrid->{'domaine'}->{'id'});
                        $si->setDomaine($newdomaine);
                    } else {
                        // S'il n'y en a pas, c'est le premier inscrit dans la base de données qui est affiché.
                        $doms = $domaineRepository->findAll();
                        $dom = $doms[1];
                        $si->setDomaine($dom);
                    }
                    // Vérifie si la clef typologie existe dans le tableau
                    if (array_key_exists('typology', $rowAgrid)) {
                        $newtype = $typologyMIRepository->findOneById($rowAgrid->{'typology'}->{'id'});
                        $si->setType($newtype);
                    } else {
                        // S'il n'y en a pas, c'est le premier inscrit dans la base de données qui est affiché
                        $types = $typologyMIRepository->findAll();
                        $type = $types[1];
                        $si->setType($type);
                    }
                    // Vérifie si la clef si_support existe dans le tableau.
                    if (array_key_exists('si_support', $rowAgrid)) {
                        $this->removeAllSystemeSupport($si);
                        $idList = $rowAgrid->{'si_support'}->{'id'};

                        echo "Liste des Ids : " . $idList . "<br>";
                        $idsagrid = explode(';', $idList);
                        foreach ($idsagrid as $monsisupportid) {

                            $systemeSupport = $systemeInformationRepository->findOneById($monsisupportid);
                            $si->addSystemeSupport($systemeSupport);
                        }
                    }
                    // Cette opération a pour effet de rendre les données persistantes
                    $manager->persist($si);
                    // Met à jour la base à partir des objets signalés à Doctrine
                    // Tant qu'elle n'est pas appellée, rien n'est modifié en base.
                    $manager->flush();
                    //
                    if (! array_key_exists('id', $rowAgrid) && array_key_exists($rowAgridComplet->{'idrow'}, $idrowmapping)) {
                        $idrowmapping[$rowAgridComplet->{'idrow'}] = $si->getId();
                    }
                    // Suppression des données
                } elseif ($ops == "delete") {
                    if (! array_key_exists('id', $rowAgrid)) {
                        if (array_key_exists($rowAgridComplet->{'idrow'}, $idrowmapping)) {
                            $si = $systemeInformationRepository->findOneById($idrowmapping[$rowAgridComplet->{'idrow'}]);
                        }
                    } else {
                        $si = $systemeInformationRepository->findOneById($rowAgrid->{'id'});
                    }

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($si);
                    $entityManager->flush();
                }
            }
        } else {}
        // SystemeInformationController nous renvoi vers notre page HTML généré par le Renderer Symfony, et créé le contexte de génération de Twig,
        // en injectant les informations récupérées par les Repository.
        return $this->render('ag-grid.html.twig', [
            'systeme_informations' => $systemeInformationRepository->findAll(),
            'domaines' => $domaineRepository->findAll(),
            'confidentialites' => $confidentialiteRepository->findAll(),
            'types' => $typologyMIRepository->findAll()
        ]);
    }

    // fonction permettant de supprimer toutes les données enregistrées dans le SystemeSupport, afin de pouvoir les modifier.
    public function removeAllSystemeSupport(SystemeInformation $si)
    {
        foreach ($si->getSystemeSupport() as $sisupport) {
            $si->removeSystemeSupport($sisupport);
        }
    }

    /**
     *
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
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/form/create", name="form_create")
     */
    public function create(Request $request, DomaineRepository $domaineRepository, ConfidentialiteRepository $confidentialiteRepository, TypologyMIRepository $typologyMIRepository): response
    {
        echo "Json = " . $MyJSON;
        if ($request->request->count() > 0) {
            $si->setUsualName($request->request->get('usualName'))
                ->setSiiName($request->request->get('SiiName'))
                ->setDescription($request->request->get('Description'));

            // ->setConfidentialite($request->request->get('confidentialiteName'))
            // ->setDomaine($request->request->get('domaineName'))
            // ->setTypologyMI($request->request->get('shortName'))
            // ->($request->request->get('longName'))
            // $manager->persist($usualName);
            // $manager->flush();
        }
        return $this->render('form/create.html.twig', [
            'form' => $si
        ]);
    }

    /**
     *
     * @Route("/{id}", name="systeme_information_show", methods={"GET"})
     */
    public function show(SystemeInformation $systemeInformation): Response
    {
        return $this->render('systeme_information/show.html.twig', [
            'systeme_information' => $systemeInformation
        ]);
    }

    /**
     *
     * @Route("/{id}/edit", name="systeme_information_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SystemeInformation $systemeInformation): Response
    {
        $form = $this->createForm(SystemeInformationType::class, $systemeInformation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('systeme_information_index');
        }

        return $this->render('systeme_information/edit.html.twig', [
            'systeme_information' => $systemeInformation,
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/{id}", name="systeme_information_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SystemeInformation $systemeInformation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $systemeInformation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($systemeInformation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('systeme_information_index');
    }
}
