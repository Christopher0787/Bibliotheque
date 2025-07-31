<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/admin/categorie', name: 'admin_categorie_')]
final class CategorieController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('admin/categorie/index.html.twig', [
            'categories' => $categorieRepository->findBy(
                [],
                ['name' => 'ASC'],
            ),
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        $categorie = new Categorie;

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categorie);
            $em->flush();

            $this->addflash('success', 'La Categorie à été créer');

            return $this->redirectToRoute('admin_categorie_index');
        }
        return $this->render('admin/categorie/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: 'update', methods: ['GET', 'POST'])]
    public function update(
        Categorie $categorie,
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addflash('success', 'La Categorie à été mis à jour');

            return $this->redirectToRoute('admin_categorie_index');
        }
        return $this->render('admin/categorie/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(
        Categorie $categorie,
        Request $request,
        EntityManagerInterface $em,
    ): RedirectResponse {
        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('token_csrf'))) {
            $em->remove($categorie);
            $em->flush();
            $this->addFlash('success', 'La Categorie à bien été supprimer');
        } else {
            $this->addFlash('danger', 'Le token CSRF est invalide');
        }
        return $this->redirectToRoute('admin_categorie_index');
    }
}
