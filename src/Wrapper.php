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
        return $this->getMapper()
            ->setOutputObject($object);
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
        return $this->getMapper()
            ->setAnnotationAdapterType($adapter);
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