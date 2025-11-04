<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Bebida;
use App\Form\BebidaFormType as BebidaType;
use App\Entity\Cafeteria;
use App\Form\CafeteriaFormType as CafeteriaType;
use Symfony\Component\HttpFoundation\Request;

final class PageController extends AbstractController
{
    //En el inicio de la página aparecerá un listado de cafeteria
    #[Route('/', name: 'inicio')]
    public function inicio(ManagerRegistry $doctrine): Response {
        $cafeteriaRepo = $doctrine->getRepository(Cafeteria::class);
        $cafeterias = $cafeteriaRepo->findAll();

        return $this->render('index.html.twig', 
        ['cafeterias' => $cafeterias]);
    }

    //Creación de un nuevo contacto
    #[Route('/bebida/nueva', name: 'nueva_bebida')]
    public function nuevoBebida(ManagerRegistry $doctrine, Request $request) {
        $bebida = new Bebida();
        $formulario = $this->createForm(BebidaType::class, $bebida);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $bebida = $formulario->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($bebida);
            $entityManager->flush();
            return $this->redirectToRoute('ficha_bebida', 
            ["codigo" => $bebida->getId()]);
        }
        return $this->render('bebida/nuevo.html.twig', 
        array('formulario' => $formulario->createView()));
    }

    //Creación de una cafeteria
   #[Route('/cafeteria/nueva', name: 'nueva_cafeteria')]
    public function nuevoCafe(ManagerRegistry $doctrine, Request $request) {
        $cafeteria = new Cafeteria();
        $formulario = $this->createForm(CafeteriaType::class, $cafeteria);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $cafeteria = $formulario->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($cafeteria);
            $entityManager->flush();

            return $this->redirectToRoute('ficha_cafeteria', 
            ["codigo" => $cafeteria->getId()]);
        }
        return $this->render('cafeteria/nuevo.html.twig', 
        array('formulario' => $formulario->createView()));
    }

    //Buscar una bebida a través del código
    #[Route('/bebida/{codigo?1}', name:'ficha_bebida',
    requirements: ['codigo' => '\d+'])]
    public function fichaBebida(ManagerRegistry $doctrine, $codigo): Response {
        $repositorio = $doctrine->getRepository(Bebida::class);
        $bebida = $repositorio->find($codigo);

        return $this->render('ficha_bebida.html.twig',
        ['bebida' => $bebida]);
    }

    //Buscar una cafeteria a través del código
    #[Route('/cafeteria/{codigo?1}', name:'ficha_cafeteria',
    requirements: ['codigo' => '\d+'])]
    public function fichaCafe(ManagerRegistry $doctrine, $codigo): Response {
        $repositorio = $doctrine->getRepository(Cafeteria::class);
        $cafeteria = $repositorio->find($codigo);

        return $this->render('ficha_cafeteria.html.twig',
        ['cafeteria' => $cafeteria]);
    }

    //Ruta para listar todas las bebidas de la cafeteria seleccionada
    #[Route('/bebida/listar/{codigo?1}', name:'listar_bebida')]
    public function listarCafe(ManagerRegistry $doctrine, $codigo): Response {
        $repositorio = $doctrine->getRepository(Cafeteria::class);
        $cafeteria = $repositorio->find($codigo);
        $bebidaRepo = $doctrine->getRepository(Bebida::class);
        $bebidas = $bebidaRepo->findBy(["cafeteria" => $codigo]);

        return $this->render('bebida/listar.html.twig',
         ['bebidas' => $bebidas,
        'cafeteria' => $cafeteria]);
    }

}
