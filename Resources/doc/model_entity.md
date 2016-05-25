Model entities
==============

ORM definition.

```php
//Tersoal/AppBundle/Resources/config/doctrine/Car.orm.xml
<?xml version="1.0" encoding="utf-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
  <entity name="Tersoal\AppBundle\Entity\Car" table="Car">

    <id name="id" type="integer" column="id">
      <generator strategy="IDENTITY"/>
    </id>

    <field name="carName" type="string" column="car_name" length="255" nullable="false"/>
    <field name="carComment" type="string" column="car_comment" length="255" nullable="true"/>

  </entity>
</doctrine-mapping>
```

The entity class must implement ModelInterface and must have two methods to identify the model: getDynaName to show label
when you select a model in master entity and getDynaSlug to save in database a unique identifier for that model.

```php
//Tersoal/AppBundle/Entity/Car.php
<?php

namespace Tersoal\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tersoal\DynaMapBundle\Model\ModelInterface;

/**
 * Car
 */
class Car implements ModelInterface
{
    /**
     * @var string
     */
    private $carName;

    /**
     * @var string
     */
    private $carComment;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set carName
     *
     * @param string $carName
     * @return Car
     */
    public function setCarName($carName)
    {
        $this->carName = $carName;

        return $this;
    }

    /**
     * Get carName
     *
     * @return string 
     */
    public function getCarName()
    {
        return $this->carName;
    }

    /**
     * Set carComment
     *
     * @param string $carComment
     * @return Car
     */
    public function setCarComment($carComment)
    {
        $this->carComment = $carComment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getCarComment()
    {
        return $this->carComment;
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
     * Get DynaName
     *
     * @return string
     */
    public function getDynaName()
    {
        return 'Car';
    }

    /**
     * Get DynaSlug
     *
     * @return string
     */
    public function getDynaSlug()
    {
        return 'car';
    }
}
```

### Warning

Doctrine requires all properties, getters and setters in the application entities. It checks at runtime that all this exist
and crash if don't. Thus, Dyna Map Bundle writes the dynamic fields properties in your model classes. This requires 
getter and setter magic methods to work properly.

Also, the modification of your entities can be a problem in your deploys. For skip this inconvenience, you can extend
your models from a class that only contains all dynamic properties of your models. Include this class in your gitignore 
file for skip the problem in your deploy.

Again, you could want to extend your models from a super class which contains shared fields for all your models (like
createdAt, createdBy, updatedAt, and so on). Doesn't matter! You can extend this intermediate class from your super class
and all will work as you expect!

The model super class ORM:

```php
//Tersoal/AppBundle/Resources/config/doctrine/Model.orm.xml
<?xml version="1.0" encoding="utf-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">

  <mapped-superclass name="Tersoal\AppBundle\Entity\Model">
    <field name="note" type="string" column="note" length="255" nullable="true"/>
  </mapped-superclass>

</doctrine-mapping>
```

And the entity:

```php
//Tersoal/AppBundle/Entity/Model.php
<?php

namespace Tersoal\AppBundle\Entity;

/**
 * Model
 *
 * Model super class
 */
class Model
{
    /**
     * @var string
     */
    private $note;

    /**
     * @param mixed $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }
}

```

The intermediate, void class to contain all dynamic properties.

```php
//Tersoal/AppBundle/Model/DynaModel.php
<?php

namespace Tersoal\AppBundle\Model;

use Tersoal\AppBundle\Entity\Model as ModelSuperClass;
/**
 * Dyna Model
 *
 * This class should not be in git repo. It only contain dynamic properties for the model entities.
 */
class DynaModel extends ModelSuperClass
{
}
```

Finally, our model entity will keep as:

```php
//Tersoal/AppBundle/Entity/Car.php
<?php

namespace Tersoal\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tersoal\DynaMapBundle\Model\ModelInterface;

/**
 * Car
 */
class Car extends DynaModel implements ModelInterface
{
    /**
     * @var string
     */
    private $carName;

    /**
     * @var string
     */
    private $carComment;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set carName
     *
     * @param string $carName
     * @return Car
     */
    public function setCarName($carName)
    {
        $this->carName = $carName;

        return $this;
    }

    /**
     * Get carName
     *
     * @return string 
     */
    public function getCarName()
    {
        return $this->carName;
    }

    /**
     * Set carComment
     *
     * @param string $carComment
     * @return Car
     */
    public function setCarComment($carComment)
    {
        $this->carComment = $carComment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getCarComment()
    {
        return $this->carComment;
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
     * Get DynaName
     *
     * @return string
     */
    public function getDynaName()
    {
        return 'Car';
    }

    /**
     * Get DynaSlug
     *
     * @return string
     */
    public function getDynaSlug()
    {
        return 'car';
    }
    
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }

        return null;
    }
}
```

In your form class you don't need to do anything more than add you shared model fields, like this:

```php
//Tersoal/AppBundle/Form/CarType.php
<?php

namespace Tersoal\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('carName')
            ->add('carComment')
            ->add('note')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tersoal\AppBundle\Entity\Car'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tersoal_appbundle_car';
    }
}
```
