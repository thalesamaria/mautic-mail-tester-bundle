<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic, Inc.
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticMailTesterBundle\EventListener;

use Mautic\CoreBundle\CoreEvents;
use Mautic\CoreBundle\Event\CustomButtonEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\CoreBundle\Templating\Helper\ButtonHelper;
use Mautic\EmailBundle\Entity\Email;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticMailTesterBundle\Integration\ECronTesterIntegration;

class ButtonSubscriber extends CommonSubscriber
{
    /**
     * @var IntegrationHelper
     */
    protected $integrationHelper;

    private $event;

    private $objectId;

    /**
     * ButtonSubscriber constructor.
     *
     * @param IntegrationHelper $helper
     */
    public function __construct(IntegrationHelper $integrationHelper)
    {
        $this->integrationHelper = $integrationHelper;
    }

    public static function getSubscribedEvents()
    {
        return [
            CoreEvents::VIEW_INJECT_CUSTOM_BUTTONS => ['injectViewButtons', 0],
        ];
    }

    /**
     * @param CustomButtonEvent $event
     */
    public function injectViewButtons(CustomButtonEvent $event)
    {
        $this->injectMailTesterButtons($event);
    }

    /**
     * @param CustomButtonEvent $event
     */
    private function injectMailTesterButtons(CustomButtonEvent $event)
    {
        /** @var ECronTesterIntegration $myIntegration */
        $eMailTesterIntegration = $this->integrationHelper->getIntegrationObject('MailTester');

        if (false === $eMailTesterIntegration || !$eMailTesterIntegration->getIntegrationSettings()->getIsPublished()) {
            return;
        }

        $objectId = $event->getRequest()->get('objectId');
        $this->setEvent($event);
        $this->setObjectId($objectId);
        if ($event->getItem() != null) {
            /** @var Email $object */
            $object = $event->getItem();
            if (method_exists($object, 'getId')) {
                $this->setObjectId($event->getItem()->getId());
            }
        }
        $this->addButtonGenerator(
            'sendToMailTester',
            'mautic.mailtester.button.test',
            'fa fa-external-link',
            'email',
            3,
            '_blank'
        );

    }

    /**
     * @param        $objectAction
     * @param        $btnText
     * @param        $icon
     * @param        $context
     * @param int    $priority
     * @param null   $target
     * @param string $header
     *
     */
    private function addButtonGenerator($objectAction, $btnText, $icon, $context, $priority = 1, $target = null, $header = '')
    {
        $event    = $this->getEvent();
        $objectId = $this->getObjectId();

        $route    = $this->router->generate(
            'mautic_mailtester_execute',
            [
                'objectAction' => $objectAction,
                'objectId'     => $objectId,
            ]
        );

        $attr     = [
            'href'        => $route,
            'data-toggle' => 'ajax',
            'data-method' => 'POST',
        ];

        switch ($target){
            case '_blank':
                $attr['data-toggle'] = '';
                $attr['data-method'] = '';
                $attr['target'] = $target;
                break;
            case '#MauticSharedModal':
                $attr['data-toggle'] = 'ajaxmodal';
                $attr['data-method'] = '';
                $attr['data-target'] = $target;
                $attr['data-header'] = $header;
                break;
        }

        $button =
            [
                'attr'      => $attr,
                'btnText'   => $this->translator->trans($btnText),
                'iconClass' => $icon,
                'priority'  => $priority,
            ];

        $event
            ->addButton(
                $button,
                ButtonHelper::LOCATION_PAGE_ACTIONS,
                ['mautic_'.$context.'_action', ['objectAction' => 'view']]
            );
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param mixed $objectId
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    }
}
