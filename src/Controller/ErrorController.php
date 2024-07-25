<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ErrorController extends AbstractController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    #[Route('/error403', name: 'error403')]
    public function show403(): Response
    {
        return new Response($this->twig->render('bundles/TwigBundle/Exception/error403.html.twig'), 403);
    }
}
