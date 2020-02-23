<?php

namespace App\Controller;

use App\Entity\Pedidos;
use App\Entity\Producto;
use App\Form\PedidosType;
use App\Repository\PedidosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/pedidos")
 */
class PedidosController extends AbstractController
{
    /**
     * @Route("/", name="pedidos_index", methods={"GET"})
     */
    public function index(PedidosRepository $pedidosRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('pedidos/index.html.twig', [
            'pedidos' => $pedidosRepository->findAll(),
        ]);
    }
    /**
     * @Route("/mostrarcarrito", name="mostrarcarrito")
     */
    public function mostrarCarrito(SessionInterface $session){
        $carrito=$session->get('carrito');
        return $this->render('pedidos/carrito.html.twig', array('carrito' => $carrito)
        );        
    }
     /**
     * @Route("/nuevo", name="nuevo",  methods={"GET","POST"})
     */
    public function realizarPedido(SessionInterface $session, \Swift_Mailer $mailer)
    {
        $carrito = $session->get('carrito');
        //var_dump($carrito);
        $restaurante=$this->getUser();
        
        $pedido = new Pedidos();
        $productosMail = array();
        foreach ($carrito as $key => $value) {
            $producto = $this->getDoctrine()->getRepository(Producto::class)->find($key);
            $productosMail = $producto;
            $pedido->addProducto($producto);
        }
        $pedido->setFecha( new \DateTime());
        $pedido->setRestaurante($restaurante);
        $pedido->setEnviado(1);

        $this->getDoctrine()->getManager()->persist($pedido);
        $this->getDoctrine()->getManager()->flush();

        $session->set('carrito',array());


        $message = (new \Swift_Message('Pedido'))
        ->setFrom('programandoqueesgerundio@gmail.com')
        ->setTo($restaurante->getCorreo())
        ->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'emails/registration.html.twig',array('productos'=>$productosMail)
            ),
            'text/html'
        )
    ;

    $mailer->send($message);
        return $this->render('pedidos/nuevo.html.twig');
    }
    /**
     * @Route("/new", name="pedidos_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $pedido = new Pedidos();
        $form = $this->createForm(PedidosType::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pedido);
            $entityManager->flush();

            return $this->redirectToRoute('pedidos_index');
        }

        return $this->render('pedidos/new.html.twig', [
            'pedido' => $pedido,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pedidos_show", methods={"GET"})
     */
    public function show(Pedidos $pedido): Response
    {
        return $this->render('pedidos/show.html.twig', [
            'pedido' => $pedido,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pedidos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pedidos $pedido): Response
    {
        $form = $this->createForm(PedidosType::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pedidos_index');
        }

        return $this->render('pedidos/edit.html.twig', [
            'pedido' => $pedido,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pedidos_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pedidos $pedido): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pedido->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pedido);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pedidos_index');
    }

    /**
     * @Route("/carrito", name="carrito",  methods={"GET","POST"})
     */
    public function addCarrito(SessionInterface $session)
    {
        var_dump($_POST);
        $codProd = $_POST['id'];
        $cantidad = $_POST['cantidad'];
        $nombre = $_POST['nombre'];
        
        $carrito = $session->get('carrito');
        if (is_null($carrito)) {
            $carrito = array();
        }

        $carrito[$codProd]['nombre']=$nombre;
        if (isset($carrito[$codProd]['cantidad'])) {

            var_dump($carrito[$codProd]);
            $carrito[$codProd]['cantidad'] += $cantidad;
            
        } else {
            $carrito[$codProd]['cantidad'] = $cantidad;
        }
        
        $session->set('carrito', $carrito);

        return $this->render('pedidos/carrito.html.twig', array('carrito' => $carrito)
        );
    }
     /**
     * @Route("/carritodel", name="carritodel",  methods={"GET","POST"})
     */
    public function removeCarrito(SessionInterface $session)
    {
        var_dump($_POST);
        $codProd = $_POST['id'];
        $cantidad = $_POST['cantidad'];
        $carrito = $session->get('carrito');

        if(isset($carrito[$codProd]['cantidad']) && $carrito[$codProd]['cantidad'] < $cantidad){
            $carrito[$codProd]['cantidad']=0;
        }else if (isset($carrito[$codProd])) {
            $carrito[$codProd]['cantidad'] -= $cantidad;
            
        } 
        $session->set('carrito', $carrito);

        return $this->render('pedidos/carrito.html.twig', array('carrito' => $carrito)
        );
    }

}
