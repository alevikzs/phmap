<?php

namespace PhMap;

use \PhMap\Wrapper\Smart;

/**
 * Class MapperTrait
 * @package PhMap
 */
trait MapperTrait {

    /**
     * @param array|object|string $value
     * @param integer $adapter
     * @return $this
     */
    public static function staticMap($value, $adapter = Mapper::MEMORY_ANNOTATION_ADAPTER) {
        /** @var Smart $mapper */
        static $mapper;

        if (is_null($mapper)) {
            $mapper = new Smart($value, get_called_class(), $adapter);
        } else {
            $mapper->setInputValue($value)
                ->setAnnotationAdapterType($adapter);
        }
        return $mapper->map();
    }

    /**
     * @param array|object|string $value
     * @param integer $adapter
     * @return $this
     */
    public function map($value, $adapter = Mapper::MEMORY_ANNOTATION_ADAPTER) {
        return (new Smart($value, $this, $adapter))
            ->map();
    }

}