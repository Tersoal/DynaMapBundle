<?php

namespace Tersoal\DynaMapBundle\EventSubscriber;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Cocur\Slugify\Slugify;

use Tersoal\DynaMapBundle\Entity\Field;
use Tersoal\DynaMapBundle\Service\FieldTypeService;

/**
 * Model Type Subscriber
 *
 * @author Tersoal
 */
class ModelTypeSubscriber implements EventSubscriberInterface
{
    private $masterId;
    private $em;
    private $fieldTypeService;

    public function __construct($masterId, EntityManager $em, FieldTypeService $fieldTypeService)
    {
        $this->masterId = $masterId;
        $this->em = $em;
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

        $this->fieldTypeService->setEntityManager($this->em);
        $fields = $this->fieldTypeService->getMasterFields($this->masterId);
        $formatedFields = $this->fieldTypeService->getFormatedFields($this->masterId);

        foreach($fields as $field) {
            $form->add(
                $formatedFields[$field->getId()]['fieldName'],
                $field->getFieldType(),
                $this->getFieldOptions($field)
            );
        }
    }

    public function getFieldOptions(Field $field)
    {
        $newField = [
            'required' => !$field->getFieldNullable(),
            'label' => $field->getFieldName(),
        ];

        switch ($field->getFieldType()) {
            case FieldTypeService::CHOICE:
                $newField['multiple'] = $field->getFieldMultiple();
                $newField['expanded'] = $field->getFieldExpanded();
                $newField['choices'] = explode("|", $field->getFieldChoices());
                $newField['choices_as_values'] = true;

                break;

            case FieldTypeService::COUNTRY:
            case FieldTypeService::CURRENCY:
            case FieldTypeService::LANGUAGE:
            case FieldTypeService::LOCALE:
            case FieldTypeService::TIMEZONE:
                $newField['multiple'] = $field->getFieldMultiple();
                $newField['expanded'] = $field->getFieldExpanded();
                $newField['choices_as_values'] = true;

                break;

            case FieldTypeService::NUMBER:
            case FieldTypeService::PERCENT:
                $newField['scale'] = $field->getFieldScale();

                break;
        }

        return $newField;
    }
}
