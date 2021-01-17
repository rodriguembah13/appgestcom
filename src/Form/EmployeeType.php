<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\User;
use App\Security\RoleService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
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
            ->add('firstname')
            ->add('lastname')
            ->add('phone')
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'property_path' => 'birthday',
            ])
            ->add('email', TextType::class, [
                'mapped' => false,
            ])
            ->add('username', TextType::class, [
                'mapped' => false,
            ])
            ->add('password', TextType::class, [
                'mapped' => false,
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => $this->getRoles(),
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
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
            'data_class' => Employee::class,
        ]);
    }
}
