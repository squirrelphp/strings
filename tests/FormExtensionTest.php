<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Attribute\StringFilterExtension;
use Squirrel\Strings\Attribute\StringFilterProcessor;
use Squirrel\Strings\Filter\LowercaseFilter;
use Squirrel\Strings\Filter\TrimFilter;
use Squirrel\Strings\StringFilterSelector;
use Squirrel\Strings\Tests\TestClasses\ClassWithPrivateProperties;
use Squirrel\Strings\Tests\TestClasses\ClassWithPrivateTypedProperties;
use Squirrel\Strings\Tests\TestClasses\ClassWithPublicProperties;
use Squirrel\Strings\Tests\TestClasses\ClassWithPublicPropertyPromotion;
use Squirrel\Strings\Tests\TestClasses\ClassWithPublicTypedProperties;
use Squirrel\Strings\Tests\TestForms\PrivatePropertiesForm;
use Squirrel\Strings\Tests\TestForms\PrivateTypedPropertiesForm;
use Squirrel\Strings\Tests\TestForms\PublicPropertiesEmptyDataForm;
use Squirrel\Strings\Tests\TestForms\PublicPropertiesForm;
use Squirrel\Strings\Tests\TestForms\PublicPropertyPromotionForm;
use Squirrel\Strings\Tests\TestForms\PublicTypedPropertiesEmptyDataForm;
use Squirrel\Strings\Tests\TestForms\PublicTypedPropertiesForm;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;

class FormExtensionTest extends \PHPUnit\Framework\TestCase
{
    private FormFactoryInterface $formFactory;

    protected function setUp(): void
    {
        // Load annotation class, as it is not loaded automatically
        //\class_exists(StringFilter::class);

        // String filters available
        $manager = new StringFilterSelector([
            'Lowercase' => new LowercaseFilter(),
            'Trim' => new TrimFilter(),
        ]);

        // Processor of StringFilter annotations
        $processor = new StringFilterProcessor($manager);

        $this->formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new HttpFoundationExtension())
            ->addTypeExtension(new StringFilterExtension($processor))
            ->getFormFactory();
    }

    public function testPublicPropertiesSubmit(): void
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
            '', // content
        );

        $form = $this->formFactory->create(PublicPropertiesForm::class, $data)
            ->add('save', SubmitType::class, [
                'label' => 'Save',
            ]);
        $form->handleRequest($request);

        $this->assertEquals('alsdjl dasdad', $data->title);
        $this->assertEquals('a   II I ADHSAHZUsd', $data->text);
    }

    public function testPublicPropertiesEmptyDataSubmit(): void
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
            '', // content
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

    public function testPublicTypedPropertiesSubmit(): void
    {
        $data = new ClassWithPublicTypedProperties();
        $data->title = '    JOOOOPPPPPP    ';
        $data->texts = [
            '   cOrEcT  ',
            ' oMundo ',
        ];

        $request = Request::create(
            'https://127.0.0.1/', // URI
            'POST', // method
            [ // post parameters
                'public_typed_properties_form' => [
                    'title' => '  alsdjl DASDAD ',
                    'texts' => [
                        '  a   II I ADHSAHZUsd ',
                        ' oMundo ',
                    ],
                ],
            ],
            [], // cookies
            [], // files
            [], // $_SERVER
            '', // content
        );

        $form = $this->formFactory->create(PublicTypedPropertiesForm::class, $data)
            ->add('save', SubmitType::class, [
                'label' => 'Save',
            ]);
        $form->handleRequest($request);

        $this->assertEquals('alsdjl dasdad', $data->title);
        $this->assertEquals([
            'a   II I ADHSAHZUsd',
            'oMundo',
        ], $data->texts);
    }

    public function testPublicTypedPropertiesEmptyDataSubmit(): void
    {
        $request = Request::create(
            'https://127.0.0.1/', // URI
            'POST', // method
            [ // post parameters
                'public_typed_properties_empty_data_form' => [
                    'title' => '  alsdjl DASDAD ',
                    'texts' => [
                        '  a   II I ADHSAHZUsd ',
                        ' oMundo ',
                        '   ladidida ' . "\n",
                    ],
                ],
            ],
            [], // cookies
            [], // files
            [], // $_SERVER
            '', // content
        );

        $form = $this->formFactory->create(PublicTypedPropertiesEmptyDataForm::class)
            ->add('save', SubmitType::class, [
                'label' => 'Save',
            ]);
        $form->handleRequest($request);

        $data = $form->getData();

        $this->assertEquals('alsdjl dasdad', $data->title);
        $this->assertEquals([
            'a   II I ADHSAHZUsd',
            'oMundo',
            'ladidida',
        ], $data->texts);
    }

    public function testPrivatePropertiesSubmit(): void
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
            '', // content
        );

        $form = $this->formFactory->create(PrivatePropertiesForm::class, $data)
            ->add('save', SubmitType::class, [
                'label' => 'Save',
            ]);
        $form->handleRequest($request);

        $this->assertEquals('alsdjl dasdad', $data->getTitle());
        $this->assertEquals('a   II I ADHSAHZUsd', $data->getText());
    }

    public function testPrivateTypedPropertiesSubmit(): void
    {
        $data = new ClassWithPrivateTypedProperties();

        $request = Request::create(
            'https://127.0.0.1/', // URI
            'POST', // method
            [ // post parameters
                'private_typed_properties_form' => [
                    'title' => '  alsdjl DASDAD ',
                    'text' => '  a   II I ADHSAHZUsd ',
                ],
            ],
            [], // cookies
            [], // files
            [], // $_SERVER
            '', // content
        );

        $form = $this->formFactory->create(PrivateTypedPropertiesForm::class, $data)
            ->add('save', SubmitType::class, [
                'label' => 'Save',
            ]);
        $form->handleRequest($request);

        $this->assertEquals('alsdjl dasdad', $data->getTitle());
        $this->assertEquals('a   II I ADHSAHZUsd', $data->getText());
    }

    public function testPublicTypedPropertyPromotionSubmit(): void
    {
        $data = new ClassWithPublicPropertyPromotion(
            title: '    JOOOOPPPPPP    ',
            text: '   cOrEcT  ',
            other: '',
        );

        $request = Request::create(
            'https://127.0.0.1/', // URI
            'POST', // method
            [ // post parameters
                'public_property_promotion_form' => [
                    'title' => '  alsdjl DASDAD ',
                    'text' => '  a   II I ADHSAHZUsd ',
                ],
            ],
            [], // cookies
            [], // files
            [], // $_SERVER
            '', // content
        );

        $form = $this->formFactory->create(PublicPropertyPromotionForm::class, $data)
            ->add('save', SubmitType::class, [
                'label' => 'Save',
            ]);
        $form->handleRequest($request);

        $this->assertEquals('alsdjl dasdad', $data->title);
        $this->assertEquals('a   II I ADHSAHZUsd', $data->text);
    }
}
