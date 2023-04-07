<?php

namespace App\Controller;

use App\Entity\Bank;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
	/**
	 * @param User $user
	 * @param Bank $bank
	 * @return Response
	 */
	#[Route('/profil', name: 'app_user_profile')]
	public function profile(): Response
	{
		// On récupère l'utilisateur connecté
		$user = $this->getUser();

		// On récupère le compte bancaire de l'utilisateur
		$bank = $this->getUser()->getBank();

		//On récupère les addresses de l'utilisateur
		$addresses = $this->getUser()->getAddresses();

		return $this->render('user/profile.html.twig', [
			'user' => $user,
			'bank' => $bank,
			'addresses' => $addresses
		]);
	}
}
