<?php

namespace Kelunik\Feature\Strategy;

use Amp\Success;

class RandomStrategy implements Strategy {
    private $threshold;

    public function __construct($threshold) {
        if (!is_int($threshold) || $threshold < 0 || $threshold > 100) {
            throw new \InvalidArgumentException("threshold must int and between 0 and 100");
        }

        $this->threshold = $threshold;
    }

    public function isEnabled() {
        return new Success(mt_rand(0, 99) < $this->threshold);
    }
}