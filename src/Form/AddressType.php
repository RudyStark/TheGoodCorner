<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
	        ->add('street', TextType::class, [
		        'label' => 'street',
		        'attr' => [
			        'class' => 'form-control rounded-pill col-4',
			        'placeholder' => 'Rue',
		        ]
	        ])
	        ->add('city', TextType::class, [
		        'label' => 'city',
		        'attr' => [
			        'class' => 'form-control rounded-pill col-4',
			        'placeholder' => 'Ville',
		        ]
	        ])
	        ->add('zip', IntegerType::class, [
		        'label' => 'zip',
		        'attr' => [
			        'class' => 'form-control rounded-pill col-4',
			        'placeholder' => 'Code postal',
		        ]
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
