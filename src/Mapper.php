<?php

namespace PhMap;

use \stdClass,

    \PhMap\Exception\InvalidValueToMap;

/**
 * Class Mapper
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
    private $class;

    /**
     * @var stdClass
     */
    private $instance;

    /**
     * @var integer
     */
    private $annotationAdapterType;

    /**
     * @return string
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * @param string $class
     * @return $this
     */
    private function setClass($class) {
        $this->class = $class;

        return $this;
    }

    /**
     * @return string
     */
    public function getInstance() {
        return $this->instance;
    }

    /**
     * @param string $instance
     * @return $this
     */
    private function setInstance($instance) {
        $this->instance = $instance;

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
    private function setAnnotationAdapterType($adapter) {
        $this->annotationAdapterType = $adapter;

        return $this;
    }

    /**
     * @param string|stdClass $toMap
     * @param integer $adapter
     * @throws InvalidValueToMap
     */
    public function __construct($toMap, $adapter = self::MEMORY_ANNOTATION_ADAPTER) {
        if (is_object($toMap)) {
            $instance = $toMap;
            $class = get_class($instance);
        } elseif(is_string($toMap) && class_exists($toMap)) {
            $class = $toMap;
            $instance = new $class();
        } else {
            throw new InvalidValueToMap($toMap);
        }

        $this
            ->setClass($class)
            ->setInstance($instance)
            ->setAnnotationAdapterType($adapter);
    }

}