<?php

namespace Tersoal\DynaMapBundle\Tool;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\SchemaTool as DoctrineSchemaTool;
use Doctrine\Common\Util\ClassUtils;

/**
 * Schema Tool
 *
 * @author Tersoal
 */
class SchemaTool
{
    private $em;
    private $schemaTool;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->schemaTool = new DoctrineSchemaTool($em);
    }

    public function updateSchema($modelClassMetadatas)
    {
        $allClassMetadata = $this->em->getMetadataFactory()->getAllMetadata();
        $allClassMetadata = array_merge($allClassMetadata, $modelClassMetadatas);

        $this->schemaTool->updateSchema($allClassMetadata);
    }

    /**
     * Write model properties in the entity, or in the class that extends (if any).
     *
     * @param ClassMetadata $modelClassMetadata
     * @param $fields
     */
    public function updateModelProperties(ClassMetadata $modelClassMetadata, $fields)
    {
        $parentClass = ClassUtils::getParentClass($modelClassMetadata->name);
        $reflClass = empty($parentClass) ?
                        $modelClassMetadata->getReflectionClass() :
                        ClassUtils::newReflectionClass($parentClass);

        $newFields = [];
        foreach($fields as $propertyName) {
            if (!$reflClass->hasProperty($propertyName)) {
                $newFields[] = $propertyName;
            }
        }

        if (!empty($newFields)) {
            $this->writeEntityFile($reflClass, $newFields);
        }
    }

    private function writeEntityFile(\ReflectionClass $reflClass, $newFields)
    {
        $path = $this->getPath($reflClass);
        if (empty($path)) {
            return false;
        }

        $currentCode = file_get_contents($path);
        $last = strrpos($currentCode, '}');

        $lines = [];
        foreach($newFields as $newField) {
            $lines[] = '    ' . 'protected' . ' $' . $newField . ';';
        }

        $newCode = substr($currentCode, 0, $last) . implode("\n", $lines) . "\n" . "}\n";

        file_put_contents($path, $newCode);

        return true;
    }

    private function getPath(\ReflectionClass $reflClass)
    {
        $outputDirectory = $this->getBasePathForClass($reflClass->getName(), $reflClass->getNamespaceName(), dirname($reflClass->getFilename()));
        if (!$outputDirectory) {
            return null;
        }

        $path = $outputDirectory . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $reflClass->getName()) . '.php';
        $dir = dirname($path);

        if ( !is_dir($dir) || !file_exists($path)) {
            return null;
        }

        return $path;
    }

    /**
     * Get a base path for a class
     *
     * @param string $name      class name
     * @param string $namespace class namespace
     * @param string $path      class path
     *
     * @return string
     * @throws \RuntimeException When base path not found
     */
    private function getBasePathForClass($name, $namespace, $path)
    {
        $namespace = str_replace('\\', '/', $namespace);
        $search = str_replace('\\', '/', $path);
        $destination = str_replace('/'.$namespace, '', $search, $c);

        if ($c != 1) {
            return null;
        }

        return $destination;
    }
}
