<?php

namespace Tersoal\DynaMapBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

use Tersoal\DynaMapBundle\EventSubscriber\FieldSubscriber;
use Tersoal\DynaMapBundle\Service\FieldTypeService;

class FieldTypeExtension extends AbstractTypeExtension
{
    private $fieldEntity;
    private $fieldTypeService;

    public function __construct($fieldEntity, FieldTypeService $fieldTypeService)
    {
        $this->fieldEntity = $fieldEntity;
        $this->fieldTypeService = $fieldTypeService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dataClass = $builder->getDataClass();

        if ($dataClass !== $this->fieldEntity) {
            return;
        }

        $listener = new FieldSubscriber(
            $this->fieldTypeService
        );
        $builder->addEventSubscriber($listener);
    }

    public function getExtendedType()
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tersoal_dynamapbundle_fieldtype_extension';
    }
}
