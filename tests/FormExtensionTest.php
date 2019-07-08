<?php

namespace Squirrel\Strings\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Squirrel\Strings\Annotation\StringFilter;
use Squirrel\Strings\Annotation\StringFilterExtension;
use Squirrel\Strings\Annotation\StringFilterProcessor;
use Squirrel\Strings\Filter\LowercaseFilter;
use Squirrel\Strings\Filter\TrimFilter;
use Squirrel\Strings\StringFilterManager;
use Squirrel\Strings\Tests\TestClasses\ClassWithPrivateProperties;
use Squirrel\Strings\Tests\TestClasses\ClassWithPublicProperties;
use Squirrel\Strings\Tests\TestForms\PrivatePropertiesForm;
use Squirrel\Strings\Tests\TestForms\PublicPropertiesEmptyDataForm;
use Squirrel\Strings\Tests\TestForms\PublicPropertiesForm;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;

class FormExtensionTest extends \PHPUnit\Framework\TestCase
{
    private $formFactory;

    protected function setUp(): void
    {
        // Load annotation class, as it is not loaded automatically
        \class_exists(StringFilter::class);

        // Annotation reader, needed to find annotations
        $reader = new AnnotationReader();

        // String filters available
        $manager = new StringFilterManager([
            'Lowercase' => new LowercaseFilter(),
            'Trim' => new TrimFilter(),
        ]);

        // Processor of StringFilter annotations
        $processor = new StringFilterProcessor($reader, $manager);

        $this->formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new HttpFoundationExtension())
            ->addTypeExtension(new StringFilterExtension($processor))
            ->getFormFactory();
    }

    public function testPublicPropertiesSubmit()
    {
        $data = new ClassWithPublicProperties();
        $data->title = '    JOOOOPPPPPP    ';
        $data->text = '   cOrEcT  ';

        $request = Request::create(
            'https://127.0.0.1/', // URI
            'POST', // method
            [ // post parameters
                'public_properties_form' => [
                    'title' => '  alsdjl DASDAD ',
                    'text' => '  a   II I ADHSAHZUsd ',
                ],
            ],
            [], // cookies
            [], // files
            [], // $_SERVER
            '' // content
        );

        $form = $this->formFactory->create(PublicPropertiesForm::class, $data)
            ->add('save', SubmitType::class, [
                'label' => 'Save',
            ]);
        $form->handleRequest($request);

        $this->assertEquals('alsdjl dasdad', $data->title);
        $this->assertEquals('a   II I ADHSAHZUsd', $data->text);
    }

    public function testPublicPropertiesEmptyDataSubmit()
    {
        $request = Request::create(
            'https://127.0.0.1/', // URI
            'POST', // method
            [ // post parameters
                'public_properties_empty_data_form' => [
                    'title' => '  alsdjl DASDAD ',
                    'text' => '  a   II I ADHSAHZUsd ',
                ],
            ],
            [], // cookies
            [], // files
            [], // $_SERVER
            '' // content
        );

        $form = $this->formFactory->create(PublicPropertiesEmptyDataForm::class)
            ->add('save', SubmitType::class, [
                'label' => 'Save',
            ]);
        $form->handleRequest($request);

        $data = $form->getData();

        $this->assertEquals('alsdjl dasdad', $data->title);
        $this->assertEquals('a   II I ADHSAHZUsd', $data->text);
    }

    public function testPrivatePropertiesSubmit()
    {
        $data = new ClassWithPrivateProperties();

        $request = Request::create(
            'https://127.0.0.1/', // URI
            'POST', // method
            [ // post parameters
                'private_properties_form' => [
                    'title' => '  alsdjl DASDAD ',
                    'text' => '  a   II I ADHSAHZUsd ',
                ],
            ],
            [], // cookies
            [], // files
            [], // $_SERVER
            '' // content
        );

        $form = $this->formFactory->create(PrivatePropertiesForm::class, $data)
            ->add('save', SubmitType::class, [
                'label' => 'Save',
            ]);
        $form->handleRequest($request);

        $this->assertEquals('alsdjl dasdad', $data->getTitle());
        $this->assertEquals('a   II I ADHSAHZUsd', $data->getText());
    }
}
