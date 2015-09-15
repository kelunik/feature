<?php

namespace Kelunik\Feature;

use PHPUnit_Framework_TestCase;

class ContextTest extends PHPUnit_Framework_TestCase {
    /** @var Context */
    private $context;

    public function setUp() {
        $this->context = new Context;
    }

    public function testGet() {
        $this->assertNull($this->context->get("random"));

        $this->context->set("random", true);
        $this->assertTrue($this->context->get("random"));
    }
}