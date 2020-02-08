<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\CarsRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MarquesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        return $this->render('home/index.html.twig', [
            'page' => 'home',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {

        $contact = new Contact();

        $form = $this->createForm(ContactType::class);
        return $this->render('home/contact.html.twig', [
            'page' => 'contact',
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/mentions-legals", name="mention")
     */
    public function mentions()
    {
        $page = 'mention';
        return $this->render('home/mention.html.twig', [
            'page' => $page,
        ]);
    }


    /**
     * @Route("/a-propos", name="about")
     */
    public function about()
    {
        $page = 'about';
        return $this->render('home/about.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * @Route("/vehicules", name="vehicules")
     * @param CarsRepository $car
     * @param MarquesRepository $marques
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function vehicules(CarsRepository $car,MarquesRepository $marques, Request $request, PaginatorInterface $paginator):Response
    {
        $marques = $marques->findAll();
        $cars = $paginator->paginate($car->findAllVisible(),
            $request->query->getInt('page', 1),
            6
        );
        $carnb = $car->findAll();
        $page = 'vehicules';
        return $this->render('home/vehicules.html.twig', [
            'page' => $page,
            'marques' => $marques,
            'nbrCar' => $carnb,
            'cars' => $cars,
        ]);
    }

    /**
     * @Route("/marques", name="marques")
     * @param CarsRepository $car
     * @param MarquesRepository $marques
     * @return Response
     */
    public function marques(CarsRepository $car,MarquesRepository $marques):Response
    {
        $marques = $marques->findAll();
        $carnb = $car->findAll();

        $page = 'marques';
        return $this->render('home/marques.html.twig', [
            'page' => $page,
            'marques' => $marques,
            'nbrCar' => $carnb,
        ]);
    }

    /**
     * @Route("/marque/{slug}", name="marque_show")
     * @param $slug
     * @param CarsRepository $car
     * @param MarquesRepository $marques
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function showMarque($slug, CarsRepository $car, MarquesRepository $marques, Request $request, PaginatorInterface $paginator):Response
    {
        $carnb = $car->findAll();

        $marques = $marques->findOneBySlug($slug);
        if (!$marques){
            return $this->redirectToRoute('home');
        }

        $cars = $paginator->paginate($car->findByMarque($marques->getId()),
            $request->query->getInt('page', 1),
            6
        );


        $page = 'vehicule';
        return $this->render('home/marque.html.twig', [
            'page' => $page,
            'marque' => $marques,
            'nbrCar' => $carnb,
            'cars' => $cars,
        ]);
    }

    /**
     * @Route("/show/{slug}", name="page_car")
     * @param $slug
     * @param CarsRepository $car
     * @param MarquesRepository $marquesRepository
     * @param CarsRepository $carsRepository
     * @param Request $request
     * @param ContactNotification $notification
     * @return Response
     */
    public function show($slug ,CarsRepository $car,MarquesRepository $marquesRepository, CarsRepository $carsRepository,Request $request, ContactNotification $notification ):Response
    {
        $marquesListe = $marquesRepository->findAll();
        $cars = $carsRepository->findOneBySlug($slug);
        if (!$cars){
            return $this->redirectToRoute('home');
        }


        $contact = new Contact();

        $carnb = $car->findAll();
        $nbCar = count($carnb);
        $ref = uniqid();
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contact->setCar($cars);
            $contact->setTitre($_POST['contact']['titre']);
            $contact->setPrenom($_POST['contact']['prenom']);
            $contact->setNom($_POST['contact']['nom']);
            $contact->setMail($_POST['contact']['mail']);
            $contact->setTelephone($_POST['contact']['telephone']);
            $contact->setDamande($_POST['contact']['demande']);
            $contact->setMessage($_POST['contact']['message']);
            $notification->notify($contact);
            $this->addFlash(
                "success", "Votre message à bien été envoyer"
            );
            $this->redirectToRoute('page_car',['slug' => $slug]);
        }

        return $this->render('home/show.html.twig', [
            'page' => 'vehicules',
            'nbr' => $nbCar,
            'cars' => $cars,
            'ref' => $ref,
            'form' => $form->createView()
        ]);
    }
}
