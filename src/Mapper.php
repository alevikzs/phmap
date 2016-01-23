<?php

namespace PhMap;

/**
 * Class Mapper
 * @package PhMap
 */
abstract class Mapper implements MapperInterface {

    /**
     * @var string
     */
    private $class;

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
    public function setClass($class) {
        $this->class = $class;

        return $this;
    }

    /**
     * @param string $class
     */
    public function __construct($class) {
        $this->setClass($class);
    }

}