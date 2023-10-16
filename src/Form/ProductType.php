<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('selected_product_id', IntegerType::class, [
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Please select a product',
                ])
            ]
        ])
        ->add('name', TextType::class, [
            'label' => 'Product Name',
        ])
        ->add('gift', TextType::class, [
            'label' => 'Gift',
        ])
        ->add('price', IntegerType::class, [
            'label' => 'Price',
        ])
        ->add('oldPrice', TextType::class, [
            'label' => 'Old Price',
        ])
        ->add('saving', TextType::class, [
            'label' => 'Savings',
        ]);
}
}
