<?php

namespace App\Controller;
use App\Repository\CategoriaRepository;
use App\Entity\Categoria;
use App\Entity\Restaurante;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoriaController extends AbstractController
{
    /**
     * @Route("/categoria", name="categoria")
     */
    public function index(CategoriaRepository $cat)
    { 
        $categoria = $this->getDoctrine()->getRepository(Categoria::class)->findAll();
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('categoria/index.html.twig', array('categoria' => $categoria)
        );
    }
}
