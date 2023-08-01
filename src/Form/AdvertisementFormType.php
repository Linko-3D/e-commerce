<?php
// AdvertisementFormType.php

namespace App\Form;

use App\Entity\Advertisement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Add this import for file uploads
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual; // Add this import for the constraint

class AdvertisementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('content', TextType::class, [
                'label' => 'Description'
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('imageFile', FileType::class, [ // Use FileType::class for file uploads
                'label' => 'Image', // Add a label for the file input
                'required' => false, // Set 'required' to false to allow empty uploads
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'Le prix doit être supérieur ou égal à 0.'
                    ]),
                ],
            ])
            ->add('region', IntegerType::class, [
                'label' => 'Département'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advertisement::class,
        ]);
    }
}
