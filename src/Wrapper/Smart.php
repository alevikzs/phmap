<?php

namespace PhMap\Wrapper;

use \PhMap\Mapper,
    \PhMap\Wrapper,
    \PhMap\Exception\SmartValidator;

/**
 * Class Smart
 * @package PhMap\Wrapper
 */
class Smart extends Wrapper {

    /**
     * @var string|object|array
     */
    private $inputValue;

    /**
     * @return array|object|string
     */
    public function getInputValue() {
        return $this->inputValue;
    }

    /**
     * @param array|object|string $value
     * @return $this
     */
    public function setInputValue($value) {
        if (gettype($this->getInputValue()) !== gettype($value)) {
            $this->inputValue = $value;

            $this->createMapper($this->getOutputObject(), $this->getAnnotationAdapterType());
        }

        return $this;
    }

    /**
     * @param array|object|string $inputValue
     * @param string|object $outputClassOrObject
     * @param integer $adapter
     */
    public function __construct(
        $inputValue,
        $outputClassOrObject,
        $adapter = Mapper::MEMORY_ANNOTATION_ADAPTER
    ) {
        $this->inputValue = $inputValue;
        $this->createMapper($outputClassOrObject, $adapter);
    }

    /**
     * @param string|object $outputClassOrObject
     * @param integer $adapter
     * @return $this
     * @throws SmartValidator
     */
    protected function createMapper(
        $outputClassOrObject,
        $adapter = Mapper::MEMORY_ANNOTATION_ADAPTER
    ) {
        $mapperClass = $this->detectMapperClass();

        /** @var Mapper $mapper */
        $mapper = new $mapperClass(
            $this->getInputValue(),
            $outputClassOrObject,
            $adapter
        );

        return $this->setMapper($mapper);
    }

    /**
     * @return string
     * @throws SmartValidator
     */
    protected function detectMapperClass() {
        if (is_string($this->getInputValue())) {
            return '\PhMap\Wrapper\Json';
        } elseif (is_object($this->getInputValue())) {
            return '\PhMap\Mapper\Structure\Object';
        } elseif(is_array($this->getInputValue())) {
            return '\PhMap\Mapper\Structure\Associative';
        }

        throw new SmartValidator($this->getInputValue());
    }

}