<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/address')]
class AddressController extends AbstractController
{
    #[Route('/', name: 'app_address_index', methods: ['GET'])]
    public function index(AddressRepository $addressRepository): Response
    {
        return $this->render('address/index.html.twig', [
            'addresses' => $addressRepository->findAll(),
        ]);
    }

	#[Route('/new', name: 'app_address_new', methods: ['GET', 'POST'])]
	public function new(Request $request, EntityManagerInterface $entityManager): Response
	{
		$address = new Address();
		$form = $this->createForm(AddressType::class, $address);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$user = $this->getUser();
			$user->addAddress($address);
			$entityManager->persist($user);
			$entityManager->flush();
			return $this->redirectToRoute('app_address_index', [], Response::HTTP_SEE_OTHER);
		}

		return $this->render('address/new.html.twig', [
			'address' => $address,
			'form' => $form,
		]);
	}

    #[Route('/{id}/edit', name: 'app_address_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Address $address, AddressRepository $addressRepository): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addressRepository->save($address, true);

            return $this->redirectToRoute('app_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('address/edit.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_address_delete')]
    public function delete(Request $request,EntityManagerInterface $entityManager, Address $address): Response
    {
		$address->getUser();
		$entityManager->remove($address);
		$entityManager->flush();

        return $this->redirectToRoute('app_address_index', ['id' => $address->getId()]);
    }
}
