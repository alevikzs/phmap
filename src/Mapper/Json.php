<?php

namespace PhMap\Mapper;

use \stdClass,

    \PhMap\Mapper,
    \PhMap\Mapper\Structure\Object,
    \PhMap\Exception\InvalidJson;

/**
 * Class Json
 * @package PhMap\Mapper
 */
class Json extends Mapper {

    /**
     * @var string
     */
    private $string;

    /**
     * @var stdClass
     */
    private $object;

    /**
     * @return string
     */
    public function getString() {
        return $this->string;
    }

    /**
     * @param string $string
     * @return $this
     */
    public function setString($string) {
        $this->string = $string;

        return $this;
    }

    /**
     * @return stdClass
     */
    private function getObject() {
        return $this->object;
    }

    /**
     * @param string $object
     * @return $this
     */
    private function setObject($object) {
        $this->object = $object;

        return $this;
    }

    /**
     * @param string $string
     * @param string $class
     */
    public function __construct($string, $class) {
        $object = $this->toObject($string);

        $this
            ->setClass($class)
            ->setString($string)
            ->setObject($object);

    }

    /**
     * @param string $string
     * @return stdClass
     * @throws InvalidJson
     */
    private function toObject($string) {
        $object = json_decode($string);

        if (is_object($object)) {
            return $object;
        }

        throw new InvalidJson($string);
    }

    /**
     * @return stdClass
     */
    public function map() {
        return (new Object(
            $this->getObject(),
            $this->getClass()
        ))->map();
    }

}