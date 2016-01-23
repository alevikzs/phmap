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
     * @param string $class
     * @return self
     */
    public function setClass($class);

    /**
     * @return stdClass
     */
    public function map();

}