<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Bebida;
use App\Form\BebidaFormType as BebidaType;
use App\Entity\Cafeteria;
use App\Form\CafeteriaFormType as CafeteriaType;
use Symfony\Component\HttpFoundation\Request;

final class PageController extends AbstractController
{
    //Página para iniciar sesión
    #[Route('/inicio', name: 'index')]
    public function index(): Response {
        return $this->render('inicio.html.twig');
    }

    //En el inicio de la página aparecerá un listado de cafeteria
    #[Route('/', name: 'inicio')]
    public function inicio(ManagerRegistry $doctrine): Response {
        $cafeteriaRepo = $doctrine->getRepository(Cafeteria::class);
        $cafeterias = $cafeteriaRepo->findAll();

        return $this->render('index.html.twig', 
        ['cafeterias' => $cafeterias]);
    }

    //Creación de una nueva bebida
    #[Route('/bebida/nueva/{codigo}', name: 'nueva_bebida')]
    public function nuevoBebida(ManagerRegistry $doctrine, Request $request, $codigo = null) {
        //Si no está logeado, nos redirige a la página inicio
        if (!$this->getUser()) {
            return $this->redirectToRoute('index');
        }
        
        $bebida = new Bebida();
        $cafeteria = null;

        //Pilla el código de la cafeteria en la que se encuentra de esta manera
        //cuando se crea una nueva sale el nombre de la cafeteria en la que estás
        if($codigo) {
            $cafeteriaRepo = $doctrine->getRepository(Cafeteria::class);
            $cafeteria = $cafeteriaRepo->find($codigo);

            if ($cafeteria) {
                $bebida->setCafeteria($cafeteria);
            }
        }

        $formulario = $this->createForm(BebidaType::class, $bebida);
        $formulario->handleRequest($request);
        
        if($formulario->isSubmitted() && $formulario->isValid()){
            $entityManager = $doctrine->getManager();
            $entityManager->persist($bebida);
            $entityManager->flush();
            return $this->redirectToRoute('listar_bebida', 
            ["codigo" => $cafeteria->getId()]);
        }
        return $this->render('bebida/nuevo.html.twig', 
        ['formulario' => $formulario->createView(),
        'cafeteria' => $cafeteria]);
    }

    //Editar los datos de una bebida
    #[Route('/bebida/editar/{codigo}', name: 'editar_bebida')]
    public function editarBebida(ManagerRegistry $doctrine, $codigo, Request $request): Response {
        //Si no está logeado, nos redirige a la página inicio
        if (!$this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Bebida::class);
        $bebida = $repositorio->find($codigo);

        if(!$bebida) {
            throw $this->createNotFoundException("No se ha encontrado la bebida");
        }

        $formulario = $this->createForm(BebidaType::class, $bebida);
        $formulario->handleRequest($request);

        if($bebida) {            
            if($formulario->isSubmitted() && $formulario->isValid()){
                $entityManager->flush();

                return $this->redirectToRoute('listar_bebida');
            }
        }
        return $this->render('bebida/editar.html.twig', 
        ['formulario' => $formulario->createView(),
        'bebida' => $bebida,
        'cafeteria' => $bebida->getCafeteria()]);
    }

    //Eliminación de una bebida
    #[Route('/bebida/eliminar/{codigo}', name: 'eliminar_bebida')]
    public function eliminarBebida(ManagerRegistry $doctrine, $codigo) {
        //Si no está logeado, nos redirige a la página inicio
        if (!$this->getUser()) {
            return $this->redirectToRoute('index');
        }
        
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Bebida::class);
        $bebida = $repositorio->find($codigo);

        if(!$bebida) {
            throw $this->createNotFoundException("No se ha encontrado la cafeteria");
        }

        $cafeteria = $bebida->getCafeteria();

        $entityManager->remove($bebida);
        $entityManager->flush();

        return $this->redirectToRoute('listar_bebida', 
        ['codigo' => $cafeteria->getId()]);
    }

    //Creación de una cafeteria
   #[Route('/cafeteria/nueva', name: 'nueva_cafeteria')]
    public function nuevoCafe(ManagerRegistry $doctrine, Request $request) {
        //Si no está logeado, nos redirige a la página inicio
        if (!$this->getUser()) {
            return $this->redirectToRoute('index');
        }
        
        $cafeteria = new Cafeteria();
        $formulario = $this->createForm(CafeteriaType::class, $cafeteria);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $cafeteria = $formulario->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($cafeteria);
            $entityManager->flush();

            return $this->redirectToRoute('inicio', 
            ["codigo" => $cafeteria->getId()]);
        }
        return $this->render('cafeteria/nuevo.html.twig', 
        array('formulario' => $formulario->createView()));
    }

    //Editar los datos de una cafeteria
    #[Route('/cafeteria/editar/{codigo}', name: 'editar_cafeteria')]
    public function editarCafe(ManagerRegistry $doctrine, $codigo, Request $request): Response {
        //Si no está logeado, nos redirige a la página inicio
        if (!$this->getUser()) {
            return $this->redirectToRoute('index');
        }
        
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Cafeteria::class);
        $cafeteria = $repositorio->find($codigo);

        if(!$cafeteria) {
            throw $this->createNotFoundException("No se ha encontrado la cafeteria");
        }

        $formulario = $this->createForm(CafeteriaType::class, $cafeteria);
        $formulario->handleRequest($request);

        if($cafeteria) {            
            if($formulario->isSubmitted() && $formulario->isValid()){
                $entityManager->flush();

                return $this->redirectToRoute('inicio');
            }
        }
        return $this->render('cafeteria/editar.html.twig', 
        ['formulario' => $formulario->createView(),
        'cafeteria' => $cafeteria]);
    }

    //Eliminación de una cafeteria
    #[Route('/cafeteria/eliminar/{codigo}', name: 'eliminar_cafeteria')]
    public function eliminarCafe(ManagerRegistry $doctrine, $codigo) {
        //Si no está logeado, nos redirige a la página inicio
        if (!$this->getUser()) {
            return $this->redirectToRoute('index');
        }
        
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Cafeteria::class);
        $cafeteria = $repositorio->find($codigo);

        if(!$cafeteria) {
            throw $this->createNotFoundException("No se ha encontrado la cafeteria");
        }

        $entityManager->remove($cafeteria);
        $entityManager->flush();

        return $this->redirectToRoute('inicio');
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
