<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\FormOrderType;
use App\Repository\PaymentRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;

class LandingPageController extends AbstractController
{
    #[Route('/', name: 'landing_page', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository, PaymentRepository $paymentRepository): Response
    {
        $order = new Order();
        $products = $productRepository->findAll();

        $form = $this->createForm(FormOrderType::class, $order);

        $form->handleRequest($request);

        //GUZZLE

        $client = new Client([
            'base_uri' => 'http://httpbin.org',
            'timeout' => 2.0,
        ]);

        // $jsonData = ['order' => $jsonOrder];
        
        $token = "mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX";

        $header = [
            'Authorization' => "Bearer" . $token,
        ];

        // $response = $client->request('POST', 'test');

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
         
            //PERSIST
            // $entityManager->persist($client);
            $entityManager->persist($order);  
            $entityManager->flush();

            $jsonOrder = [
                'status' => $order->getStatus(),
                'client' => [
                    'firstname' => $order->getClient()->getFirstname(),
                    'lastname' => $order->getClient()->getLastname(),
                    'email' => $order->getClient()->getEmail(),
                ],
                'addresses' => [
                    'billing' => [
                        'address_line1' => $order->getBilingAdress()->getAdressLine(),
                        'address_line2' => $order->getBilingAdress()->getAdressLine2(),
                        'city' => $order->getBilingAdress()->getCity(),
                        'zipcode' => $order->getBilingAdress()->getZipcode(),
                        'country' => $order->getBilingAdress()->getCountry()->getCountry(),
                        'phone' => $order->getBilingAdress()->getPhone(),
                    ],
                    'shipping' => [
                        'address_line1' => $order->getShippingAdress()->getAdressLine(),
                        'address_line2' => $order->getShippingAdress()->getAdressLine2(),
                        'city' => $order->getShippingAdress()->getCity(),
                        'zipcode' => $order->getShippingAdress()->getZipcode(),
                        'country' => $order->getShippingAdress()->getCountry()->getCountry(),
                        'phone' => $order->getShippingAdress()->getPhone(),
                    ],
                ],
                'payment_method' => [
                    'method' => $order->getPayment()->getMethod(),
                ],
                'products' => $products->getName(), 
            ];
            
            $jsonString = json_encode(['order' => $jsonOrder]);

        }
            //return $this->redirectToRoute('confirmation');
        }

        return $this->render('landing_page/index_new.html.twig', [
            'form' => $form->createView(),
            'products'=>$productRepository->findAll(),
            
        ]);
    }
}
