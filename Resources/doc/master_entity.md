Master entity
=============

ORM definition.

```php
//Tersoal/AppBundle/Resources/config/doctrine/Project.orm.xml
<?xml version="1.0" encoding="utf-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
  <entity name="Tersoal\AppBundle\Entity\Project" table="Project">

    <id name="id" type="integer" column="id">
      <generator strategy="IDENTITY"/>
    </id>

    <one-to-many field="fields" target-entity="ProjectField" mapped-by="project" orphan-removal="true">
      <cascade>
        <cascade-persist/>
        <cascade-remove/>
      </cascade>
    </one-to-many>

  </entity>
</doctrine-mapping>
```

The entity class must extend DynaMap Master Entity.

```php
//Tersoal/AppBundle/Entity/Project.php
<?php

namespace Tersoal\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tersoal\DynaMapBundle\Entity\Master;

/**
 * Project
 */
class Project extends Master
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $fields;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    public function __construct()
    {
        $this->fields = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }


    /**
     * Add fields
     *
     * @param \Tersoal\AppBundle\Entity\ProjectField $fields
     * @return $this
     */
    public function addField(\Tersoal\AppBundle\Entity\ProjectField $fields)
    {
        $this->fields[] = $fields;

        return $this;
    }

    /**
     * Remove fields
     *
     * @param \Tersoal\AppBundle\Entity\ProjectField $fields
     */
    public function removeField(\Tersoal\AppBundle\Entity\ProjectField $fields)
    {
        $this->fields->removeElement($fields);
    }

    /**
     * Get fields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFields()
    {
        return $this->fields;
    }
}
```

In your form class you don't need to do anything if you don't have another fields.

```php
//Tersoal/AppBundle/Form/ProjectType.php
<?php

namespace Tersoal\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tersoal\AppBundle\Entity\Project'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tersoal_appbundle_project';
    }
}
```
