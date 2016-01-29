<?php

namespace PhMap;

use \stdClass,

    \PhMap\Mapper\Smart;

/**
 * Class MapperTrait
 * @package PhMap
 */
trait MapperTrait {

    /**
     * @param array|stdClass|string $value
     * @param integer $adapter
     * @return $this
     */
    public static function staticMap($value, $adapter = Mapper::MEMORY_ANNOTATION_ADAPTER) {
        return (new Smart($value, get_called_class(), $adapter))
            ->map();
    }

    /**
     * @param array|stdClass|string $value
     * @param integer $adapter
     * @return $this
     */
    public function map($value, $adapter = Mapper::MEMORY_ANNOTATION_ADAPTER) {
        return (new Smart($value, $this, $adapter))
            ->map();
    }

}