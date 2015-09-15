<?php

namespace Kelunik\Feature\Strategy;

use Kelunik\Feature\Context;
use function Amp\all;
use function Amp\pipe;

class OrStrategy implements Strategy {
    private $first;
    private $second;

    public function __construct(Strategy $first, Strategy $second) {
        $this->first = $first;
        $this->second = $second;
    }

    public function isEnabled(Context $context) {
        $all = all([
            $this->first->isEnabled($context),
            $this->second->isEnabled($context),
        ]);

        return pipe($all, function ($res) {
            return $res[0] || $res[1];
        });
    }
}