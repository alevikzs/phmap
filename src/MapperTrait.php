<?php

namespace PhMap;

use \PhMap\Wrapper\Smart;

/**
 * Class MapperTrait
 * @package PhMap
 */
trait MapperTrait {

    /**
     * @var Smart
     */
    private static $mapper;

    /**
     * @param array|object|string $value
     * @param integer $adapter
     * @return Smart
     * @static
     */
    public static function staticMapper($value, $adapter = Mapper::MEMORY_ANNOTATION_ADAPTER) {
        return self::updateMapper($value, $adapter);
    }

    /**
     * @param array|object|string $value
     * @param integer $adapter
     * @return Smart
     * @static
     */
    public function mapper($value, $adapter = Mapper::MEMORY_ANNOTATION_ADAPTER) {
        return self::updateMapper($value, $adapter);
    }

    /**
     * @param $value
     * @param int $adapter
     * @return Smart
     */
    private static function updateMapper($value, $adapter = Mapper::MEMORY_ANNOTATION_ADAPTER) {
        if (is_null(self::$mapper)) {
            self::$mapper = new Smart($value, get_called_class(), $adapter);
        } else {
            self::$mapper
                ->setInputValue($value)
                ->setAnnotationAdapterType($adapter);
        }

        return self::$mapper;
    }

}