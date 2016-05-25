<?php

namespace Tersoal\DynaMapBundle\EventSubscriber;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Tersoal\DynaMapBundle\Tool\ModelTool;

/**
 * Master Subscriber
 *
 * @author Tersoal
 */
class MasterSubscriber implements EventSubscriberInterface
{
    private $em;
    private $modelTool;

    public function __construct(EntityManager $em, ModelTool $modelTool)
    {
        $this->em = $em;
        $this->modelTool = $modelTool;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'addFields',
            FormEvents::PRE_SUBMIT => 'addFields',
        );
    }

    public function addFields(FormEvent $event)
    {
        $form = $event->getForm();
        $this->modelTool->setEntityManager($this->em)->setModelData();

        $form
            ->add('name')
            ->add('model', 'choice', array(
                'required' => true,
                'choices' => $this->modelTool->getModelChoices()
            ))
        ;
    }
}
