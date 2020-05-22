<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', null, [
            'label' => "Nom du produit",
            'attr' => [
                'placeholder' => 'Entrer votre produit'
            ],
        ])

        ->add('imageFile', FileType::class, 
        [
            'label' => "Photo du produit",
            'required' => false,
            'attr' => [
                'class' => 'show-for-sr'
            ],
        ])
        ->add('prix', null, [
            'label' => "Prix pc",
            'attr' => [
                'placeholder' => 'Entrer le prix'
            ],
        ])

        ->add('cpu', null, [
            'label' => "Nom du cpu",
            'attr' => [
                'placeholder' => 'Entrer le cpu'
            ],
        ])

        ->add('ram', null, [
            'label' => "Nom de la ram",
            'attr' => [
                'placeholder' => 'Entrer le ram'
            ],
            ])
            ->add('vga', null, [
            'label' => "Nom du vga",
            'attr' => [
                'placeholder' => 'Entrer le vga'
            ],
        ])

            ->add('ecran', null, [
            'label' => "Nom ecran",
            'attr' => [
                'placeholder' => 'Entrer type ecran'
            ],
        ])
        

            ->add('description', null, [
            'label' => "description produit",
            'attr' => [
                'placeholder' => 'Entrer la description'
            ],
        ])

           ->add('stockage', null, [
            'label' => "Espace de stockage",
            'attr' => [
                'placeholder' => 'Entrer espace de stockage'
            ],
        ])

            ->add('enseigne', null, [
            'label' => "Nom enseigne",
            'attr' => [
                'placeholder' => 'Entrer nom enseigne'
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
