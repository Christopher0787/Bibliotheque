<?php

namespace App\Controller\Admin;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/livres', name: 'admin_livres_')]
final class LivreController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('admin/livres/index.html.twig', [
            'livres' => $livreRepository->findBy(
                [],
                ['createdAt' => 'ASC'],
            ),
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        $livre = new Livre;

        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $livre->setAuthor($this->getUser());
            $em->persist($livre);
            $em->flush();

            $this->addFlash('success', 'Le Livre à été créer avec succès');
            return $this->redirectToRoute('admin_livres_index');
        }
        return $this->render('admin/livres/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: 'update', methods: ['GET', 'POST'])]
    public function update(
        Livre $livre,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Le Livre à été mis à jour avec succès');
            return $this->redirectToRoute('admin_livres_index');
        }
        return $this->render('admin/livres/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(
        Livre $livre,
        Request $request,
        EntityManagerInterface $em
    ): RedirectResponse {
        if ($this->isCsrfTokenValid('delete' . $livre->getId(), $request->request->get('token_csrf'))) {
            $em->remove($livre);
            $em->flush();

            $this->addFlash('success', 'Le Livre à été supprimé avec succés');
        } else {
            $this->addFlash('danger', 'Le token CSRF est invalide.');
        }

        return $this->redirectToRoute('admin_livres_index');
    }
}
