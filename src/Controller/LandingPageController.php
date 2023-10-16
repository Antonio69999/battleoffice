<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\FormOrderType;
use App\Repository\PaymentRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class LandingPageController extends AbstractController
{
    #[Route('/', name: 'landing_page', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository, PaymentRepository $paymentRepository): Response
    {
        $order = new Order();
        $products = $productRepository->findAll();

        $form = $this->createForm(FormOrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $selectedProductID = $request->request->get('selected_product_id');

            if ($selectedProductID === null) {
                $this->addFlash('error', 'Please select a product');
            } else {
            $products = $productRepository->find($selectedProductID);


            $paymentMethod = $request->request->get('payment');
            $payment = $paymentRepository->findOneBy(['method' => $paymentMethod]);
            
            
            // les methodes qui set
            $order->addProduct($products);
            $order->setPayment($payment);
            $order->setStatus('WAITING');
            // $country->setCountry($country);

            //PERSIST
            // $entityManager->persist($client);
            $entityManager->persist($order);  
            $entityManager->flush();

            //return $this->redirectToRoute('confirmation');
            }
        }

        return $this->render('landing_page/index_new.html.twig', [
            'form' => $form->createView(),
            'products'=>$productRepository->findAll(),
            
        ]);
    }

    #[Route('/serialize-order', name: 'serialize_order', methods: ['GET'])]
    public function serializeOrder(SerializerInterface $serializer)
    {
        $order = new Order();

        $normalizers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];
    
        $serializer = new \Symfony\Component\Serializer\Serializer($normalizers, $encoders);
    
        $json = $serializer->serialize($order, 'json');

        return new JsonResponse($json, 200, [], true);
    }
}
