<?php

namespace PhMap;

use \ArrayIterator;

/**
 * Class Transforms
 * @package PhMap
 */
class Transforms extends ArrayIterator {

    /**
     * @param Transform $transform
     * @return $this
     */
    public function add(Transform $transform) {
        $this->append($transform);

        return $this;
    }

    /**
     * @param string $field
     * @return Transform|null
     */
    public function findByInputFieldName($field) {
        /** @var Transform $transform */
        foreach ($this as $transform) {
            if ($transform->getInputFieldName() === $field) {
                return $transform;
            }
        }

        return null;
    }

}