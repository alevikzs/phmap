<?php

namespace PhMap;

/**
 * Class Transforms
 * @package PhMap
 */
class Transform {

    /**
     * @var string
     */
    private $inputFieldName;

    /**
     * @var string
     */
    private $outputFieldName;

    /**
     * @var Transforms
     */
    private $transforms;

    /**
     * @return string
     */
    public function getInputFieldName() {
        return $this->inputFieldName;
    }

    /**
     * @param string $inputFieldName
     * @return $this
     */
    public function setInputFieldName($inputFieldName) {
        $this->inputFieldName = $inputFieldName;

        return $this;
    }

    /**
     * @return string
     */
    public function getOutputFieldName() {
        return $this->outputFieldName;
    }

    /**
     * @param string $outputFieldName
     * @return $this
     */
    public function setOutputFieldName($outputFieldName) {
        $this->outputFieldName = $outputFieldName;

        return $this;
    }

    /**
     * @return Transforms
     */
    public function getTransforms() {
        return $this->transforms;
    }

    /**
     * @param Transforms $transforms
     * @return $this
     */
    public function setTransforms(Transforms $transforms) {
        $this->transforms = $transforms;

        return $this;
    }

}