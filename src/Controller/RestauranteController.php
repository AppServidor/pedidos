<?php

namespace App\Controller;

use App\Entity\Restaurante;
use App\Form\RestauranteType;
use App\Repository\RestauranteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/restaurante")
 */
class RestauranteController extends AbstractController
{
    /**
     * @Route("/", name="restaurante_index", methods={"GET"})
     */
    public function index(RestauranteRepository $restauranteRepository): Response
    {
        return $this->render('restaurante/index.html.twig', [
            'restaurantes' => $restauranteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="restaurante_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $restaurante = new Restaurante();
        $form = $this->createForm(RestauranteType::class, $restaurante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($restaurante);
            $entityManager->flush();

            return $this->redirectToRoute('restaurante_index');
        }

        return $this->render('restaurante/new.html.twig', [
            'restaurante' => $restaurante,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="restaurante_show", methods={"GET"})
     */
    public function show(Restaurante $restaurante): Response
    {
        return $this->render('restaurante/show.html.twig', [
            'restaurante' => $restaurante,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="restaurante_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Restaurante $restaurante): Response
    {
        $form = $this->createForm(RestauranteType::class, $restaurante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('restaurante_index');
        }

        return $this->render('restaurante/edit.html.twig', [
            'restaurante' => $restaurante,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="restaurante_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Restaurante $restaurante): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restaurante->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($restaurante);
            $entityManager->flush();
        }

        return $this->redirectToRoute('restaurante_index');
    }
}
