<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FormOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adress', AdressType::class)
            ->add('client', ClientType::class)
            ->add('product', ProductType::class)
            ->add('id_client')
            ->add('idPayment')
            ->add('idBilingAdress')
            ->add('idShippingAdress')
            ->add('products')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
