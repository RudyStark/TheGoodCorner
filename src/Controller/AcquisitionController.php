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
		$annonce = $annonceRepository->findOneBy(['id' => $request->attributes->get('id')]);
		//on récupère l'utilisateur
		$user = $this->getUser();

		//Ajouter une acquisition
		$acquisition = new Acquisition();
		$form = $this->createForm(AcquisitionType::class, $acquisition, ['user' => $user]);
		$form->handleRequest($request);

		//Enregistrer l'acquisition
		if ($form->isSubmitted() && $form->isValid()) {
			$acquisition->setUser($this->getUser());
			$acquisition->setAnnonce($annonce);

			//On déduis le montant de l'annonce du solde de l'utilisateur sinon on envoi un message d'erreur si le montant n'est pas suffisant
			if ($user->getBank() >= $annonce->getPrice()) {
				$userBank = $user->getBank();
				$newBankAmount = $userBank->getAmount() - $annonce->getPrice();
				$userBank->setAmount($newBankAmount);
				$user->setBank($userBank);
				$entityManager->persist($user);
			} else {
				$this->addFlash('danger', 'Vous n\'avez pas assez d\'argent sur votre compte pour effectuer cette transaction');
				return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);
			}
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
