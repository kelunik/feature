<?php

namespace Kelunik\Feature\Strategy;

use Kelunik\Feature\Context;

interface Strategy {
    public function isEnabled(Context $context);
}