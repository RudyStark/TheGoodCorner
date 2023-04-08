<?php

namespace App\Form;

use App\Entity\Acquisition;
use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AcquisitionType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$user = $options['user'];
		$addresses = $user->getAddresses();

		$builder
			->add('address', EntityType::class, [
				'class' => Address::class,
				'choice_label' => function (Address $address) {
					return sprintf('%s, %s %s', $address->getStreet(), $address->getZip(), $address->getCity());
				},
				'choice_value' => 'id',
				'choices' => $addresses,
				'attr' => [
					'class' => 'form-select',
				],
				'multiple' => false,
			])
			->add('submit', SubmitType::class, [
				'label' => 'Valider',
				'attr' => [
					'class' => 'btn btn-primary',
				],
			]);
	}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            'data_class' => Acquisition::class,
	        'user' => User::class,
        ]);
    }
}
