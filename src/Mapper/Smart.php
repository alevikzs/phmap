<?php

namespace PhMap\Mapper;

use \stdClass,

    \PhMap\Mapper,
    \PhMap\Exception\SmartValidator;

/**
 * Class Smart
 * @package PhMap\Mapper
 */
class Smart extends Mapper {

    /**
     * @var string|stdClass|array
     */
    private $value;

    /**
     * @return array|stdClass|string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param array|stdClass|string $value
     * @return $this
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * @param array|stdClass|string $value
     * @param string|stdClass $toMap
     * @param integer $adapter
     */
    public function __construct($value, $toMap, $adapter = self::MEMORY_ANNOTATION_ADAPTER) {
        $this->setValue($value);

        parent::__construct($toMap, $adapter);
    }

    /**
     * @return stdClass
     * @throws SmartValidator
     */
    public function map() {
        $mapperClass = $this->detectMapperClass();

        /** @var Mapper $mapper */
        $mapper = new $mapperClass(
            $this->getValue(),
            $this->getInstance(),
            $this->getAnnotationAdapterType()
        );

        return $mapper->map();
    }

    /**
     * @return string
     * @throws SmartValidator
     */
    protected function detectMapperClass() {
        if (is_string($this->getValue())) {
            return '\PhMap\Mapper\Json';
        } elseif (is_object($this->getValue())) {
            return '\PhMap\Mapper\Structure\Object';
        } elseif(is_array($this->getValue())) {
            return '\PhMap\Mapper\Structure\Associative';
        }

        throw new SmartValidator($this->getValue());
    }

}