<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Commentary;
use App\Form\AnnonceType;
use App\Form\CommentaryType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annonce')]
class AnnonceController extends AbstractController
{
    #[Route('/', name: 'app_annonce_index', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonceRepository->findby(['user' => $this->getUser()->getId()]),
        ]);
    }

    #[Route('/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        $user = $this->getUser();
	        $user->addAnnonce($annonce);
	        $entityManager->persist($user);
	        $entityManager->flush();

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annonce_show', methods: ['GET', 'POST'])]
    public function show(Annonce $annonce, Request $request, EntityManagerInterface $entityManager): Response
    {
		//Ajouter un commentaire
		$commentary = new Commentary();
		$form = $this->createForm(CommentaryType::class, $commentary);
		$form->handleRequest($request);
		//Enregistrer le commentaire
		if ($form->isSubmitted() && $form->isValid()) {
			$commentary->setUser($this->getUser());
			$commentary->setAnnonce($annonce);
			$entityManager->persist($commentary);
			$entityManager->flush();

			return $this->redirectToRoute('app_annonce_show', ['id' => $annonce->getId()], Response::HTTP_SEE_OTHER);
		}
	    //Récupérer les commentaires
	    $user = $commentary->getUser();
	    $commentaries = $annonce->getCommentaries();

        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
	        'commentaries' => $commentaries,
	        'form' => $form,
	        'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->save($annonce, true);

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $annonceRepository->remove($annonce, true);
        }

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }

	#[Route('/toggle/{id}', name: 'annonce_toggle', methods: ['POST'])]
	public function toggle(string $id, Annonce $annonce, AnnonceRepository $annonceRepository, EntityManagerInterface $entityManager): Response
	{
		$annonce = $annonceRepository->find($id);

		if (!$annonce) {
			throw $this->createNotFoundException('Annonce non trouvée');
		}

		$annonce->setIsVisible(!$annonce->isIsVisible());

		$entityManager->persist($annonce);
		$entityManager->flush();

		return $this->redirectToRoute('app_annonce_index');
	}
}
