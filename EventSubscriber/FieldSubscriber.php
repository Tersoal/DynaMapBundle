<?php

namespace Tersoal\DynaMapBundle\EventSubscriber;

use Symfony\Component\Form\FormEvent;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Tersoal\DynaMapBundle\Service\FieldTypeService;
use Tersoal\DynaMapBundle\Form\Type\CollationType;

/**
 * Master Subscriber
 *
 * @author Tersoal
 */
class FieldSubscriber implements EventSubscriberInterface
{
    private $fieldTypeService;

    public function __construct(FieldTypeService $fieldTypeService)
    {
        $this->fieldTypeService = $fieldTypeService;
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

        $form
            ->add('fieldName')
            ->add('fieldType', 'choice', array(
                'required' => true,
                'choices' => $this->fieldTypeService->getFieldList()
            ))
            ->add('fieldLength')
            ->add('fieldNullable')
            ->add('fieldPrecision')
            ->add('fieldScale')
            ->add('fieldUnique')
            ->add('fieldDefault')
            ->add('fieldCollation', CollationType::class, array(
                'required' => false,
            ))
            ->add('fieldUnsigned')
            ->add('fieldMultiple')
            ->add('fieldExpanded')
            ->add('fieldChoices', null, array(
                'label' => 'Selection choices (separated by "|")',
            ))
        ;
    }
}
