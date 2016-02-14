<?php

namespace PhMap\Wrapper;

use \PhMap\Mapper,
    \PhMap\Wrapper,
    \PhMap\Mapper\Structure\Object as ObjectMapper,
    \PhMap\Exception\InvalidJson;

/**
 * Class Json
 * @package PhMap\Wrapper
 */
class Json extends Wrapper {

    /**
     * @var string
     */
    private $inputJsonString;

    /**
     * @var object
     */
    private $inputObject;

    /**
     * @return string
     */
    public function getInputJsonString() {
        return $this->inputJsonString;
    }

    /**
     * @param string $inputJsonString
     * @return $this
     */
    public function setInputJsonString($inputJsonString) {
        $this->inputJsonString = $inputJsonString;

        return $this->createInputObject();
    }

    /**
     * @return object
     */
    private function getInputObject() {
        return $this->inputObject;
    }

    /**
     * @param object $inputObject
     * @return $this
     */
    private function setInputObject($inputObject) {
        $this->inputObject = $inputObject;

        return $this;
    }

    /**
     * @param string $inputJsonString
     * @param string|object $outputClassOrObject
     * @param integer $adapter
     */
    public function __construct(
        $inputJsonString,
        $outputClassOrObject,
        $adapter = Mapper::MEMORY_ANNOTATION_ADAPTER
    ) {
        $this
            ->setInputJsonString($inputJsonString)
            ->createInputObject()
            ->createMapper($outputClassOrObject, $adapter);
    }

    /**
     * @return $this
     * @throws InvalidJson
     */
    private function createInputObject() {
        $object = json_decode($this->getInputJsonString());

        if (is_object($object)) {
            return $this->setInputObject($object);
        }

        throw new InvalidJson($this->getInputJsonString());
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
    protected function createMapper(
        $outputClassOrObject,
        $adapter = Mapper::MEMORY_ANNOTATION_ADAPTER
    ) {
        $mapper = new ObjectMapper(
            $this->getInputObject(),
            $outputClassOrObject,
            $adapter
        );

        return $this->setMapper($mapper);
    }

}