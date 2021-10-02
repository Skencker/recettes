<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Recette;
use App\Form\SearchType as FormSearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecetteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/recettes', name: 'recettes')]
    public function index(Request $request): Response
    {
        $recettes = $this->entityManager->getRepository((Recette::class))->findAll();

        $search = new Search();
        $form = $this->createForm(FormSearchType::class, $search);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {

            $recettes = $this->entityManager->getRepository((Recette::class))->findWithSearch($search);
        }

        return $this->render('recette/index.html.twig', [
            'recettes' => $recettes,
            'form' => $form->createView()
        ]);
    }

    #[Route('/recette/{slug}', name: 'recette')]
    public function show($slug): Response
    {
        $recette = $this->entityManager->getRepository((Recette::class))->findOneBySlug($slug);
        return $this->render('recette/recette.html.twig', [
            'recette' => $recette
        ]);
    }
}
