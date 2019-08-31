<?php

namespace MauticPlugin\MauticMailTesterBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;

class MailTesterIntegration extends AbstractIntegration
{
    public function getName()
    {
        // should be the name of the integration
        return 'MailTester';
    }

    public function getAuthenticationType()
    {
        /* @see \Mautic\PluginBundle\Integration\AbstractIntegration::getAuthenticationType */
        return 'none';
    }

    /**
     * Get icon for Integration.
     *
     * @return string
     */
    public function getIcon()
    {
        return 'plugins/MauticMailTesterBundle/Assets/img/icon.png';
    }

    /**
     * @param \Mautic\PluginBundle\Integration\Form|FormBuilder $builder
     * @param array                                             $data
     * @param string                                            $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        if ($formArea == 'keys') {
        }
    }
}
