<?php

namespace PhMap;

/**
 * Class Wrapper
 * @abstract
 * @package PhMap
 */
abstract class Wrapper implements MapperInterface {

    /**
     * @var Mapper
     */
    private $mapper;

    /**
     * @return Mapper
     */
    protected function getMapper() {
        return $this->mapper;
    }

    /**
     * @param MapperInterface $mapper
     * @return $this
     */
    protected function setMapper(MapperInterface $mapper) {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * @return string
     */
    public function getOutputClass() {
        return $this->getMapper()
            ->getOutputClass();
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setOutputClass($class) {
        $this->getMapper()
            ->setOutputClass($class);

        return $this;
    }

    /**
     * @return object
     */
    public function getOutputObject() {
        return $this->getMapper()
            ->getOutputObject();
    }

    /**
     * @param object $object
     * @return $this
     */
    public function setOutputObject($object) {
        $this->getMapper()
            ->setOutputObject($object);

        return $this;
    }

    /**
     * @return integer
     */
    public function getAnnotationAdapterType() {
        return $this->getMapper()
            ->getAnnotationAdapterType();
    }

    /**
     * @param integer $adapter
     * @return $this
     */
    public function setAnnotationAdapterType($adapter) {
        $this->getMapper()
            ->setAnnotationAdapterType($adapter);

        return $this;
    }

    /**
     * @return Transforms
     */
    public function getTransforms() {
        return $this->getMapper()
            ->getTransforms();
    }

    /**
     * @param Transforms $transforms
     * @return $this
     */
    public function setTransforms(Transforms $transforms) {
        $this->getMapper()
            ->setTransforms($transforms);

        return $this;
    }

    /**
     * @return boolean
     */
    public function getValidation() {
        return $this->getMapper()
            ->getValidation();
    }

    /**
     * @return $this
     */
    public function disableValidation() {
        $this->getMapper()
            ->disableValidation();

        return $this;
    }

    /**
     * @return $this
     */
    public function enableValidation() {
        $this->getMapper()
            ->enableValidation();

        return $this;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setSkipAttributes(array $attributes = []) {
        $this->getMapper()
            ->setSkipAttributes($attributes);

        return $this;
    }

    /**
     * @return array
     */
    public function getSkipAttributes() {
        return $this->getMapper()
            ->getSkipAttributes();
    }

    /**
     * @return object
     */
    public function map() {
        return $this->getMapper()
            ->map();
    }

    /**
     * @param string|object $outputClassOrObject
     * @param integer $adapter
     * @return $this
     */
    abstract protected function createMapper(
        $outputClassOrObject,
        $adapter = Mapper::MEMORY_ANNOTATION_ADAPTER
    );

}