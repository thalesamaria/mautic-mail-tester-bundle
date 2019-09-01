<?php

return [
    'name'        => 'MauticMailTesterBundle',
    'description' => 'Mail-tester.com integration for Mautic',
    'author'      => 'mtcextendee.com',
    'version'     => '1.0',
    'routes'      => [
        'main' => [
            'mautic_mailtester_execute' => [
                'path'       => '/mailtester/{objectAction}/{objectId}',
                'controller' => 'MauticMailTesterBundle:MailTester:execute',
            ],
        ],
    ],
    'services'    => [
        'events' => [
            'mautic.mailtester.button.subscriber' => [
                'class'     => \MauticPlugin\MauticMailTesterBundle\EventListener\ButtonSubscriber::class,
                'arguments' => [
                    'mautic.helper.integration',
                ],
            ],
        ],
        'others' => [
        ],
        'integrations' => [
            'mautic.integration.mailtester' => [
                'class'     => \MauticPlugin\MauticMailTesterBundle\Integration\MailTesterIntegration::class,
                'arguments' => [
                ],
            ],
        ],
    ],
];
