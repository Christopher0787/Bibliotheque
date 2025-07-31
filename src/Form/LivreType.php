<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Livre;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Entrez la titre du livre',
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu:',
                'attr' => [
                    'placeholder' => 'Entrez le contenue du livre',
                    'row' => 10,
                ]
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'Archiver le livre',
                'required' => false,
            ])
            // ->add('author', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'fullName',
            //     'multiple' => false,
            //     'placeholder' => 'Sélectionner un auteur',
            // ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'multiple' => true,
                'placeholder' => 'Sélectionner une categorie',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
