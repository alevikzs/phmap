<?php

namespace PhMap;

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
     * @param string $class
     * @param integer $adapter
     */
    public function __construct($class, $adapter = self::MEMORY_ANNOTATION_ADAPTER) {
        $this
            ->setClass($class)
            ->setAnnotationAdapterType($adapter);
    }

}