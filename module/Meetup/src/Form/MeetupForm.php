<?php

declare(strict_types=1);

namespace Meetup\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\ModuleManager\Feature\InputFilterProviderInterface;
use Zend\Validator;
use Zend\Validator\StringLength;

class MeetupForm extends Form implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('meetup');

        $this->add([
            'type' => Element\Text::class,
            'name' => 'title',
            'options' => [
                'label' => 'Title',
            ],
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'description',
            'options' => [
                'label' => 'Description',
            ],
        ]);

        $this->add([
            'type' => Element\Date::class,
            'name' => 'debut',
            'options' => [
                'label' => 'Date de debut',
            ],
        ]);

        $this->add([
            'type' => Element\Date::class,
            'name' => 'fin',
            'options' => [
                'label' => 'Date de fin',
            ],
        ]);

        $this->add([
            'type' => Element\Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Submit'
            ],
        ]);

        $this->add([
            'type' => Element\Submit::class,
            'name' => 'Delete',
            'attributes' => [
                'value' => 'delete'
            ],
        ]);
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getInputFilterConfig()
    {
        return [
            'title' => [
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 2,
                            'max' => 20,
                        ],
                    ],
                ],
            ],
            'description' => [
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 2,
                            'max' => 255,
                        ],
                    ],
                ],
            ],
            'fin' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => Validator\Callback::class,
                        'options' => [
                            'callback' => [$this, 'checkDates'],
                            'messages' => [
                                Validator\Callback::INVALID_VALUE => 'La date de fin doit être supérieur à la date de début.',
                            ],
                        ],
                    ]
                ]
            ]
        ];
    }

    public function checkDates($value, $context) :bool
    {
        return $value <= $context['fin'];
    }
}
