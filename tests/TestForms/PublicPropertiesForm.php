<?php

namespace Squirrel\Strings\Tests\TestForms;

use Squirrel\Strings\Tests\TestClasses\ClassWithPublicProperties;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicPropertiesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
            ])
            ->add('text', TextType::class, [
                'label' => false,
            ])
        ;
    }

    /**
     * Set class where our form data comes from
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ClassWithPublicProperties::class,
        ));
    }
}
