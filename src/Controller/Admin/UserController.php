<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/users', name: "admin_users_")]
final class UserController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'update', methods: ['GET', 'POST'])]
    public function update(
        User $user,
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
    ): Response {
        $form = $this->createForm(UserType::class, $user, [
            'isAdmin' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($plainPassword = $form->get('password')->getData()) {
                $user->setPassword(
                    $hasher->hashPassword(
                        $user,
                        $plainPassword,
                    )
                );
            }

            $em->flush();

            $this->addFlash('success', 'Utilisateur mis à jour avec succés');

            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/users/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(User $user, Request $request, EntityManagerInterface $em): RedirectResponse
    {

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('token_csrf'))) {
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succés');
        } else {
            $this->addFlash('danger', 'Le token CSRF est invalide. suppression impossible.');
        }

        return $this->redirectToRoute('admin_users_index');
    }
}
