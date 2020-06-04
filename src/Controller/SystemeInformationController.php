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
            // Recontistution d'une chaîne de caractère en format JSON à partir de la requête dont l'élément 'Description' n'est qu'un fragment de JSON,
            // afin de pouvoir convertir la chaîne de caractère en objet.
            $desc = "{\"list\":[" . $request->request->get('Description') . "]}";
            // Transformation d'une chaîne de caractères en format JSON en un objet.
            $object = json_decode($desc);
            // Récupération d'un tableau d'objet. Chacun de ces objets correspond à un fragment JSON.
            // Il représente une action unitaire réalisée par l'utilisateur dans le tableau AG-Grid
            $maliste = $object->{'list'};
            // Lorsque l'on ajoute une ligne dans le tableau ag-grid, on ne dispose pas encore de l'identifiant de l'objet correspondant en base de données.
            // Il faut donc trouver un moyen pour lui attribuer un identifiant transitoire : cela sera le rôle de l'identifiant de nœud, de la ligne du tabealu Ag-Grid.
            // Cet identifiant est invariant lorsque on effectue des opéartions impactant l'ordre des lignes dans le tableaux.
            // Dès que l'objet associé à la ligne est créé en base de données, il faut pouvoir maintenir le lien entre identifiant de nœud et l'identifiant de l'objet en base,
            // le temps que tout l'ensemble de la transaction se termine: c'est le rôle du tableau $idrowmapping que de garder ce lien.
            // En effet, les champs de la ligne ajoutée entre deux actions de sauvegarde, peuvent subir des modifications sans que l'identifiant de l'objet en base n'ait déjà été retourné au client.
            $idrowmapping = [];
            // Passe en revue le tableau d'objet maliste. A chaque itération, les valeurs de l'élément courant sont assignées à rowAgridComplet.
            // rowAgridComplet représente successivement chaque action de la transaction.  
            foreach ($maliste as $rowAgridComplet) {
                // On attribut à ops la valeur 'operation' qui est le type d'opération de l'action en cours de traitement.
                $ops = $rowAgridComplet->{'operation'};
                // On attribut à rowAgrid la valeur de la propriété data de rowAgridComplet. 
                // rowAgrid est donc l'objet représentant les données de la ligne brute. 
                $rowAgrid = $rowAgridComplet->{'data'};
                // Si ops a une valeur correspondant à 'addupdate'
                if ($ops == "addupdate") {
                    // si la clef ID n'existe pas dans le tableau, cela signifie que l'on travail sur une nouvelle ligne
                    //qui n'a pas encore été enregistré dans notre base de données.
                    if (! array_key_exists('id', $rowAgrid)) {
                        // Si la clef idrow (nodeid d'Ag-Grid) existe dans le tableau idrowmapping c'est que l'on est en train de contiuer l'édition d'une nouvelle ligne. 
                        if (array_key_exists($rowAgridComplet->{'idrow'}, $idrowmapping)) {
                            // Grâce au mapping, on peut retrouver l'identifiant de base de données de la ligne en cours d'édition.
                            $id_db=$idrowmapping[$rowAgridComplet->{'idrow'}];
                            // On récupère le si en base à partir de son identifiant. Ce n'est pas une méthode optimale. 
                            $si = $systemeInformationRepository->findOneById($id_db);
                        }
                        // Sinon c'est la première fois que l'on doit traité cette nouvelle ligne par une action. 
                        // Dans ce cas, on se contente de créer un objet si vide. 
                        else {
                            $si = new SystemeInformation();
                        }
                    } else {
                        // Si la clef existe dans le tableau Ag-Grid, retourne les entitiés, identifiées grâce à leur ID.
                        $si = $systemeInformationRepository->findOneById($rowAgrid->{'id'});
                    }
                    // Vérifie si la clef usual_name existe dans le tableau
                    if (array_key_exists('usual_name', $rowAgrid)) {
                        // Mise à jour de l'attribut usual name de l'objet si, qui est la représentation du si dans la base de données, 
                        // avec la valeur saisie par l'utilisateur dans le tableau Ag-Grid.
                        // Cette valeur se trouve dans l'attribut usual name de l'objet rowAgrid. 
                        $si->setUsualName($rowAgrid->{'usual_name'});
                    } else {
                        // S'il n'y a pas de données, la cellule renvoie une chaîne de caractères vide.
                        $si->setUsualName('');
                    }
                    // Vérifie si la clef sii_name existe dans le tableau
                    if (array_key_exists('sii_name', $rowAgrid)) {
                        // Mise à jour de l'attribut sii_name de l'objet si, qui est la représentation du si dans la base de données,
                        // avec la valeur saisie par l'utilisateur dans le tableau Ag-Grid.
                        // Cette valeur se trouve dans l'attribut sii_name de l'objet rowAgrid. 
                        $si->setSiiName($rowAgrid->{'sii_name'});
                    } else {
                        // S'il n'y a pas de données, la cellule du composant Ag-Grid renvoie une chaîne de caractères vide.
                        $si->setSiiName('');
                    }
                    // Si la clef description existe dans le tableau
                    if (array_key_exists('description', $rowAgrid)) {
                        // Mise à jour de l'attribut description de l'objet si, qui est la représentation du si dans la base de données,
                        // avec la valeur saisie par l'utilisateur dans le tableau Ag-Grid.
                        // Cette valeur se trouve dans l'attribut description de l'objet rowAgrid.
                        $si->setDescription($rowAgrid->{'description'});
                    } else {
                        // S'il n'y a pas de données, la cellule d'Ag-Grid renvoie une chaîne de caractère vide.
                        $si->setDescription('');
                    }
                    // Si la clef confidentialite existe dans le tableau
                    if (array_key_exists('confidentialite', $rowAgrid)) {
                        // On attribut à $idconf l'identifiant de l'objet confidentialité, choisi par l'utilisateur dans aggrid, et que l'on retrouve dans rowAgrid. 
                        $idconf = $rowAgrid->{'confidentialite'}->{'id'};
                        // On cherche dans la base de données la confidentiatlité grâce à son identifiant. 
                        $newconf = $confidentialiteRepository->findOneById($idconf);
                        // On va associer à $si la confidentialité choisi par l'utilisateur dans la base de données. 
                        $si->setConfidentialite($newconf);
                    } else {
                        // S'il n'y a pas de clef 'confidentialité', c'est le premier inscrit dans la base de données qui est affiché.
                        $confs = $confidentialiteRepository->findAll();
                        $conf = $confs[1];
                        $si->setConfidentialite($conf);
                    }
                    // Si la clef domaine existe dans le tableau
                    if (array_key_exists('domaine', $rowAgrid)) {
                        // On attribut à $iddom l'identifiant de l'objet domaine, choisi par l'utilisateur dans aggrid, et que l'on retrouve dans rowAgrid.
                        $iddom = $rowAgrid->{'domaine'}->{'id'};
                        // On cherche dans la base de données le domaine grâce à son identifiant. 
                        $newdomaine = $domaineRepository->findOneById($iddom);
                        // On va associer à $si le domaine choisi par l'utilisateur dans la base de données. 
                        $si->setDomaine($newdomaine);
                    } else {
                        // S'il n'y a pas de clef 'domaine', c'est le premier inscrit dans la base de données qui est affiché.
                        $doms = $domaineRepository->findAll();
                        $dom = $doms[1];
                        $si->setDomaine($dom);
                    }
                    // Si la clef typologie existe dans le tableau
                    if (array_key_exists('typology', $rowAgrid)) {
                        // On attribut à $idtype l'identifiant de l'objet typologie, choisi par l'utilisateur dans aggrid, et que l'on retrouve dans rowAgrid.
                        $idtype = $rowAgrid->{'typology'}->{'id'};
                        // On cherche dans la base de données la typologie grâce à son identifiant. 
                        $newtype = $typologyMIRepository->findOneById($idtype);
                        // On va associer à $si la typologie choisi par l'utilisateur dans la base de données.
                        $si->setType($newtype);
                    } else {
                        // S'il n'y a pas de clef typologie, c'est le premier inscrit dans la base de données qui est affiché
                        $types = $typologyMIRepository->findAll();
                        $type = $types[1];
                        $si->setType($type);
                    }
                    // Si la clef si_support existe dans le tableau.
                    if (array_key_exists('si_support', $rowAgrid)) {
                        // L'object courant, à savoir le SytemeInformationController, va utiliser la fonction removeAllSystemeSupport sur le Systeme Information.
                        $this->removeAllSystemeSupport($si);
                        // On attribut à $idList l'identifiant de l'objet Si Support, choisi par l'utilisateur dans aggrid, et que l'on retrouve dans rowAgrid.
                        $idList = $rowAgrid->{'si_support'}->{'id'};

                        if ($idList != "") {
                            // Délimitation des IdList par un point-virgule.
                            $idsagrid = explode(';', $idList);
                            // Passe en revue le tableau d'objet idsagrid. A chaque itération, les valeurs de l'élément courant sont assignées à monsisupportid.
                            // rowAgridComplet représente successivement chaque action de la transaction.  
                            foreach ($idsagrid as $monsisupportid) {
                                // On cherche dans la base de données le sisupport grâce à son identifiant.
                                $systemeSupport = $systemeInformationRepository->findOneById($monsisupportid);
                                // On va associer à $si le si support choisi par l'utilisateur dans la base de données.
                                $si->addSystemeSupport($systemeSupport);
                            }
                        }
                    }
                    // Cette opération a pour effet de rendre les données du système d'information persistantes
                    $manager->persist($si);
                    // Met à jour la base à partir des objets signalés à Doctrine
                    // Tant qu'elle n'est pas appellée, rien n'est modifié en base.
                    $manager->flush();
                    //
                    if (! array_key_exists('id', $rowAgrid) && !array_key_exists($rowAgridComplet->{'idrow'}, $idrowmapping)) {
                        $idrowmapping[$rowAgridComplet->{'idrow'}] = $si->getId();
                    }
                    // Si ops a une valeur correspondant à 'delete'
                } elseif ($ops == "delete") {
                    // Si la clef id n'existe pas dans le tableau Ag-Grid
                    if (! array_key_exists('id', $rowAgrid)) {
                        // Il faut utiliser le mapping, qui permet d'identifier la ligne grâce à l'idrow.
                        if (array_key_exists($rowAgridComplet->{'idrow'}, $idrowmapping)) {
                            // f(x) = y Le Système d'Information va récupéré dans le SystemeInformationRepository un identifiant de ligne, grâce au mapping.
                            $si = $systemeInformationRepository->findOneById($idrowmapping[$rowAgridComplet->{'idrow'}]);
                        }
                    } else {
                        // Si la clef permettant l'identification existe, le Systeme d'Information va le récupérer dans le SystemeInformationRepository.
                        $si = $systemeInformationRepository->findOneById($rowAgrid->{'id'});
                    }
                    // Récupération du Manager, grâce au SystemeInformationController, qui se trouve dans Doctrine; c'est une récupération par héritage.
                    $entityManager = $this->getDoctrine()->getManager();
                    // Le Manager va supprimer le Système d'Information.
                    $entityManager->remove($si);
                    // Mise à jour de la base de données, à partir des objets signalés à Doctrine
                    // Tant qu'elle n'est pas appellée, rien n'est modifié en base.
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
