<?php

namespace App\Controller;

use App\Entity\Acquisition;
use App\Entity\Annonce;
use App\Form\AcquisitionType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/acquisition')]
class AcquisitionController extends AbstractController
{
    #[Route('/index', name: 'app_acquisition')]
    public function index(): Response
    {
        return $this->render('acquisition/index.html.twig', [
            'controller_name' => 'AcquisitionController',
        ]);
    }

	#[Route('/order-details/{id}', name: 'app_acquisition_order-details')]
	public function orderDetails(AnnonceRepository $annonceRepository, Request $request, EntityManagerInterface $entityManager): Response
	{
		//on récupère l'annonce
		$annonce = $annonceRepository->findby(['user' => $this->getUser()->getId()]);
		//on récupère l'utilisateur
		$user = $this->getUser();

		$acquisition = new Acquisition();
		$form = $this->createForm(AcquisitionType::class, $acquisition, ['user' => $user]);
		$form->handleRequest($request);

		//Enregistrer l'acquisition
		if ($form->isSubmitted() && $form->isValid()) {
			$acquisition->setUser($this->getUser());
			$acquisition->setAnnonce($annonce);
			$entityManager->persist($acquisition);
			$entityManager->flush();

			return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);
		}


		return $this->render('acquisition/index.html.twig', [
			'form' => $form,
			'annonce' => $annonce,
			'user' => $user,
		]);
	}
}
