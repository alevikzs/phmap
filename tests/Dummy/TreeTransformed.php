<?php

namespace Tests\Dummy;

use \JsonSerializable,

    \PhMap\MapperTrait;

/**
 * Class Tree
 * @package Tests\Dummy
 */
class TreeTransformed implements JsonSerializable {

    use MapperTrait;

    /**
     * @var double
     */
    private $height;

    /**
     * @var string
     */
    private $nameTransformed;

    /**
     * @var Branch
     */
    private $branchTransformed;

    /**
     * @var Branch
     */
    private $something;

    /**
     * @return float
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * @param float $height
     * @return $this
     */
    public function setHeight($height) {
        $this->height = $height;

        return $this;
    }

    /**
     * @return string
     */
    public function getNameTransformed() {
        return $this->nameTransformed;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setNameTransformed($name) {
        $this->nameTransformed = $name;

        return $this;
    }

    /**
     * @return BranchTransformed
     */
    public function getBranchTransformed() {
        return $this->branchTransformed;
    }

    /**
     * @param BranchTransformed|null $branch
     * @mapper(class="\Tests\Dummy\BranchTransformed")
     * @return $this
     */
    public function setBranchTransformed(BranchTransformed $branch = null) {
        $this->branchTransformed = $branch;

        return $this;
    }

    /**
     * @param double $height
     * @param string $name
     * @param BranchTransformed $branch
     */
    public function __construct($height = null, $name = null, BranchTransformed $branch = null) {
        $this->height = $height;
        $this->nameTransformed = $name;
        $this->branchTransformed = $branch;
        $this->something = 'something';
    }

    /**
     * @return array
     */
    public function jsonSerialize() {
        return [
            'nameTransformed' => $this->getNameTransformed(),
            'height' => $this->getHeight(),
            'branchTransformed' => $this->getBranchTransformed()
        ];
    }

}