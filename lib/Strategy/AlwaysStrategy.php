<?php

namespace Kelunik\Feature\Strategy;


use Amp\Success;

class AlwaysStrategy implements Strategy {
    public function isEnabled() {
        return new Success(true);
    }
}