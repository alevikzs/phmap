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
     * @var string|object|array
     */
    private $value;

    /**
     * @return array|object|string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param array|object|string $value
     * @return $this
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    public function __construct($value, $class) {
        $this->setValue($value);

        parent::__construct($class);
    }

    /**
     * @return stdClass
     * @throws SmartValidator
     */
    public function map() {
        $mapperClass = $this->detectMapperClass();

        /** @var Mapper $mapper */
        $mapper = new $mapperClass($this->getValue(), $this->getClass());

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