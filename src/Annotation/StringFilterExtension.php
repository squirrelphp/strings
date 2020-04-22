<?php

namespace Squirrel\Strings\Annotation;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Apply StringFilter annotations and filter submitted data accordingly
 */
class StringFilterExtension extends AbstractTypeExtension
{
    /**
     * @var StringFilterProcessor
     */
    private $stringFiltersProcessor;

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
                // Property accessor like the one used by the form component
                $propertyAccessor = PropertyAccess::createPropertyAccessor();

                // Create instance of the form data object, either from empty_data or by instantiating it
                if (isset($options['empty_data']) && $options['empty_data'] instanceof $options['data_class']) {
                    $model = clone $options['empty_data'];
                } else {
                    $model = (new $options['data_class']());
                }

                // Assign values to the model as the form would do it
                foreach ($data as $key => $value) {
                    if ($form->has($key)) {
                        $propertyPath = $form->get($key)->getPropertyPath();

                        if (isset($propertyPath) && $propertyAccessor->isWritable($model, $propertyPath)) {
                            $propertyAccessor->setValue($model, $propertyPath, $value);
                        }
                    }
                }

                // Apply string filters on the object instance
                $this->stringFiltersProcessor->process($model);

                // Copy back the processed data to the array
                foreach ($data as $key => $value) {
                    if ($form->has($key)) {
                        $propertyPath = $form->get($key)->getPropertyPath();

                        if (isset($propertyPath) && $propertyAccessor->isReadable($model, $propertyPath)) {
                            $data[$key] = $propertyAccessor->getValue($model, $propertyPath);
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
