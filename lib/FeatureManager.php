<?php

namespace Kelunik\Feature;

use Kelunik\Feature\Strategy\Strategy;

class FeatureManager {
    /** @var Strategy[] */
    private $features;

    public function __construct() {

    }

    public function add($feature, Strategy $strategy) {
        $this->features[$feature] = $strategy;
    }

    public function remove($feature) {
        unset($this->features[$feature]);
    }

    public function isEnabled($feature, Context $context) {
        if (!isset($this->features[$feature])) {
            throw new NoSuchFeatureException("there's no strategy for that feature");
        }

        return $this->features[$feature]->isEnabled($context);
    }
}