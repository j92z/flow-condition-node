<?php

namespace J92z\Flow\Condition;

class ConditionCollection
{

    public $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public function getMatchCondition(ResourceCollection $resources)
    {
        $match = [];
        foreach ($this->collection as $condition) {
            if ($condition->checkCondition($resources)) {
                $match[] = $condition;
            }
        }
        return $match;
    }

    public static function genCondition(array $condition)
    {
        $collection = [];
        foreach ($condition as $info) {
            $collection[] = Condition::create($info);
        }
        return new self($collection);
    }

    public function data()
    {
        $conditions = [];
        foreach ($this->collection as $condition) {
            $conditions[] = $condition->data();
        }
        return $conditions;
    }
}
