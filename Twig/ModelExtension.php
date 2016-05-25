<?php

namespace Tersoal\DynaMapBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

use Tersoal\DynaMapBundle\Tool\ModelTool;

/**
 * ModelExtension
 *
 */
class ModelExtension extends \Twig_Extension
{
    private $routeParameter;
    private $request;
    private $em;
    private $modelTool;
    private $fieldTypeService;
    private $masterId;

    /**
     * @param string $routeParameter
     * @param RequestStack $request
     * @param EntityManager $em
     * @param ModelTool $modelTool
     */
    public function __construct($routeParameter, RequestStack $request, EntityManager $em, ModelTool $modelTool)
    {
        $this->routeParameter = $routeParameter;
        $this->request = $request;
        $this->em = $em;
        $this->modelTool = $modelTool;
        $this->fieldTypeService = $modelTool->getFieldTypeService();

        $this->fieldTypeService->setEntityManager($this->em);
        $this->modelTool->setEntityManager($this->em)->setModelData();
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('dyna_map_model_fields', array($this, 'getModelFields')),
            new \Twig_SimpleFunction('dyna_map_model_field_name', array($this, 'getModelFieldName')),
        );
    }

    /**
     * @return array
     */
    public function getModelFields($modelSlug)
    {
        if (empty($modelSlug) || !is_string($modelSlug) || !$this->checkRequisites($modelSlug)) {
            return false;
        }

        $fields = $this->fieldTypeService->getMasterFields($this->masterId);

        return $fields;
    }

    /**
     * @return array
     */
    public function getModelFieldName($fieldId)
    {
        if (empty($fieldId) || !$this->checkRouting()) {
            return false;
        }

        $formatedFields = $this->fieldTypeService->getFormatedFields($this->masterId);

        return $formatedFields[$fieldId]['fieldName'];
    }

    public function checkRequisites($modelSlug)
    {
        $modelClassMetadata = $this->modelTool->getModel($modelSlug);
        if (!$this->modelTool->isModel($modelClassMetadata) || !$this->checkRouting()) {
            return false;
        }

        return true;
    }

    public function checkRouting()
    {
        $request = $this->request->getCurrentRequest();
        if (!$request) {
            return false;
        }

        $this->masterId = $request->attributes->get($this->routeParameter);
        if (!$this->masterId || !is_numeric($this->masterId)) {
            return false;
        }

        return true;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'tersoal_dynamapbundle_twig_model_extension';
    }
}
