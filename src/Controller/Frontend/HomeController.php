<?php

namespace App\Controller\Frontend;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('', name: 'app_home', methods: ['GET'])]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('frontend/home/index.html.twig', [
            'livres' => $livreRepository->findBy(
                ['enabled', true],
                ['createdAt', 'ASC'],
                3,
            )
        ]);
    }
}
