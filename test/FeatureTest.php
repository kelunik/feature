<?php

namespace Kelunik\Feature\Strategy;

use Kelunik\Feature\Feature;
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
        $this->assertTrue(wait($this->feature->isEnabled("foo")));
    }

    public function testAnd() {
        $this->feature->add("foo", new AndStrategy(new AlwaysStrategy, new AlwaysStrategy));
        $this->assertTrue(wait($this->feature->isEnabled("foo")));

        $this->feature->add("foo", new AndStrategy(new AlwaysStrategy, new NeverStrategy));
        $this->assertFalse(wait($this->feature->isEnabled("foo")));

        $this->feature->add("foo", new AndStrategy(new NeverStrategy, new AlwaysStrategy));
        $this->assertFalse(wait($this->feature->isEnabled("foo")));

        $this->feature->add("foo", new AndStrategy(new NeverStrategy, new NeverStrategy));
        $this->assertFalse(wait($this->feature->isEnabled("foo")));
    }

    public function testNever() {
        $this->feature->add("foo", new NeverStrategy);
        $this->assertFalse(wait($this->feature->isEnabled("foo")));
    }

    public function testOr() {
        $this->feature->add("foo", new OrStrategy(new AlwaysStrategy, new AlwaysStrategy));
        $this->assertTrue(wait($this->feature->isEnabled("foo")));

        $this->feature->add("foo", new OrStrategy(new AlwaysStrategy, new NeverStrategy));
        $this->assertTrue(wait($this->feature->isEnabled("foo")));

        $this->feature->add("foo", new OrStrategy(new NeverStrategy, new AlwaysStrategy));
        $this->assertTrue(wait($this->feature->isEnabled("foo")));

        $this->feature->add("foo", new OrStrategy(new NeverStrategy, new NeverStrategy));
        $this->assertFalse(wait($this->feature->isEnabled("foo")));
    }

    public function testRandom() {
        $this->feature->add("foo", new RandomStrategy(0));
        $this->assertFalse(wait($this->feature->isEnabled("foo")));

        $this->feature->add("foo", new RandomStrategy(100));
        $this->assertTrue(wait($this->feature->isEnabled("foo")));
    }

    public function testXor() {
        $this->feature->add("foo", new XorStrategy(new AlwaysStrategy, new AlwaysStrategy));
        $this->assertFalse(wait($this->feature->isEnabled("foo")));

        $this->feature->add("foo", new XorStrategy(new AlwaysStrategy, new NeverStrategy));
        $this->assertTrue(wait($this->feature->isEnabled("foo")));

        $this->feature->add("foo", new XorStrategy(new NeverStrategy, new AlwaysStrategy));
        $this->assertTrue(wait($this->feature->isEnabled("foo")));

        $this->feature->add("foo", new XorStrategy(new NeverStrategy, new NeverStrategy));
        $this->assertFalse(wait($this->feature->isEnabled("foo")));
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
        wait($this->feature->isEnabled("random"));
    }

    /**
     * @expectedException \Kelunik\Feature\NoSuchFeatureException
     */
    public function testThrowsOnRemovedFeature() {
        $this->feature->add("random", new AlwaysStrategy);
        $this->feature->remove("random");
        wait($this->feature->isEnabled("random"));
    }
}