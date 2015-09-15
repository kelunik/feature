<?php

namespace Kelunik\Feature\Strategy;

use Amp\Success;

class NeverStrategy implements Strategy {
    public function isEnabled() {
        return new Success(false);
    }
}