<?php

namespace Kelunik\Feature\Strategy;

use Amp\Success;
use Kelunik\Feature\Context;

class NeverStrategy implements Strategy {
    public function isEnabled(Context $context) {
        return new Success(false);
    }
}