<?php

namespace Kelunik\Feature;

use Amp\Failure;
use Kelunik\Feature\Strategy\Strategy;

class Feature {
    private $features;

    public function __construct() {

    }

    public function add($feature, Strategy $strategy) {
        $this->features[$feature] = $strategy;
    }

    public function remove($feature) {
        unset($this->features[$feature]);
    }

    public function isEnabled($feature) {
        if (isset($this->features[$feature])) {
            return $this->features[$feature]->isEnabled();
        }

        return new Failure(new NoSuchFeatureException("there's no strategy for that feature"));
    }
}