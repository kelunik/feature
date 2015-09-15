<?php

namespace Kelunik\Feature;

use Kelunik\Feature\Strategy\AlwaysStrategy;
use Kelunik\Feature\Strategy\AndStrategy;
use Kelunik\Feature\Strategy\NeverStrategy;
use Kelunik\Feature\Strategy\OrStrategy;
use Kelunik\Feature\Strategy\RandomStrategy;
use Kelunik\Feature\Strategy\XorStrategy;
use PHPUnit_Framework_TestCase;
use function Amp\wait;

class FeatureTest extends PHPUnit_Framework_TestCase {
    /** @var Feature */
    private $feature;

    public function setUp() {
        $this->feature = new Feature;
    }

    public function testAlways() {
        $this->feature->add("foo", new AlwaysStrategy);
        $this->assertTrue(wait($this->feature->isEnabled("foo", new Context)));
    }

    public function testAnd() {
        $this->feature->add("foo", new AndStrategy(new AlwaysStrategy, new AlwaysStrategy));
        $this->assertTrue(wait($this->feature->isEnabled("foo", new Context)));

        $this->feature->add("foo", new AndStrategy(new AlwaysStrategy, new NeverStrategy));
        $this->assertFalse(wait($this->feature->isEnabled("foo", new Context)));

        $this->feature->add("foo", new AndStrategy(new NeverStrategy, new AlwaysStrategy));
        $this->assertFalse(wait($this->feature->isEnabled("foo", new Context)));

        $this->feature->add("foo", new AndStrategy(new NeverStrategy, new NeverStrategy));
        $this->assertFalse(wait($this->feature->isEnabled("foo", new Context)));
    }

    public function testNever() {
        $this->feature->add("foo", new NeverStrategy);
        $this->assertFalse(wait($this->feature->isEnabled("foo", new Context)));
    }

    public function testOr() {
        $this->feature->add("foo", new OrStrategy(new AlwaysStrategy, new AlwaysStrategy));
        $this->assertTrue(wait($this->feature->isEnabled("foo", new Context)));

        $this->feature->add("foo", new OrStrategy(new AlwaysStrategy, new NeverStrategy));
        $this->assertTrue(wait($this->feature->isEnabled("foo", new Context)));

        $this->feature->add("foo", new OrStrategy(new NeverStrategy, new AlwaysStrategy));
        $this->assertTrue(wait($this->feature->isEnabled("foo", new Context)));

        $this->feature->add("foo", new OrStrategy(new NeverStrategy, new NeverStrategy));
        $this->assertFalse(wait($this->feature->isEnabled("foo", new Context)));
    }

    public function testRandom() {
        $this->feature->add("foo", new RandomStrategy(0));
        $this->assertFalse(wait($this->feature->isEnabled("foo", new Context)));

        $this->feature->add("foo", new RandomStrategy(100));
        $this->assertTrue(wait($this->feature->isEnabled("foo", new Context)));
    }

    public function testXor() {
        $this->feature->add("foo", new XorStrategy(new AlwaysStrategy, new AlwaysStrategy));
        $this->assertFalse(wait($this->feature->isEnabled("foo", new Context)));

        $this->feature->add("foo", new XorStrategy(new AlwaysStrategy, new NeverStrategy));
        $this->assertTrue(wait($this->feature->isEnabled("foo", new Context)));

        $this->feature->add("foo", new XorStrategy(new NeverStrategy, new AlwaysStrategy));
        $this->assertTrue(wait($this->feature->isEnabled("foo", new Context)));

        $this->feature->add("foo", new XorStrategy(new NeverStrategy, new NeverStrategy));
        $this->assertFalse(wait($this->feature->isEnabled("foo", new Context)));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowsOnInvalidRandomThreshold() {
        new RandomStrategy(-1);
    }

    /**
     * @expectedException \Kelunik\Feature\NoSuchFeatureException
     */
    public function testThrowsOnUnknownFeature() {
        wait($this->feature->isEnabled("random", new Context));
    }

    /**
     * @expectedException \Kelunik\Feature\NoSuchFeatureException
     */
    public function testThrowsOnRemovedFeature() {
        $this->feature->add("random", new AlwaysStrategy);
        $this->feature->remove("random");
        wait($this->feature->isEnabled("random", new Context));
    }
}