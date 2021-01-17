<?php

namespace App\Form;

use App\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'label.name',
                'help' => 'Allowed character: A-Z and _',
                'constraints' => [
                    new Regex(['pattern' => '/^[a-zA-Z_]{5,}$/']),
                ],
                'attr' => [
                    'maxlength' => 50,
                ],
            ])
        ;
        // help the user to figure out the allowed name
        $builder->get('name')->addViewTransformer(
            new CallbackTransformer(
                function ($roleName) {
                    if (is_string($roleName)) {
                        $roleName = str_replace(' ', '_', $roleName);
                        $roleName = str_replace('-', '_', $roleName);
                        $roleName = strtoupper($roleName);
                    }
                    return $roleName;
                },
                function ($roleName) {
                    return $roleName;
                }
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
