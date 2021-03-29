<?php

namespace J92z\Flow\Condition;

class Resource
{
    public $key;
    public $value;

    public function __construct($key, $value)
    {
        $this->key   = $key;
        $this->value = $value;
    }

    public function data()
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
        ];
    }

}
