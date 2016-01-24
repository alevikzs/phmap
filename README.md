#PhMap#

**Build Status:** [![Build Status](https://secure.travis-ci.org/alevikzs/phmap.png?branch=master)](http://travis-ci.org/alevikzs/phmap)

##About##

The PhMap is a library for creating objects from json strings, associative arrays and objects(the 
properties of this objects must be a public). The PhMap is based on 
[phalcon annotations](https://docs.phalconphp.com/en/latest/reference/annotations.html).

##Requirements##

* PHP >= 5.4 && < 7.0
* Phalcon >= 2.0

##Installation##

1. Require the package and its dependencies with composer: ```$ composer require alevikzs/phmap```
2. Install Phalcon framework. Detail guide is [here](https://phalconphp.com/en/download).

##Examples##

```php
class Tree {

    private $height;
    private $name;
    private $branch;

    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getBranch() {
        return $this->branch;
    }

    /**
     * @mapper(class="\Tests\Dummy\Branch")
     */
    public function setBranch(Branch $branch) {
        $this->branch = $branch;
    }
    
}
```

As you can see, if some property of you object has type of another class - you must declare an annotation ```@mapper``` 
for setter method of this property. This annotation has two arguments: ```class``` and ```isArray```. First argument is 
a string with class name and second is a boolean value that indicates whether the your property value is array or not.

```php
class Branch {

    private $length;
    private $leaves;
    
    public function getLength() {
        return $this->length;
    }

    public function setLength($length) {
        $this->length = $length;
    }

    public function getLeaves() {
        return $this->leaves;
    }

    /**
     * @mapper(class="\Tests\Dummy\Leaf", isArray=true)
     */
    public function setLeaves(array $leaves) {
        $this->leaves = $leaves;
    }

}

class Leaf {

    private $height;
    private $width;
    
    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function getWidth() {
        return $this->width;
    }

    public function setWidth($width) {
        $this->width = $width;
    }

}
```

Create object of Tree class from json string:

```php
$result = (new \PhMap\Mapper\Json($json, 'Tree'))->map();
```

Create object of Tree class from associative array:

```php
$result = (new \PhMap\Mapper\Structure\Associative($array, 'Tree'))->map();
```

Create object of Tree class from another object:

```php
$result = (new \PhMap\Mapper\Structure\Object($object, 'Tree'))->map();
```

You can use ```php \PhMap\Mapper\Smart``` if you don't know what type of you value. In this case mapping instructions 
will be apply automatically:

```php
$result = (new \PhMap\Mapper\Smart($value, 'Tree'))->map();
```

##The MIT License (MIT)##

**Copyright (c) 2016 Alexey Novikov <alekseeey@gmail.com>**

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.