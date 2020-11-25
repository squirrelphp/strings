<?php

namespace Squirrel\Strings\Tests\TestForms;

use Squirrel\Strings\Tests\TestClasses\ClassWithPublicTypedProperties;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicTypedPropertiesEmptyDataForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
            ])
            ->add('texts', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
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
            'data_class' => ClassWithPublicTypedProperties::class,
            'empty_data' => new ClassWithPublicTypedProperties(),
        ));
    }
}
