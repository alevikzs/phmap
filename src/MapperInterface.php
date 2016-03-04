<?php

namespace PhMap;

/**
 * Interface MapperInterface
 * @package PhMap
 */
interface MapperInterface {

    /**
     * @return string
     */
    public function getOutputClass();

    /**
     * @param string $class
     * @return $this
     */
    public function setOutputClass($class);

    /**
     * @return object
     */
    public function getOutputObject();

    /**
     * @param object $object
     * @return $this
     */
    public function setOutputObject($object);

    /**
     * @return integer
     */
    public function getAnnotationAdapterType();

    /**
     * @param integer $adapter
     * @return $this
     */
    public function setAnnotationAdapterType($adapter);

    /**
     * @param Transforms $transforms
     * @return $this
     */
    public function setTransforms(Transforms $transforms);

    /**
     * @return Transforms
     */
    public function getTransforms();

    /**
     * @return $this
     */
    public function disableValidation();

    /**
     * @return $this
     */
    public function enableValidation();

    /**
     * @return boolean
     */
    public function hasValidation();

    /**
     * @param array $attributes
     * @return $this
     */
    public function setSkipAttributes(array $attributes = []);

    /**
     * @return array
     */
    public function getSkipAttributes();

    /**
     * @return object
     */
    public function map();

}