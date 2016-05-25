<?php

namespace Tersoal\DynaMapBundle\Form\Extension;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use Tersoal\DynaMapBundle\EventSubscriber\ModelTypeSubscriber;
use Tersoal\DynaMapBundle\Tool\ModelTool;

class ModelTypeExtension extends AbstractTypeExtension
{
    private $routeParameter;
    private $request;
    private $em;
    private $modelTool;

    public function __construct($routeParameter, RequestStack $request, EntityManager $em, ModelTool $modelTool)
    {
        $this->routeParameter = $routeParameter;
        $this->request = $request;
        $this->em = $em;
        $this->modelTool = $modelTool;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dataClass = $builder->getDataClass();
        if (empty($dataClass)) {
            return;
        }

//        $form = $builder->getForm();
//        $data = $builder->getData();
        $classMetadata = $this->em->getClassMetadata($dataClass);

        if (!$this->modelTool->isModel($classMetadata)) {
            return;
        }

        $request = $this->request->getCurrentRequest();
        if (!$request) {
            return;
        }

        $masterId = $request->attributes->get($this->routeParameter);
        if (!$masterId || !is_numeric($masterId)) {
//            throw new MissingMandatoryParametersException(sprintf('"%s" mandatory parameter is missing.', 'project_id'));
            return;
        }

        $listener = new ModelTypeSubscriber(
            $masterId,
            $this->em,
            $this->modelTool->getFieldTypeService()
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
        return 'tersoal_dynamapbundle_modeltype_extension';
    }
}
