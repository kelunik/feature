<?php

namespace Kelunik\Feature;

class Context {
    /** @var [] */
    private $data;

    public function __construct(array $data = []) {
        $this->data = $data;
    }

    public function set($key, $value) {
        $this->data[$key] = $value;

        return $this;
    }

    public function get($key) {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return null;
    }
}