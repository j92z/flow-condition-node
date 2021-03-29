<?php

namespace J92z\Flow\Condition;

interface InfoInterface
{
    public function toJson(): string;

    public function data();
}
