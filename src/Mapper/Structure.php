<?php

namespace PhMap\Mapper;

use \Phalcon\Annotations\Annotation,
    \Phalcon\Annotations\Reflection,
    \Phalcon\Annotations\Collection as Annotations,
    \Phalcon\Annotations\AdapterInterface,
    \Phalcon\Annotations\Adapter\Memory,
    \Phalcon\Annotations\Adapter\Files,
    \Phalcon\Annotations\Adapter\Apc,
    \Phalcon\Annotations\Adapter\Xcache,

    \PhMap\Mapper,
    \PhMap\Exception\UnknownAnnotationAdapter,
    \PhMap\Exception\FieldValidator\UnknownField as UnknownFieldException,
    \PhMap\Exception\FieldValidator\MustBeSimple as MustBeSimpleException,
    \PhMap\Exception\FieldValidator\MustBeSequence as MustBeSequenceException,
    \PhMap\Exception\FieldValidator\MustBeObject as MustBeObjectException;

/**
 * Class Structure
 * @abstract
 * @package PhMap\Mapper
 */
abstract class Structure extends Mapper {

    /**
     * @var array|object
     */
    private $structure;

    /**
     * @var AdapterInterface
     */
    private $annotationAdapter;

    /**
     * @var Reflection
     */
    private $reflector;

    /**
     * @return array|object
     */
    protected function getInputStructure() {
        return $this->structure;
    }

    /**
     * @param array|object $structure
     * @return $this
     */
    protected function setInputStructure($structure) {
        $this->structure = $structure;

        return $this;
    }

    /**
     * @return Reflection
     */
    private function getReflector() {
        return $this->reflector;
    }

    /**
     * @param Reflection $reflector
     * @return $this
     */
    private function setReflector(Reflection $reflector) {
        $this->reflector = $reflector;

        return $this;
    }

    /**
     * @return AdapterInterface
     */
    private function getAnnotationAdapter() {
        return $this->annotationAdapter;
    }

    /**
     * @param AdapterInterface $adapter
     * @return $this
     */
    private function setAnnotationAdapter(AdapterInterface $adapter) {
        $this->annotationAdapter = $adapter;

        return $this;
    }

    /**
     * @param integer $adapter
     * @return $this
     */
    public function setAnnotationAdapterType($adapter) {
        if ($adapter !== $this->getAnnotationAdapterType()) {
            parent::setAnnotationAdapterType($adapter);

            $this->createAnnotationAdapter()
                ->updateReflector();
        }

        return $this;
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setOutputClass($class) {
        parent::setOutputClassInternal($class);

        return $this->updateReflector();
    }

    /**
     * @param object $object
     * @return $this
     */
    public function setOutputObject($object) {
        parent::setOutputObjectInternal($object);

        return $this->updateReflector();
    }

    /**
     * @param array|object $inputStructure
     * @param string|object $outputClassOrObject
     * @param integer $adapter
     */
    public function __construct(
        $inputStructure, 
        $outputClassOrObject, 
        $adapter = self::MEMORY_ANNOTATION_ADAPTER
    ) {
        parent::__construct($outputClassOrObject, $adapter);

        $this
            ->setInputStructure($inputStructure)
            ->createAnnotationAdapter()
            ->updateReflector();
    }

    /**
     * @return $this
     * @throws UnknownAnnotationAdapter
     */
    private function createAnnotationAdapter() {
        switch ($this->getAnnotationAdapterType()) {
            case self::MEMORY_ANNOTATION_ADAPTER:
                $this->setAnnotationAdapter(new Memory());
                break;
            case self::FILES_ANNOTATION_ADAPTER:
                $this->setAnnotationAdapter(new Files([
                    'annotationsDir' => $this->getCacheDirectory()
                ]));
                break;
            case self::APC_ANNOTATION_ADAPTER:
                $this->setAnnotationAdapter(new Apc());
                break;
            case self::X_CACHE_ANNOTATION_ADAPTER:
                $this->setAnnotationAdapter(new Xcache());
                break;
            default:
                throw new UnknownAnnotationAdapter();
        }

        return $this;
    }

    /**
     * @return string
     */
    private function getCacheDirectory() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
            'cache' . DIRECTORY_SEPARATOR;
    }

    /**
     * @return $this
     */
    protected function updateReflector() {
        return $this->setReflector(
            $this->getAnnotationAdapter()
                ->get($this->getOutputClass())
        );
    }

    /**
     * @param array $transforms
     * @return object
     * @throws UnknownFieldException
     */
    public function map(array $transforms = []) {
        $methodsAnnotations = $this
            ->getReflector()
            ->getMethodsAnnotations();

        foreach ($this->getInputAttributes() as $attribute => $value) {
            $transformedAttribute = isset($transforms[$attribute]['attribute'])
                ? $transforms[$attribute]['attribute'] : $attribute;

            $setter = $this->createSetter($transformedAttribute);

            if ($this->hasAttribute($transformedAttribute)) {
                /** @var Annotations $methodAnnotations */
                $methodAnnotations = $methodsAnnotations[$setter];

                $childTransforms = isset($transforms[$attribute]['transforms'])
                    ? $transforms[$attribute]['transforms'] : [];

                $valueToMap = $this->buildValueToMap(
                    $transformedAttribute,
                    $value,
                    $methodAnnotations,
                    $childTransforms
                );

                $this
                    ->getOutputObject()
                    ->$setter($valueToMap);
            } else {
                throw new UnknownFieldException($transformedAttribute, $this->getOutputClass());
            }
        }

        return $this->getOutputObject();
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param Annotations $methodAnnotations
     * @param array $transforms
     * @return mixed
     * @throws MustBeSimpleException
     * @throws MustBeSequenceException
     * @throws MustBeObjectException
     */
    protected function buildValueToMap($attribute, $value, Annotations $methodAnnotations, array $transforms = []) {
        $valueToMap = $value;

        if ($methodAnnotations->has('mapper')) {
            /** @var Annotation $mapperAnnotation */
            $mapperAnnotation = $methodAnnotations->get('mapper');
            $mapperAnnotationClass = $mapperAnnotation->getArgument('class');
            $mapperAnnotationIsArray = $mapperAnnotation->getArgument('isArray');

            if ($this->isObject($value)) {
                if ($mapperAnnotationIsArray) {
                    throw new MustBeSequenceException($attribute, $this->getOutputClass());
                } else {
                    /** @var object|array $value */
                    $mapper = new static($value, $mapperAnnotationClass);
                    $valueToMap = $mapper->map($transforms);
                }
            } else {
                if ($mapperAnnotationIsArray) {
                    $valueToMap = array_map(function($val) use ($mapperAnnotationClass, $transforms) {
                        return (new static($val, $mapperAnnotationClass))
                            ->map($transforms);
                    }, $value);
                } else {
                    throw new MustBeObjectException($attribute, $this->getOutputClass());
                }
            }
        } elseif ($this->isStructure($value)) {
            throw new MustBeSimpleException($attribute, $this->getOutputClass());
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

        return method_exists($this->getOutputClass(), $getter)
            && method_exists($this->getOutputClass(), $setter);
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

    /**
     * @return array
     */
    abstract protected function getInputAttributes();

}