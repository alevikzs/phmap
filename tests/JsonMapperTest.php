<?php

namespace Tests;

use \stdClass,

    \Tests\Dummy\Tree,
    \Tests\Dummy\Branch,

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
     */
    protected static function getTreeDecodedToObject() {
        return json_decode(self::getTreeJson());
    }

    /**
     * @return stdClass
     */
    protected static function getTreeTransformedDecodedToObject() {
        return json_decode(self::getTreeTransformedJson());
    }

    public function testMapperWithMemoryAdapter() {
        $class = '\Tests\Dummy\Tree';

        /** @var Tree $objectMapped */
        $objectMapped = (new Json(self::getTreeJson(), $class))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testMapperWithFilesAdapter() {
        $class = '\Tests\Dummy\Tree';

        /** @var Tree $objectMapped */
        $objectMapped = (new Json(self::getTreeJson(), $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testMapperWithInstances() {
        /** @var Tree $objectMapped */
        $objectMapped = (new Json(self::getTreeJson(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testReusable() {
        $mapper = new Json(self::getTreeJson(), new Tree(), Mapper::FILES_ANNOTATION_ADAPTER);

        $objectMapped = $mapper->map();
        $this->assertEquals($objectMapped, self::getTree());

        $objectMapped = $mapper
            ->setInputJsonString(self::getBranchJson())
            ->setOutputObject(new Branch())
            ->setAnnotationAdapterType(Mapper::FILES_ANNOTATION_ADAPTER)
            ->map();
        $this->assertEquals($objectMapped, self::getBranch());
    }

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
            ->map($transforms);
        $this->assertEquals($objectMapped, self::getTree());
    }

    public function testMustBeSimpleException() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeSimple');

        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $height = new stdClass();
        $height->foo = 1;
        $height->bar = 2;
        $object->height = $height;

        $json = json_encode($object);

        (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testMustBeSequenceException() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeSequence');

        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();
        $object->branch->leaves = new stdClass();

        $json = json_encode($object);

        (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testMustBeObjectException() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\MustBeObject');

        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();
        $object->branch = 1;

        $json = json_encode($object);

        (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

    public function testUnknownFieldException() {
        $this->setExpectedException('\PhMap\Exception\FieldValidator\UnknownField');

        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();
        $object->foo = 1;

        $json = json_encode($object);

        (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map();
    }

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

    public function testDisableMustBeSimpleException() {
        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $height = new stdClass();
        $height->foo = 1;
        $height->bar = 2;

        $object->height = $height;

        $json = json_encode($object);

        $objectMapped = (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map(null, false);
        $objectExpected = self::getTree();
        $objectExpected->setHeight(null);
        $this->assertEquals($objectMapped, $objectExpected);
    }

    public function testDisableMustBeSequenceException() {
        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $object->branch->leaves = new stdClass();

        $json = json_encode($object);

        $objectMapped = (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map(null, false);
        $objectExpected = self::getTree();
        $objectExpected->getBranch()->setLeaves();
        $this->assertEquals($objectMapped, $objectExpected);
    }

    public function testDisableMustBeObjectException() {
        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeDecodedToObject();

        $object->branch = 1;

        $json = json_encode($object);

        $objectMapped = (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map(null, false);
        $objectExpected = self::getTree();
        $objectExpected->setBranch(null);
        $this->assertEquals($objectMapped, $objectExpected);
    }

    public function testDisableUnknownFieldException() {
        $class = '\Tests\Dummy\Tree';

        $object = self::getTreeTransformedDecodedToObject();

        $json = json_encode($object);

        $objectMapped = (new Json($json, $class, Mapper::FILES_ANNOTATION_ADAPTER))
            ->map(null, false);

        $objectExpected = self::getTree()->setBranch(null)->setName(null);

        $this->assertEquals($objectMapped, $objectExpected);
    }

}