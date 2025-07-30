<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email:',
                'attr' => [
                    'placeholder' => 'Entrez votre email',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'required' => $options['isAdmin'] ? false : true,
                'mapped' => false, // Le mot de passe n'est pas mappé à l'entité User
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'first_options' => [
                    'label' => 'Mot de passe:',
                    'attr' => [
                        'placeholder' => '************',
                        'autocomplete' => 'new-password',
                    ],
                    'constraints' => [
                        new Assert\NotCompromisedPassword(message: 'Ce mot de passe a été compromis dans une fuite de données. Veuillez en choisir un autre.'),
                        new Assert\Regex(
                            pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/',
                            message: 'Le mot de passe doit contenir au moins 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.'
                        ),
                        new Assert\Length(
                            max: 4096,
                            maxMessage: 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.'
                        )
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe:',
                    'attr' => [
                        'placeholder' => '************',
                        'autocomplete' => 'new-password',
                    ],
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom:',
                'attr' => [
                    'placeholder' => 'John',
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom:',
                'attr' => [
                    'placeholder' => 'Doe',
                ]
            ])
        ;

        if ($options['isAdmin']) {
            $builder
                ->add('roles', ChoiceType::class, [
                    'label' => 'Rôles:',
                    'choices' => [
                        'Utilisateur' => 'ROLE_USER',
                        'Administrateur' => 'ROLE_ADMIN',
                        'Éditeur' => 'ROLE_EDITOR',
                    ],
                    'expanded' => true,
                    'multiple' => true,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'isAdmin' => false,
        ]);
    }
}
