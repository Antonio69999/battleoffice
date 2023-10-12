<?php

namespace App\Form;

use App\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adressLine', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('zipcode', TextType::class)
            ->add('phone', TextType::class, [
                'label' => 'Telephone',
            ])
            ->add('adressLine2', TextType::class, [
                'label' => 'Adresse Line 2',
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
            ])
            ->add('country', CountryType::class, [], [
    'constraints' => [new NotBlank()],
])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
