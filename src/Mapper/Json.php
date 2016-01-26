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

        return $this->createObject();
    }

    /**
     * @return stdClass
     */
    private function getObject() {
        return $this->object;
    }

    /**
     * @param stdClass $object
     * @return $this
     */
    private function setObject(stdClass $object) {
        $this->object = $object;

        return $this;
    }

    /**
     * @param string $string
     * @param string|stdClass $toMap
     * @param integer $adapter
     */
    public function __construct($string, $toMap, $adapter = self::MEMORY_ANNOTATION_ADAPTER) {
        parent::__construct($toMap, $adapter);

        $this
            ->setString($string)
            ->createObject();
    }

    /**
     * @return $this
     * @throws InvalidJson
     */
    private function createObject() {
        $object = json_decode($this->getString());

        if (is_object($object)) {
            return $this->setObject($object);
        }

        throw new InvalidJson($this->getString());
    }

    /**
     * @return stdClass
     */
    public function map() {
        return (new Object(
            $this->getObject(),
            $this->getClass(),
            $this->getAnnotationAdapterType()
        ))->map();
    }

}