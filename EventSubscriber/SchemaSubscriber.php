<?php

namespace Tersoal\DynaMapBundle\EventSubscriber;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

use Tersoal\DynaMapBundle\Tool\SchemaTool;
use Tersoal\DynaMapBundle\Tool\ModelTool;

/**
 * Schema Subscriber
 *
 * @author Tersoal
 */
class SchemaSubscriber implements EventSubscriber
{
    private $masterEntity;
    private $fieldEntity;
    private $modelTool;
    private $fieldTypeService;
    private $em;
    private $schemaTool;

    public function __construct($masterEntity, $fieldEntity, ModelTool $modelTool)
    {
        $this->masterEntity = $masterEntity;
        $this->fieldEntity = $fieldEntity;
        $this->modelTool = $modelTool;
        $this->fieldTypeService = $modelTool->getFieldTypeService();
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        );
    }

    /**
     * @param LifeCycleEventArgs $eventArgs
     */
    public function postPersist(LifeCycleEventArgs $eventArgs)
    {
        $this->manageModel($eventArgs);
    }

    /**
     * @param LifeCycleEventArgs $eventArgs
     */
    public function postUpdate(LifeCycleEventArgs $eventArgs)
    {
        $this->manageModel($eventArgs);
    }

    /**
     * @param LifeCycleEventArgs $eventArgs
     */
    public function postRemove(LifeCycleEventArgs $eventArgs)
    {
        $this->manageModel($eventArgs);
    }

    public function manageModel(LifecycleEventArgs $eventArgs)
    {
        $this->em = $eventArgs->getEntityManager();
        $this->schemaTool = new SchemaTool($this->em);
        $this->fieldTypeService->setEntityManager($this->em);
        $entity = $eventArgs->getObject();

        if ($entity instanceof $this->masterEntity) {
            $this->updateModelSchema();
            $this->manageMasterEntity($entity);
        }

        if ($entity instanceof $this->fieldEntity) {
            $this->updateModelSchema();
            $this->manageFieldEntity($entity);
        }
    }

    public function updateModelSchema()
    {
        $this->modelTool->setEntityManager($this->em)->setModelData();
        $modelClassMetadatas = $this->modelTool->getAllModelMetadatas();

        $this->schemaTool->updateSchema($modelClassMetadatas);
    }

    public function manageMasterEntity($entity)
    {
        $modelSlug = $entity->getModel();

        $newFields = [];
        $formatedFields = $this->fieldTypeService
                                ->setMasterFieldsFromEntity($entity->getFields())
                                ->getFormatedFields($entity->getId());

        foreach ($formatedFields as $field) {
            $newFields[] = $field['fieldName'];
        }

        $this->schemaTool->updateModelProperties($this->modelTool->getModel($modelSlug), $newFields);
    }

    public function manageFieldEntity($entity)
    {
        $modelSlug = null;
        $masterEntity = null;
        $fieldEntityCMD = $this->em->getClassMetadata($this->fieldEntity);
        $associationMappings = $fieldEntityCMD->getAssociationMappings();
        foreach ($associationMappings as $association) {
            $associationField = 'get' . $association['fieldName'];
            if ($entity->$associationField() instanceof $this->masterEntity) {
                $modelSlug = $entity->$associationField()->getModel();
                $masterEntity = $entity->$associationField();
            }
        }

        if (!$modelSlug || !$masterEntity) {
            return;
        }

        $this->fieldTypeService->getNewFormatedFields($masterEntity->getId());

        $this->schemaTool->updateModelProperties(
            $this->modelTool->getModel($modelSlug),
            [$this->fieldTypeService->getModelFieldName($entity->getFieldName(), $entity->getId())]
        );
    }
}
