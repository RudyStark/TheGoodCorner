<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
				'label' => 'Nom de l\'annonce',
				'attr' => [
					'placeholder' => 'Nom de l\'annonce'
				]
			])
            ->add('description', TextareaType::class, [
				'label' => 'Description de l\'annonce',
				'attr' => [
					'placeholder' => 'Description de l\'annonce'
				]
            ])
            ->add('price', IntegerType::class, [
				'label' => 'Prix de l\'annonce',
                'attr' => [
					'placeholder' => 'Prix de l\'annonce'
				]
            ])
            ->add('is_visible', CheckboxType::class, [
				'label' => 'Visible',
				'required' => false
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
