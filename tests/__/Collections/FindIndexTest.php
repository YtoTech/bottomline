<?php

namespace __\Test\Collections;

use __;

class FindIndexTest extends \PHPUnit_Framework_TestCase
{
    public function testWithNumericalKeyArray()
    {
        $data = ["native", "pale", "explain", "persuade", "elastic", "explain"];

        $this->assertEquals(2, __::findIndex($data, "explain"));
        $this->assertEquals(-1, __::findIndex($data, "nonexistent"));
    }

    public function testWithAssociativeArray()
    {
        $data = [
            "table" => "trick",
            "pen" => "defend",
            "motherly" => "wide",
            "may" => "needle",
            "sweat" => "cake",
            "sword" => "defend",
        ];

        $this->assertEquals("pen", __::findIndex($data, "defend"));
        $this->assertEquals(-1, __::findIndex($data, "nonexistent"));
    }

    public function testWithCallback()
    {
        $data = [
            "table"    => (object)["name" => "trick"],
            "pen"      => (object)["name" => "defend"],
            "motherly" => (object)["name" => "wide"],
            "may"      => (object)["name" => "needle"],
            "sweat"    => (object)["name" => "cake"],
            "sword"    => (object)["name" => "defend"],
        ];

        $this->assertEquals("pen", __::findIndex($data, static function ($object) {
            return $object->name === "defend";
        }));
        $this->assertEquals(-1, __::findIndex($data, static function ($value) {
            return $value === "potato";
        }));
    }
}
