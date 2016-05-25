Field entity
=============

ORM definition.

```php
//Tersoal/AppBundle/Resources/config/doctrine/ProjectField.orm.xml
<?xml version="1.0" encoding="utf-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
  <entity name="Tersoal\AppBundle\Entity\ProjectField" table="ProjectField">

    <id name="id" type="integer" column="id">
      <generator strategy="IDENTITY"/>
    </id>

    <many-to-one field="project" target-entity="Project" inversed-by="fields">
      <join-columns>
        <join-column name="project_id" referenced-column-name="id" nullable="false"/>
      </join-columns>
    </many-to-one>

  </entity>
</doctrine-mapping>

```

The entity class must extend DynaMap Field Entity.

```php
//Tersoal/AppBundle/Entity/ProjectField.php
<?php

namespace Tersoal\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tersoal\DynaMapBundle\Entity\Field;

/**
 * ProjectField
 */
class ProjectField extends Field
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * project.
     *
     * @var \Tersoal\AppBundle\Entity\Project
     *
     */
    protected $project;

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets project.
     *
     * @return \Tersoal\AppBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set project
     *
     * @param \Tersoal\AppBundle\Entity\Project $project
     * @return $this
     */
    public function setProject(\Tersoal\AppBundle\Entity\Project $project)
    {
        $this->project = $project;

        return $this;
    }
}
```

In your form class you don't need to do anything more than add you master entity, if you don't have another fields.

```php
//Tersoal/AppBundle/Form/ProjectFieldType.php
<?php

namespace Tersoal\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectFieldType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tersoal\AppBundle\Entity\ProjectField'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tersoal_appbundle_projectfield';
    }
}
```
