<?php

namespace Kelunik\Feature\Strategy;

use function Amp\all;
use function Amp\pipe;

class AndStrategy implements Strategy {
    private $first;
    private $second;

    public function __construct(Strategy $first, Strategy $second) {
        $this->first = $first;
        $this->second = $second;
    }

    public function isEnabled() {
        $all = all([
            $this->first->isEnabled(),
            $this->second->isEnabled(),
        ]);

        return pipe($all, function ($res) {
            return $res[0] && $res[1];
        });
    }
}