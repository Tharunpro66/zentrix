<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Company Name',
                'attr' => ['class' => 'form-control mb-3'], // Add Bootstrap class
                'required' => true,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Is Active?',
                'required' => false, // Checkboxes are false by default if not checked
                'attr' => ['class' => 'form-check-input mb-3'], // Bootstrap class for styling
                'label_attr' => ['class' => 'form-check-label'],
            ])
            // 'createdAt' is usually managed automatically and not part of the form
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}