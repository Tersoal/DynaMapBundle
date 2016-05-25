<?php

namespace Tersoal\DynaMapBundle\Service;

use Doctrine\Common\Util\Inflector;
use Doctrine\DBAL\Types\Type;
use Cocur\Slugify\Slugify;

use Tersoal\DynaMapBundle\Entity\Field;

/**
 * Field Type Service
 *
 * @author Tersoal
 */
class FieldTypeService
{
    const BIRTHDAY = 'birthday';
    const CHECKBOX = 'checkbox';
    const CHOICE = 'choice';
    const COUNTRY = 'country';
    const CURRENCY = 'currency';
    const DATETIME = 'datetime';
    const DATE = 'date';
    const EMAIL = 'email';
    const INTEGER = 'integer';
    const LANGUAGE = 'language';
    const LOCALE = 'locale';
    const NUMBER = 'number';
    const PASSWORD = 'password';
    const PERCENT = 'percent';
    const RADIO = 'radio';
    const RANGE = 'range';
    const TEXTAREA = 'textarea';
    const TEXT = 'text';
    const TIME = 'time';
    const TIMEZONE = 'timezone';
    const URL = 'url';

    private $masterEntity;
    private $fieldEntity;
    private $defaultList;
    private $whiteList;
    private $blackList;
    private $slugify;
    private $em;
    private $fields;
    private $formatedFields;

    public function __construct($masterEntity, $fieldEntity, $whiteList, $blackList, Slugify $slugify)
    {
        $this->masterEntity = $masterEntity;
        $this->fieldEntity = $fieldEntity;
        $this->whiteList = $whiteList;
        $this->blackList = $blackList;
        $this->slugify = $slugify;
        $this->fields = [];
        $this->formatedFields = [];

        // *** Add translation to show field in form choices.
        $this->defaultList = [
            self::BIRTHDAY  => 'Birthday',
            self::CHECKBOX  => 'Yes / No',
            self::CHOICE    => 'Selection choice',
            self::COUNTRY   => 'Country',
            self::CURRENCY  => 'Currency',
            self::DATETIME  => 'Date and time',
            self::DATE      => 'Date',
            self::EMAIL     => 'Email',
            self::INTEGER   => 'Integer number',
            self::LANGUAGE  => 'Language',
            self::LOCALE    => 'Locale code',
            self::NUMBER    => 'Decimal number',
            self::PASSWORD  => 'Encrypted (like password)',
            self::PERCENT   => 'Decimal number for percent',
            self::RADIO     => 'Radio choice',
            self::RANGE     => 'Range',
            self::TEXTAREA  => 'Long text',
            self::TEXT      => 'Short text',
            self::TIME      => 'Time',
            self::TIMEZONE  => 'Time zone',
            self::URL       => 'Url',
        ];

        natcasesort($this->defaultList);
    }

    public function setEntityManager($em)
    {
        $this->em = $em;

        return $this;
    }

    public function getDefaultList()
    {
        return $this->defaultList;
    }

    public function getWhiteList()
    {
        if (empty($this->whiteList) || !is_array($this->whiteList)) {
            return null;
        }

        $list = [];
        foreach ($this->whiteList as $type) {
            if (array_key_exists($type, $this->getDefaultList())) {
                $list[$type] = $this->defaultList[$type];
            }
        }

        natcasesort($list);
        return $list;
    }

    public function getBlackList()
    {
        if (empty($this->blackList) || !is_array($this->blackList)) {
            return null;
        }

        $list = [];
        foreach ($this->blackList as $type) {
            if (array_key_exists($type, $this->getDefaultList())) {
                $list[$type] = $this->defaultList[$type];
            }
        }

        natcasesort($list);
        return $list;
    }

    public function getFieldList()
    {
        if (!empty($this->getWhiteList())) {
            return $this->getWhiteList();
        }

        if (empty($this->getBlackList())) {
            return $this->getDefaultList();
        }

        $list = [];
        foreach ($this->getDefaultList() as $type => $value) {
            if (!array_key_exists($type, $this->getBlackList())) {
                $list[$type] = $value;
            }
        }

        natcasesort($list);
        return $list;
    }

    public function setMasterFields($masterId)
    {
        $master = $this->em->getRepository($this->masterEntity)->find($masterId);
        if (!$master) {
            return null;
        }

        $masterFieldsMapping = $master->getFields()->getMapping();
        $masterAssociationField = $masterFieldsMapping['mappedBy'];
        $this->fields = $this->em->getRepository($this->fieldEntity)->findBy([$masterAssociationField => $masterId]);

        return $this;
    }

    public function setMasterFieldsFromEntity($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public function getMasterFields($masterId)
    {
        if (empty($this->fields)) {
            $this->setMasterFields($masterId);
        }

        return $this->fields;
    }

    public function setFormatedFields($masterId)
    {
        if (empty($this->em)) {
            return null;
        }

        if (empty($this->fields)) {
            $this->setMasterFields($masterId);
        }

        $this->formatedFields = [];
        foreach($this->fields as $field) {
            if (null !== ($formatedField = $this->parseField($field))) {
                $this->formatedFields[$field->getId()] = $formatedField;
            }
        }

        return $this;
    }

    public function getFormatedFields($masterId)
    {
        if (empty($this->formatedFields)) {
            $this->setFormatedFields($masterId);
        }

        return $this->formatedFields;
    }

    public function getNewFormatedFields($masterId)
    {
        $this->setMasterFields($masterId);
        $this->setFormatedFields($masterId);

        return $this->formatedFields;
    }

    public function getModelFieldName($fieldName, $ignoredFiledId = null)
    {
        return Inflector::camelize($this->getFieldSlug($fieldName, $ignoredFiledId));
    }

    public function parseField(Field $field)
    {
        if (empty($this->getDBFieldType($field))) {
            return null;
        }

        $fieldMapping = array(
            'fieldName'  => Inflector::camelize($this->getFieldSlug($field->getFieldName())),
            'columnName' => $this->getFieldSlug($field->getFieldName()),
            'type'       => $this->getDBFieldType($field),
            'nullable'   => $field->getFieldNullable(),
            'unique'     => $field->getFieldUnique(),
        );

        // Type specific elements
        switch ($fieldMapping['type']) {
            case Type::TARRAY:
            case Type::BLOB:
            case Type::GUID:
            case Type::JSON_ARRAY:
            case Type::OBJECT:
            case Type::SIMPLE_ARRAY:
            case Type::STRING:
            case Type::TEXT:
                if (($length = $field->getFieldLength()) !== null) {
                    $fieldMapping['length'] = $length;
                }
                if (($collation = $field->getFieldCollation()) !== null) {
                    $fieldMapping['collation'] = $field->getFieldCollation();
                }
                break;

            case Type::DECIMAL:
            case Type::FLOAT:
                if (($precision = $field->getFieldPrecision()) !== null) {
                    $fieldMapping['precision'] = $precision;
                }
                if (($scale = $field->getFieldScale()) !== null) {
                    $fieldMapping['scale'] = $field->getFieldScale();
                }
                break;

            case Type::INTEGER:
            case Type::BIGINT:
            case Type::SMALLINT:
                if (($unsigned = $field->getFieldUnsigned()) !== null) {
                    $fieldMapping['unsigned'] = $unsigned;
                }
                break;
        }

        if (($default = $field->getFieldDefault()) !== null) {
            $fieldMapping['default'] = $default;
        }

        return $fieldMapping;
    }

    /**
     * Uses Cocur slugify bundle to get field name slug for database field name
     *
     * @param string $name
     * @param integer $ignoredFiledId
     *
     * @return string
     */
    public function getFieldSlug($name, $ignoredFiledId = null)
    {
        $slug = $this->slugify->slugify($name, '_');

        $i = 1;
        $oldSlug = $slug;
        while ($this->searchColumnName($slug, $ignoredFiledId)) {
            $slug = $oldSlug . '_' . ++$i;
        }

        return $slug;
    }

    /**
     * @param string $slug
     * @param integer $ignoredFiledId
     * @return bool
     */
    private function searchColumnName($slug, $ignoredFiledId = null)
    {
        foreach ($this->formatedFields as $id => $field) {
            if ($field['columnName'] === $slug && $id !== $ignoredFiledId) {
                return true;
            }
        }

        return false;
    }

    public function getDBFieldType(Field $field)
    {
        $fieldType = $field->getFieldType();

        switch ($fieldType) {
            case self::BIRTHDAY:
            case self::DATE:
                $dbFieldType = Type::DATE;
                break;

            case self::CHECKBOX:
                $dbFieldType = Type::BOOLEAN;
                break;

            case self::CHOICE:
            case self::COUNTRY:
            case self::CURRENCY:
            case self::LANGUAGE:
            case self::LOCALE:
            case self::TIMEZONE:
                $dbFieldType = $field->getFieldMultiple() ? Type::TARRAY : Type::STRING;
                break;

            case self::DATETIME:
                $dbFieldType = Type::DATETIME;
                break;

            case self::EMAIL:
            case self::PASSWORD:
            case self::RADIO:
            case self::RANGE:
            case self::TEXT:
            case self::URL:
                $dbFieldType = Type::STRING;
                break;

            case self::INTEGER:
                $dbFieldType = Type::INTEGER;
                break;

            case self::NUMBER:
            case self::PERCENT:
                $dbFieldType = Type::DECIMAL;
                break;

            case self::TEXTAREA:
                $dbFieldType = Type::TEXT;
                break;

            case self::TIME:
                $dbFieldType = Type::TIME;
                break;

            default:
                $dbFieldType = null;
        }

        return $dbFieldType;
    }
}
