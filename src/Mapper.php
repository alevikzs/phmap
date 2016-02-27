<?php

namespace PhMap;

/**
 * Class Mapper
 * @abstract
 * @package PhMap
 */
abstract class Mapper implements MapperInterface {

    /**
     * @const integer
     */
    const MEMORY_ANNOTATION_ADAPTER = 1;

    /**
     * @const integer
     */
    const FILES_ANNOTATION_ADAPTER = 2;

    /**
     * @const integer
     */
    const APC_ANNOTATION_ADAPTER = 3;

    /**
     * @const integer
     */
    const X_CACHE_ANNOTATION_ADAPTER = 4;

    /**
     * @var string
     */
    private $outputClass;

    /**
     * @var object
     */
    private $outputObject;

    /**
     * @var integer
     */
    private $annotationAdapterType;

    /**
     * @var Transforms
     */
    private $transforms;

    /**
     * @var boolean
     */
    private $validation;

    /**
     * @var array
     */
    private $skipAttributes;

    /**
     * @return string
     */
    public function getOutputClass() {
        return $this->outputClass;
    }

    /**
     * @param string $class
     * @return $this
     */
    protected function setOutputClassInternal($class) {
        $this->outputClass = $class;
        $this->outputObject = new $class();

        return $this;
    }

    /**
     * @return object
     */
    public function getOutputObject() {
        return $this->outputObject;
    }

    /**
     * @param object $object
     * @return $this
     */
    protected function setOutputObjectInternal($object) {
        $this->outputObject = $object;
        $this->outputClass = get_class($object);

        return $this;
    }

    /**
     * @return integer
     */
    public function getAnnotationAdapterType() {
        return $this->annotationAdapterType;
    }

    /**
     * @param integer $adapter
     * @return $this
     */
    public function setAnnotationAdapterType($adapter) {
        $this->annotationAdapterType = $adapter;

        return $this;
    }

    /**
     * @return Transforms
     */
    public function getTransforms() {
        return $this->transforms;
    }

    /**
     * @param Transforms|null $transforms
     * @return $this
     */
    public function setTransforms(Transforms $transforms = null) {
        $this->transforms = $transforms;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getValidation() {
        return $this->validation;
    }

    /**
     * @return $this
     */
    public function disableValidation() {
        $this->validation = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function enableValidation() {
        $this->validation = true;

        return $this;
    }

    /**
     * @param boolean $validation
     * @return $this
     */
    public function setValidation($validation) {
        $this->validation = $validation;

        return $this;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setSkipAttributes(array $attributes = []) {
        $this->skipAttributes = $attributes;

        return $this;
    }

    /**
     * @return array
     */
    public function getSkipAttributes() {
        return $this->skipAttributes;
    }

    /**
     * @param string|object $outputClassOrObject
     * @param integer $adapter
     */
    public function __construct($outputClassOrObject, $adapter = self::MEMORY_ANNOTATION_ADAPTER) {
        $this->setOutputClassOrObject($outputClassOrObject)
            ->setAnnotationAdapterType($adapter)
            ->setValidation(true)
            ->setSkipAttributes();
    }

    /**
     * @param string|object $outputClassOrObject
     * @return $this
     */
    private function setOutputClassOrObject($outputClassOrObject) {
        if (is_object($outputClassOrObject)) {
            $this->setOutputObjectInternal($outputClassOrObject);
        } else {
            $this->setOutputClassInternal($outputClassOrObject);
        }

        return $this;
    }

}