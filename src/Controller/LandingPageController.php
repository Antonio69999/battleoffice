<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\FormOrderType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{
    #[Route('/', name: 'landing_page', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository): Response
    {
        $order = new Order();
        $products = $productRepository->findAll();

        $form = $this->createForm(FormOrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $selectedProductID = $request->request->get('selected_product_id');
            $products = $productRepository->find($selectedProductID);
            
            
            $order->addProduct($products);
            //SET
            $order->setStatus('WAITING');
            // $country->setCountry($country);

            //PERSIST
            // $entityManager->persist($client);
            $entityManager->persist($order);  
            $entityManager->flush();

            //return $this->redirectToRoute('confirmation');
        }

        return $this->render('landing_page/index_new.html.twig', [
            'form' => $form,
            'products' => $productRepository->findAll(),
        ]);
    }
}
