<?php

namespace Tests;

use \stdClass,

    \Tests\Dummy\Tree,

    \PhMap\Mapper,
    \PhMap\Transform,
    \PhMap\Transforms,
    \PhMap\Wrapper\Json;

/**
 * Class JsonMapperTest
 * @package Tests
 */
class JsonMapperTest extends MapperTest {

    /**
     * @return stdClass
     * @static
     */
    protected static function getTreeDecodedToObject() {
        return json_decode(self::getTreeJson());
    }

    /**
     * @return stdClass
     * @static
     */
    protected static function getTreeTransformedDecodedToObject() {
        return json_decode(self::getTreeTransformedJson());
    }

    /**
     * @return void
     */
    public function testMapperWithMemoryAdapter() {
        $class = '\Tests\Dummy\Tree';

        /** @var Tree $objectMapped */
        $objectMapped = (new Json(self::getTreeJson(), $class))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    /**
     * @return void
     */
    public function testMapperWithFilesAdapter() {
        $class = '\Tests\Dummy\Tree';

        /** @var Tree $objectMapped */
        $objectMapped = (new Json(self::getTreeJson(), $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    /**
     * @return void
     */
    public function testMapperWithInstances() {
        $mapper = new Json(self::getTreeJson(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER);
        /** @var Tree $objectMapped */
        $objectMapped = $mapper->map();

        $this->assertEquals($mapper->getOutputClass(), 'Tests\Dummy\Tree');
        $this->assertEquals($objectMapped, self::getTree());
    }

    /**
     * @return void
     */
    public function testReusable() {
        $mapper = new Json(self::getTreeJson(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER);

        $objectMapped = $mapper->map();
        $this->assertEquals($objectMapped, self::getTree());

        $class = '\Tests\Dummy\Branch';
        $objectMapped = $mapper
            ->setInputJsonString(self::getBranchJson())
            ->setOutputClass($class)
            ->setAnnotationAdapterType(Mapper::FILES_ANNOTATION_ADAPTER)
            ->map();
        $this->assertEquals($objectMapped, self::getBranch());
    }

    /**
     * @return void
     */
    public function testTransforms() {
        $inputJson = self::getTreeTransformedJson();

        $transforms = (new Transforms())
            ->add(
                (new Transform())->setInputFieldName('nameTransformed')->setOutputFieldName('name')
            )
            ->add(
                (new Transform())->setInputFieldName('branchTransformed')->setOutputFieldName('branch')
                    ->setTransforms(
                        (new Transforms())->add(
                            (new Transform())->setInputFieldName('leavesTransformed')->setOutputFieldName('leaves')
                        )
                    )
            );

        $objectMapped = (new Json($inputJson, new Tree(), Mapper::FILES_ANNOTATION_ADAPTER))
            ->setTransforms($transforms)
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    /**
     * @return void
     */
    public function testSkipAttributes() {
        $inputStructure = self::getTreeJson();

        $skipAttributes = [
            'name',
            'branch.length'
        ];

        $objectMapped = (new Json($inputStructure, new Tree(), Mapper::FILES_ANNOTATION_ADAPTER))
            ->setSkipAttributes($skipAttributes)
            ->map();
        $this->assertEquals(
            $objectMapped,
            self::getTree()
                ->setName(null)
                ->setBranch(
                    self::getBranch()
                        ->setLength(null)
                )
        );
    }

    /**
     * @throws \PhMap\Exception\FieldValidator\MustBeSimple
     * @return void
     */
    public function testMustBeSimpleException() {
        $class = '\Tests\Dummy\Tree';

        $this->setExpectedException(
            '\PhMap\Exception\FieldValidator\MustBeSimple',
            'Passed "height" field of "' . $class . '" class must be a simple type'
        );

        $object = self::getTreeDecodedToObject();

        $height = new stdClass();
        $height->foo = 1;
        $height->bar = 2;
        $object->height = $height;

        $json = json_encode($object);

        (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    /**
     * @throws \PhMap\Exception\FieldValidator\MustBeSequence
     * @return void
     */
    public function testMustBeSequenceException() {
        $class = '\Tests\Dummy\Tree';

        $this->setExpectedException(
            '\PhMap\Exception\FieldValidator\MustBeSequence',
            'Passed "leaves" field of "\Tests\Dummy\Branch" class must be sequence'
        );

        $object = self::getTreeDecodedToObject();
        $object->branch->leaves = new stdClass();

        $json = json_encode($object);

        (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    /**
     * @throws \PhMap\Exception\FieldValidator\MustBeObject
     * @return void
     */
    public function testMustBeObjectException() {
        $class = '\Tests\Dummy\Tree';

        $this->setExpectedException(
            '\PhMap\Exception\FieldValidator\MustBeObject',
            'Passed "branch" field of "' . $class . '" class must be an object'
        );

        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();
        $object->branch = 1;

        $json = json_encode($object);

        (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    /**
     * @throws \PhMap\Exception\FieldValidator\UnknownField
     * @return void
     */
    public function testUnknownFieldException() {
        $class = '\Tests\Dummy\Tree';

        $this->setExpectedException(
            '\PhMap\Exception\FieldValidator\UnknownField',
            '"' . $class . '" class has not a "foo" field'
        );

        $object = self::getTreeDecodedToObject();
        $object->foo = 1;

        $json = json_encode($object);

        (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    /**
     * @throws \PhMap\Exception\InvalidJson
     * @return void
     */
    public function testInvalidJson() {
        $json = 1;

        $this->setExpectedException(
            '\PhMap\Exception\InvalidJson',
            'Json "' . $json . '" is invalid'
        );

        $class = '\Tests\Dummy\Tree';

        (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    /**
     * @throws \PhMap\Exception\UnknownAnnotationAdapter
     * @return void
     */
    public function testUnknownAdapter() {
        $this->setExpectedException(
            '\PhMap\Exception\UnknownAnnotationAdapter',
            'Unknown annotation adapter'
        );

        $class = '\Tests\Dummy\Tree';

        $adapter = 10;

        (new Json(self::getTreeJson(), $class, $adapter))
            ->map();
    }

    /**
     * @return void
     */
    public function testDisableMustBeSimpleException() {
        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $height = new stdClass();
        $height->foo = 1;
        $height->bar = 2;

        $object->height = $height;

        $json = json_encode($object);

        $objectMapped = (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->disableValidation()
            ->map();
        $objectExpected = self::getTree();
        $objectExpected->setHeight(null);
        $this->assertEquals($objectMapped, $objectExpected);
    }

    /**
     * @return void
     */
    public function testDisableMustBeSequenceException() {
        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $object->branch->leaves = new stdClass();

        $json = json_encode($object);

        $objectMapped = (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->disableValidation()
            ->map();
        $objectExpected = self::getTree();
        $objectExpected->getBranch()->setLeaves();
        $this->assertEquals($objectMapped, $objectExpected);
    }

    /**
     * @return void
     */
    public function testDisableMustBeObjectException() {
        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $object->branch = 1;

        $json = json_encode($object);

        $objectMapped = (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->disableValidation()
            ->map();
        $objectExpected = self::getTree();
        $objectExpected->setBranch(null);
        $this->assertEquals($objectMapped, $objectExpected);
    }

    /**
     * @return void
     */
    public function testDisableUnknownFieldException() {
        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeTransformedDecodedToObject();

        $json = json_encode($object);

        $objectMapped = (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->disableValidation()
            ->map();
        $objectExpected = self::getTree()->setBranch(null)->setName(null);
        $this->assertEquals($objectMapped, $objectExpected);
    }

}