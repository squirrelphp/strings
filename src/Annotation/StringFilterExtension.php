<?php

namespace Squirrel\Strings\Annotation;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Apply StringFilter annotations and filter submitted data accordingly
 */
class StringFilterExtension extends AbstractTypeExtension
{
    private StringFilterProcessor $stringFiltersProcessor;

    public function __construct(StringFilterProcessor $stringFiltersProcessor)
    {
        $this->stringFiltersProcessor = $stringFiltersProcessor;
    }

    /**
     * Filter data before submission
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Modify data before it is submitted/validated
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
            // Retrieve submitted content
            $data = $event->getData();
            $form = $event->getForm();

            // We only want form elements with a data class and an array of values
            if (\is_array($data) && \strlen($options['data_class']) > 0) {
                // Create instance of the form data object, either from empty_data or by instantiating it
                if (isset($options['empty_data']) && $options['empty_data'] instanceof $options['data_class']) {
                    $model = clone $options['empty_data'];
                } else {
                    $model = (new $options['data_class']());
                }

                // Assign values to the model only for direct properties
                foreach ($data as $key => $value) {
                    if (\property_exists($model, $key)) {
                        $reflectionProperty = new \ReflectionProperty($model, $key);
                        $reflectionPropertyType = $reflectionProperty->getType();

                        // @codeCoverageIgnoreStart
                        if (
                            PHP_VERSION_ID >= 80000
                            && $reflectionPropertyType instanceof \ReflectionUnionType
                        ) {
                            $reflectionTypes = $reflectionPropertyType->getTypes();
                        } else {
                            $reflectionTypes = [$reflectionPropertyType];
                        }
                        // @codeCoverageIgnoreEnd

                        $hasSupportedType = false;

                        if (!$reflectionProperty->hasType()) {
                            $hasSupportedType = true;
                        }

                        foreach ($reflectionTypes as $reflectionType) {
                            if (!($reflectionType instanceof \ReflectionNamedType)) {
                                continue;
                            }

                            if (
                                $reflectionType->getName() === 'string'
                                || $reflectionType->getName() === 'array'
                            ) {
                                $hasSupportedType = true;
                                break;
                            }
                        }

                        if ($hasSupportedType === true) {
                            $reflectionProperty->setAccessible(true);
                            $reflectionProperty->setValue($model, $value);
                        }
                    }
                }

                // Apply string filters on the object instance
                $this->stringFiltersProcessor->process($model);

                // Copy back the processed data to the array
                foreach ($data as $key => $value) {
                    if (\property_exists($model, $key)) {
                        $reflectionProperty = new \ReflectionProperty($model, $key);
                        $reflectionPropertyType = $reflectionProperty->getType();

                        // @codeCoverageIgnoreStart
                        if (
                            PHP_VERSION_ID >= 80000
                            && $reflectionPropertyType instanceof \ReflectionUnionType
                        ) {
                            $reflectionTypes = $reflectionPropertyType->getTypes();
                        } else {
                            $reflectionTypes = [$reflectionPropertyType];
                        }
                        // @codeCoverageIgnoreEnd

                        $hasSupportedType = false;

                        if (!$reflectionProperty->hasType()) {
                            $hasSupportedType = true;
                        }

                        foreach ($reflectionTypes as $reflectionType) {
                            if (!($reflectionType instanceof \ReflectionNamedType)) {
                                continue;
                            }

                            if (
                                $reflectionType->getName() === 'string'
                                || $reflectionType->getName() === 'array'
                            ) {
                                $hasSupportedType = true;
                                break;
                            }
                        }

                        if ($hasSupportedType === true) {
                            $reflectionProperty->setAccessible(true);
                            $data[$key] = $reflectionProperty->getValue($model);
                        }
                    }
                }

                // Set processed data for submission
                $event->setData($data);
            }
        }, 100); // Set priority 100 to make sure it is executed early on
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
