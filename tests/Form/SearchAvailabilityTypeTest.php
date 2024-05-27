<?php

namespace App\Tests\Form;

use App\Form\SearchAvailabilityType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

class SearchAvailabilityTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $validator = Validation::createValidator();
        return [
            new PreloadedExtension([], []),
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'depart_date' => '2024-05-01',
            'return_date' => '2024-05-10',
        ];

        $model = [];
        $form = $this->factory->create(SearchAvailabilityType::class, $model);

        $expected = [
            'depart_date' => new \DateTime('2024-05-01'),
            'return_date' => new \DateTime('2024-05-10'),
        ];

        // submiting data to the form directly
        $form->submit($formData);

        // ensuring form is successfully submitted
        $this->assertTrue($form->isSynchronized());

        // checking that the $model has been correctly populated
        $this->assertEquals($expected, $form->getData());

        // checking that the form fields contain the correct data
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}