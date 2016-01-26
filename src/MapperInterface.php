<?php

namespace PhMap;

use \stdClass;

/**
 * Interface MapperInterface
 * @package PhMap
 */
interface MapperInterface {

    /**
     * @return string
     */
    public function getClass();

    /**
     * @return stdClass
     */
    public function getInstance();

    /**
     * @return integer
     */
    public function getAnnotationAdapterType();

    /**
     * @return stdClass
     */
    public function map();

}