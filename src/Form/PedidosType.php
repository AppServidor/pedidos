<?php

namespace App\Form;

use App\Entity\Restaurante;
use App\Entity\Pedidos;
use App\Entity\Producto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PedidosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha')
            ->add('enviado')
            ->add('productos', EntityType::class,[
                'class' => Producto::class,
                'choice_label' => 'nombre',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('restaurante', EntityType::class,[
                'class' => Restaurante::class,
                'choice_label' => 'nombre',
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pedidos::class,
        ]);
    }
}
