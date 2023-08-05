<?php
// AdvertisementFormType.php

namespace App\Form;

use App\Entity\Advertisement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Image; // Add this import for image validation
use Symfony\Component\Validator\Context\ExecutionContextInterface; // Add this import for ExecutionContextInterface
use Symfony\Component\Validator\Constraints\Length; // Add this import for length validation

class AdvertisementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isNewAdvertisement = $builder->getData() instanceof Advertisement && null === $builder->getData()->getId();

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le titre doit faire au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('content', TextType::class, [
                'label' => 'Description',
                'constraints' => [
                    new Length([
                        'min' => 100,
                        'minMessage' => 'La description doit faire au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'La ville doit faire au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image',
                'required' => $isNewAdvertisement, // Image is required when creating a new ad
                'constraints' => [
                    new Image([ // Add image validation constraint
                        'maxSize' => '5M', // Set the maximum allowed image size
                    ]),
                ],
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'Le prix doit être supérieur ou égal à 0.',
                    ]),
                ],
            ])
            ->add('region', IntegerType::class, [
                'label' => 'Département',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advertisement::class,
        ]);
    }
}
