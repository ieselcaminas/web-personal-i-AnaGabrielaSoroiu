<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('/page', name: 'app_page')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PageController.php',
        ]);
    }

    #[Route('/', name: 'inicio')]
    public function inicio(): Response {
        return $this->render('index.html.twig');
    }

   /*#[Route('/bebida/insertar', name: 'insertar_bebida')]
    public function insertar(ManagerRegistry $doctrine) {
        $entityManager = $doctrine->getManager();
        foreach($this->bebidas as $b){
            $bebida = new Bebida();
            $bebida->setNombre($b["nombre"]);
            $bebida->setTipo($b["tipo"]);
            $bebida->setAlergenos($b["alergenos"]);
            $entityManager->persist($bebida);
        }
    }*/


}
