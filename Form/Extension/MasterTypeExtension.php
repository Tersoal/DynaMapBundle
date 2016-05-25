<?php

namespace Tersoal\DynaMapBundle\Form\Extension;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

use Tersoal\DynaMapBundle\EventSubscriber\MasterSubscriber;
use Tersoal\DynaMapBundle\Tool\ModelTool;

class MasterTypeExtension extends AbstractTypeExtension
{
    private $masterEntity;
    private $em;
    private $modelTool;

    public function __construct($masterEntity, EntityManager $em, ModelTool $modelTool)
    {
        $this->masterEntity = $masterEntity;
        $this->em = $em;
        $this->modelTool = $modelTool;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dataClass = $builder->getDataClass();

        if ($dataClass !== $this->masterEntity) {
            return;
        }

        $listener = new MasterSubscriber(
            $this->em,
            $this->modelTool
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
        return 'tersoal_dynamapbundle_mastertype_extension';
    }
}
