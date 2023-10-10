<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\FormOrderType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    private function createOrderForm(): Form
    {
        $order = new Order();
        return $this->createForm(FormOrderType::class, $order);
    }

    #[Route('/', name: 'landing_page', methods: ['GET', 'POST'])]

    public function index(Request $request) :Response
    {
        $form = $this->createOrderForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $order = $form->getData();
                dd($order);
                $this->entityManager->persist($order);
                $this->entityManager->flush();
            }
        }

        return $this->render('landing_page/index_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('confirmation', name: 'confirmation')]
    public function confirmation(Request $request): Response
    {
        $form = $this->createOrderForm();
        return $this->render('landing_page/confirmation.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
