<?php

namespace Tests\Dummy;

use \JsonSerializable;

/**
 * Class BranchTransformed
 * @package Tests\Dummy
 */
class BranchTransformed implements JsonSerializable {

    /**
     * @var double
     */
    private $length;

    /**
     * @var Leaf[]
     */
    private $leavesTransformed;

    /**
     * @return float
     */
    public function getLength() {
        return $this->length;
    }

    /**
     * @param float $length
     * @return $this
     */
    public function setLength($length) {
        $this->length = $length;

        return $this;
    }

    /**
     * @return Leaf[]
     */
    public function getLeavesTransformed() {
        return $this->leavesTransformed;
    }

    /**
     * @param Leaf[] $leaves
     * @mapper(class="\Tests\Dummy\Leaf", isArray=true)
     * @return $this
     */
    public function setLeavesTransformed(array $leaves = []) {
        $this->leavesTransformed = $leaves;

        return $this;
    }

    /**
     * @param double $length
     * @param array $leaves
     */
    public function __construct($length = null, array $leaves = []) {
        $this->length = $length;
        $this->leavesTransformed = $leaves;
    }

    /**
     * @return array
     */
    public function jsonSerialize() {
        return [
            'length' => $this->getLength(),
            'leaves' => $this->getLeavesTransformed()
        ];
    }

}