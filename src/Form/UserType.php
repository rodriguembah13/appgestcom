<?php

namespace App\Form;

use App\Entity\User;
use App\Security\RoleService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @var RoleService
     */
    private $roles;
    public function __construct(RoleService $roles)
    {
        $this->roles = $roles;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('plainPassword')
            ->add('roles', ChoiceType::class, [
                'choices' => $this->getRoles(),
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }
    public function getRoles()
    {
        $roles = [];
        foreach ($this->roles->getAvailableNames() as $name) {
            $roles[$name] = strtoupper($name);
        }

        if (isset($roles[User::DEFAULT_ROLE])) {
            unset($roles[User::DEFAULT_ROLE]);
        }

        return $roles;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
