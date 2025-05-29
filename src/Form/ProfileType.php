<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Adresse email (non modifiable si vous le souhaitez, sinon retirez 'disabled')
            ->add('email', EmailType::class, [
                'label'    => 'Adresse email',
            ])
            // Prénom
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            // Nom de famille
            ->add('lastname', TextType::class, [
                'label' => 'Nom de famille',
            ])
            // Ancien mot de passe (vérification)
            ->add('old_password', PasswordType::class, [
                'label'    => 'Mot de passe actuel',
                'mapped'   => false,
                'required' => false,
                'attr'     => ['placeholder' => 'Laissez vide pour ne pas changer'],
            ])
            // Nouveau mot de passe (répété)
            ->add('new_password', RepeatedType::class, [
                'type'            => PasswordType::class,
                'mapped'          => false,
                'required'        => false,
                'first_options'   => [
                    'label' => 'Nouveau mot de passe',
                    'attr'  => ['placeholder' => 'Au moins 8 caractères'],
                ],
                'second_options'  => [
                    'label' => 'Confirmez mot de passe',
                    'attr'  => ['placeholder' => 'Répétez le mot de passe'],
                ],
                'invalid_message' => 'Les deux champs doivent correspondre.',
                'constraints'     => [
                    new Assert\Length([
                        'min'        => 8,
                        'minMessage' => 'Le nouveau mot de passe doit avoir au moins {{ limit }} caractères.',
                        'max'        => 4096,
                    ]),
                ],
            ])
            // Bouton de soumission
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer les modifications',
                'attr'  => ['class' => 'btn btn-outline-success'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
