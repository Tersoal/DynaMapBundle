<?php

namespace Tersoal\DynaMapBundle\Tool;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

use Tersoal\DynaMapBundle\Service\FieldTypeService;

/**
 * Model Tool
 *
 * @author Tersoal
 */
class ModelTool
{
    private $masterEntity;
    private $modelInterface;
    private $fieldTypeService;
    private $allMetadata;
    private $models;
    private $modelChoices;
    private $em;

    public function __construct($masterEntity, $modelInterface, FieldTypeService $fieldTypeService)
    {
        $this->masterEntity = $masterEntity;
        $this->modelInterface = $modelInterface;
        $this->fieldTypeService = $fieldTypeService;
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        return $this;
    }

    public function setModelData()
    {
        if (empty($this->em)) {
            return null;
        }

        $this->allMetadata = $this->getAllMetadata();

        foreach ($this->allMetadata as $metadata) {
            if ($this->isModel($metadata)) {
                $reflectionClass = $metadata->getReflectionClass();
                $entity = $reflectionClass->newInstance();
                $this->models[$entity->getDynaSlug()] = $metadata;
                $this->modelChoices[$entity->getDynaSlug()] = $entity->getDynaName();
            }
        }

        return $this;
    }

    public function getAllMetadata()
    {
        return $this->em->getMetadataFactory()->getAllMetadata();
    }

    public function getFieldTypeService()
    {
        return $this->fieldTypeService;
    }

    public function getModels()
    {
        return $this->models;
    }

    public function getModel($modelSlug)
    {
        return $this->models[$modelSlug] ?: null;
    }

    public function getModelSlug($model)
    {
        $reflectionClass = $model->getReflectionClass();
        $entity = $reflectionClass->newInstance();

        return $entity->getDynaSlug();
    }

    public function getModelChoices()
    {
        return $this->modelChoices;
    }

    public function getAllModelMetadatas()
    {
        $modelClassMetadatas = [];

        foreach ($this->models as $model) {
            $modelClassMetadatas = array_merge($modelClassMetadatas, $this->getModelMetadatas($model));
        }

        return $modelClassMetadatas;
    }

    public function getModelMetadatas(ClassMetadata $model)
    {
        $newModelClassMetadatas = [];

        $masters = $this->em->getRepository($this->masterEntity)->findByModel($this->getModelSlug($model));
        foreach ($masters as $masterEntity) {
            $newModelClassMetadata = clone $model;
            $tableName = $model->getTableName() . '_' . $masterEntity->getId();
            $newModelClassMetadata->setPrimaryTable(['name' => $tableName]);
            $newModelClassMetadata->name .= '_' . $masterEntity->getId();
            $newModelClassMetadata->namespace .= '_' . $masterEntity->getId();
            $newModelClassMetadata->rootEntityName .= '_' . $masterEntity->getId();

            $this->fieldTypeService->setEntityManager($this->em);
            $fields = $this->fieldTypeService->getNewFormatedFields($masterEntity->getId());

            foreach($fields as $field) {
                $newModelClassMetadata->mapField($field);
            }

            $newModelClassMetadatas[] = $newModelClassMetadata;
        }

        return $newModelClassMetadatas;
    }

    /**
     * Checks if entity is a dyna map model
     *
     * @param ClassMetadata $classMetadata
     *
     * @return boolean
     */
    public function isModel(ClassMetadata $classMetadata)
    {
        $reflectionClass = $classMetadata->getReflectionClass();

        if (in_array($this->modelInterface, $reflectionClass->getInterfaceNames())) {
            return true;
        }

        return false;
    }
}
