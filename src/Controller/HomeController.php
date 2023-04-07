<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(AnnonceRepository $annonceRepository): Response
    {
		$annonces = $annonceRepository->findAll();
        return $this->render('home/index.html.twig', [
            'annonces' => $annonces
        ]);
    }
}
