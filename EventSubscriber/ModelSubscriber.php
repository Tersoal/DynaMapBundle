<?php

namespace Tersoal\DynaMapBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;

use Tersoal\DynaMapBundle\Tool\ModelTool;

/**
 * Model Subscriber
 *
 * @author Tersoal
 */
class ModelSubscriber implements EventSubscriber
{
    private $masterEntity;
    private $fieldEntity;
    private $routeParameter;
    private $request;
    private $router;
    private $modelTool;

    public function __construct($masterEntity, $fieldEntity, $routeParameter, RequestStack $request, Router $router, ModelTool $modelTool)
    {
        $this->masterEntity = $masterEntity;
        $this->fieldEntity = $fieldEntity;
        $this->routeParameter = $routeParameter;
        $this->request = $request;
        $this->router = $router;
        $this->modelTool = $modelTool;
    }
    
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
        );
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $em            = $eventArgs->getEntityManager();
        $classMetadata = $eventArgs->getClassMetadata();

        if (null === $classMetadata->reflClass) {
            return;
        }

        if ($this->modelTool->isModel($classMetadata)) {
            $this->mapModel($classMetadata, $em);
        }
    }

    /**
     * Map entity with model data
     *
     * @param ClassMetadata $classMetadata
     * @param EntityManager $em
     *
     * @return boolean
     */
    private function mapModel(ClassMetadata $classMetadata, EntityManager $em)
    {
        $request = $this->request->getCurrentRequest();
        if (!$request) {
            return;
        }

        $this->router->getContext()->fromRequest($request);
        $route = $this->router->match($request->getPathInfo());
        if (empty($route[$this->routeParameter])) {
            return;
        }

        $masterId = $route[$this->routeParameter];
        if (!$masterId || !is_numeric($masterId)) {
//            throw new MissingMandatoryParametersException(sprintf('"%s" mandatory parameter is missing.', 'project_id'));
            return;
        }

        $fieldTypeService = $this->modelTool->getFieldTypeService();
        $fieldTypeService->setEntityManager($em);

        $fields = $fieldTypeService->getFormatedFields($masterId);
        foreach($fields as $field) {
            $classMetadata->mapField($field);
        }

        $table = [
            'name' => $classMetadata->getTableName() . '_' . $masterId,
        ];
        $classMetadata->setPrimaryTable($table);
    }
}
