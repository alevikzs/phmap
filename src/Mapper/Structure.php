<?php

namespace PhMap\Mapper;

use \stdClass,

    \Phalcon\Annotations\Annotation,
    \Phalcon\Annotations\Reflection,
    \Phalcon\Annotations\Collection as Annotations,
    \Phalcon\Annotations\Adapter\Memory as MemoryAdapter,

    \PhMap\Mapper,
    \PhMap\Exception\FieldValidator\UnknownField as UnknownFieldException,
    \PhMap\Exception\FieldValidator\MustBeSimple as MustBeSimpleException,
    \PhMap\Exception\FieldValidator\MustBeSequence as MustBeSequenceException,
    \PhMap\Exception\FieldValidator\MustBeObject as MustBeObjectException;

/**
 * Class Structure
 * @package PhMap\Mapper
 */
abstract class Structure extends Mapper {

    /**
     * @var Reflection
     */
    private $reflector;

    /**
     * @var array|stdClass
     */
    private $structure;

    /**
     * @return Reflection
     */
    protected function getReflector() {
        return $this->reflector;
    }

    /**
     * @param Reflection $reflector
     * @return $this
     */
    protected function setReflector(Reflection $reflector) {
        $this->reflector = $reflector;

        return $this;
    }

    /**
     * @return array|stdClass
     */
    public function getStructure() {
        return $this->structure;
    }

    /**
     * @param array|stdClass $structure
     * @return $this
     */
    public function setStructure($structure) {
        $this->structure = $structure;

        return $this;
    }

    /**
     * @param array|stdClass $structure
     * @param string $class
     */
    public function __construct($structure, $class) {
        $this
            ->setStructure($structure)
            ->setReflector(
                (new MemoryAdapter())
                    ->get($class)
            );

        parent::__construct($class);
    }

    /**
     * @return stdClass
     * @throws UnknownFieldException
     */
    public function map() {
        $class = $this->getClass();
        $instance = new $class();

        $methodsAnnotations = $this
            ->getReflector()
            ->getMethodsAnnotations();

        foreach ($this->getStructure() as $attribute => $value) {
            $setter = $this->createSetter($attribute);

            if ($this->hasAttribute($attribute)) {
                /** @var Annotations $methodAnnotations */
                $methodAnnotations = $methodsAnnotations[$setter];

                $valueToMap = $this->buildValueToMap($attribute, $value, $methodAnnotations);

                $instance->$setter($valueToMap);
            } else {
                throw new UnknownFieldException($attribute, $class);
            }
        }

        return $instance;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param Annotations $methodAnnotations
     * @return mixed
     * @throws MustBeSimpleException
     * @throws MustBeSequenceException
     * @throws MustBeObjectException
     */
    protected function buildValueToMap($attribute, $value, Annotations $methodAnnotations) {
        $valueToMap = $value;

        if ($methodAnnotations->has('mapper')) {
            /** @var Annotation $mapperAnnotation */
            $mapperAnnotation = $methodAnnotations->get('mapper');
            $mapperAnnotationClass = $mapperAnnotation->getArgument('class');
            $mapperAnnotationIsArray = $mapperAnnotation->getArgument('isArray');

            if ($this->isObject($value)) {
                if ($mapperAnnotationIsArray) {
                    throw new MustBeSequenceException($attribute, $this->getClass());
                } else {
                    /** @var stdClass|array $value */
                    $mapper = new static($value, $mapperAnnotationClass);
                    $valueToMap = $mapper->map();
                }
            } else {
                if ($mapperAnnotationIsArray) {
                    $valueToMap = array_map(function($val) use ($mapperAnnotationClass) {
                        return (new static($val, $mapperAnnotationClass))
                            ->map();
                    }, $value);
                } else {
                    throw new MustBeObjectException($attribute, $this->getClass());
                }
            }
        } elseif ($this->isStructure($value)) {
            throw new MustBeSimpleException($attribute, $this->getClass());
        }

        return $valueToMap;
    }

    /**
     * @param string $attribute
     * @return boolean
     */
    protected function hasAttribute($attribute) {
        $setter = $this->createSetter($attribute);
        $getter = $this->createGetter($attribute);

        return method_exists($this->getClass(), $getter)
            && method_exists($this->getClass(), $setter);
    }

    /**
     * @param string $attribute
     * @return string
     */
    protected function createSetter($attribute) {
        return 'set' . ucfirst($attribute);
    }

    /**
     * @param string $attribute
     * @return string
     */
    protected function createGetter($attribute) {
        return 'get' . ucfirst($attribute);
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    abstract protected function isObject($value);

    /**
     * @param mixed $value
     * @return boolean
     */
    abstract protected function isSequential($value);

    /**
     * @param mixed $value
     * @return boolean
     */
    abstract protected function isStructure($value);

}