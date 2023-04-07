<?php

namespace App\Controller;

use App\Entity\Bank;
use App\Form\BankType;
use App\Repository\BankRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/bank')]
class BankController extends AbstractController
{
    #[Route('/', name: 'app_bank_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, BankRepository $bankRepository): Response
    {
	    $bank = new Bank();
		$user = $this->getUser();

	    $form = $this->createForm(BankType::class, $bank);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
		    // récupérer le montant de la banque de l'utilisateur
		    $userBank = $user->getBank();

		    // ajouter le montant à la banque de l'utilisateur
		    $userBank->setAmount($userBank->getAmount() + $bank->getAmount());

		    $entityManager->persist($userBank);
		    $entityManager->flush();

		    return $this->redirectToRoute('app_bank_index', [], Response::HTTP_SEE_OTHER);
	    }

	    return $this->render('bank/index.html.twig', [
		    'banks' => $bankRepository->findAll(),
		    'form' => $form->createView(),
	    ]);
    }
}
