<?php

namespace Tersoal\DynaMapBundle\Entity;

class Field
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var string
     */
    private $fieldType;

    /**
     * @var integer
     */
    private $fieldLength;

    /**
     * @var boolean
     */
    private $fieldNullable = true;

    /**
     * @var integer
     */
    private $fieldPrecision;

    /**
     * @var integer
     */
    private $fieldScale;

    /**
     * @var boolean
     */
    private $fieldUnique = false;

    /**
     * @var string
     */
    private $fieldDefault;

    /**
     * @var string
     */
    private $fieldCollation;

    /**
     * @var boolean
     */
    private $fieldUnsigned = false;

    /**
     * @var boolean
     */
    private $fieldMultiple = false;

    /**
     * @var boolean
     */
    private $fieldExpanded = false;

    /**
     * @var string
     */
    private $fieldChoices;


    /**
     * Set fieldName
     *
     * @param string $fieldName
     * @return Field
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * Get fieldName
     *
     * @return string 
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * Set fieldType
     *
     * @param string $fieldType
     * @return Field
     */
    public function setFieldType($fieldType)
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    /**
     * Get fieldType
     *
     * @return string 
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * Set fieldLength
     *
     * @param integer $fieldLength
     * @return Field
     */
    public function setFieldLength($fieldLength)
    {
        $this->fieldLength = $fieldLength;

        return $this;
    }

    /**
     * Get fieldLength
     *
     * @return integer 
     */
    public function getFieldLength()
    {
        return $this->fieldLength;
    }

    /**
     * Set fieldNullable
     *
     * @param boolean $fieldNullable
     * @return Field
     */
    public function setFieldNullable($fieldNullable)
    {
        $this->fieldNullable = $fieldNullable;

        return $this;
    }

    /**
     * Get fieldNullable
     *
     * @return boolean 
     */
    public function getFieldNullable()
    {
        return $this->fieldNullable;
    }

    /**
     * Set fieldPrecision
     *
     * @param integer $fieldPrecision
     * @return Field
     */
    public function setFieldPrecision($fieldPrecision)
    {
        $this->fieldPrecision = $fieldPrecision;

        return $this;
    }

    /**
     * Get fieldPrecision
     *
     * @return integer 
     */
    public function getFieldPrecision()
    {
        return $this->fieldPrecision;
    }

    /**
     * Set fieldScale
     *
     * @param integer $fieldScale
     * @return Field
     */
    public function setFieldScale($fieldScale)
    {
        $this->fieldScale = $fieldScale;

        return $this;
    }

    /**
     * Get fieldScale
     *
     * @return integer 
     */
    public function getFieldScale()
    {
        return $this->fieldScale;
    }

    /**
     * Set fieldUnique
     *
     * @param boolean $fieldUnique
     * @return Field
     */
    public function setFieldUnique($fieldUnique)
    {
        $this->fieldUnique = $fieldUnique;

        return $this;
    }

    /**
     * Get fieldUnique
     *
     * @return boolean 
     */
    public function getFieldUnique()
    {
        return $this->fieldUnique;
    }

    /**
     * Set fieldDefault
     *
     * @param string $fieldDefault
     * @return Field
     */
    public function setFieldDefault($fieldDefault)
    {
        $this->fieldDefault = $fieldDefault;

        return $this;
    }

    /**
     * Get fieldDefault
     *
     * @return string 
     */
    public function getFieldDefault()
    {
        return $this->fieldDefault;
    }

    /**
     * Set fieldCollation
     *
     * @param string $fieldCollation
     * @return Field
     */
    public function setFieldCollation($fieldCollation)
    {
        $this->fieldCollation = $fieldCollation;

        return $this;
    }

    /**
     * Get fieldCollation
     *
     * @return string 
     */
    public function getFieldCollation()
    {
        return $this->fieldCollation;
    }

    /**
     * Set fieldUnsigned
     *
     * @param boolean $fieldUnsigned
     * @return Field
     */
    public function setFieldUnsigned($fieldUnsigned)
    {
        $this->fieldUnsigned = $fieldUnsigned;

        return $this;
    }

    /**
     * Get fieldUnsigned
     *
     * @return boolean 
     */
    public function getFieldUnsigned()
    {
        return $this->fieldUnsigned;
    }

    /**
     * Set fieldMultiple
     *
     * @param boolean $fieldMultiple
     * @return Field
     */
    public function setFieldMultiple($fieldMultiple)
    {
        $this->fieldMultiple = $fieldMultiple;

        return $this;
    }

    /**
     * Get fieldMultiple
     *
     * @return boolean
     */
    public function getFieldMultiple()
    {
        return $this->fieldMultiple;
    }

    /**
     * Set fieldExpanded
     *
     * @param boolean $fieldExpanded
     * @return Field
     */
    public function setFieldExpanded($fieldExpanded)
    {
        $this->fieldExpanded = $fieldExpanded;

        return $this;
    }

    /**
     * Get fieldExpanded
     *
     * @return boolean
     */
    public function getFieldExpanded()
    {
        return $this->fieldExpanded;
    }

    /**
     * Set fieldChoices
     *
     * @param boolean $fieldChoices
     * @return Field
     */
    public function setFieldChoices($fieldChoices)
    {
        $this->fieldChoices = $fieldChoices;

        return $this;
    }

    /**
     * Get fieldChoices
     *
     * @return boolean
     */
    public function getFieldChoices()
    {
        return $this->fieldChoices;
    }
}
