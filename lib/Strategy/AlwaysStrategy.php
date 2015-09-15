<?php

namespace Kelunik\Feature\Strategy;


use Amp\Success;
use Kelunik\Feature\Context;

class AlwaysStrategy implements Strategy {
    public function isEnabled(Context $context) {
        return new Success(true);
    }
}