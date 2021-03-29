<?php

namespace J92z\Flow\Condition;

class Node implements InfoInterface
{

    public $conditions = [];

    public $resources;

    private $matchConditions = [];

    public function __construct(ConditionCollection $conditions, ResourceCollection $resources)
    {
        $this->setResources($resources);
        $this->setConditions($conditions);
        //匹配条件 必须放在最后执行
        $this->setMatch();
    }

    private function setResources($resources)
    {
        if (!$resources instanceof ResourceCollection) {
            throw new \Exception("Node.resources类型必须为ResourceCollection");
        }
        $this->resources = $resources;
    }

    private function setConditions($conditions)
    {
        if (!$conditions instanceof ConditionCollection) {
            throw new \Exception("Node.conditions类型必须为ConditionCollection");
        }
        $this->conditions = $conditions;
    }

    private function setMatch()
    {
        $tempConditions = $this->conditions->getMatchCondition($this->resources);
        $count = count($tempConditions);
        for ($i = 0; $i < $count; $i++) { //冒泡排序
            for ($k = $i + 1; $k < $count; $k++) {
                if ($tempConditions[$i]->priority < $tempConditions[$k]->priority) {
                    // 前者大于后者，调换位置
                    // 如果想要按照从大到小进行排序，改为 $arr[$i] < $arr[$k]
                    $temp = $tempConditions[$i];
                    $tempConditions[$i] = $tempConditions[$k];
                    $tempConditions[$k] = $temp;
                }
            }
        }
        $this->matchConditions = $tempConditions;
    }

    public static function create($conditions, $resources = [])
    {
        if (\is_string($conditions)) {
            $conditions = json_decode($conditions, true);
        }
        if (!\is_array($conditions)) {
            throw new \Exception("Node.conditions必须为array");
        }
        $conditions = ConditionCollection::genCondition($conditions);
        $resources  = ResourceCollection::genResource($resources);
        return new self($conditions, $resources);
    }

    public function toJson(): string
    {
        return \json_encode($this->conditions->data());
    }

    public function data()
    {
        return [
            'conditions' => $this->conditions->data(),
            'resources'  => $this->resources->data(),
        ];
    }

    public function getMatch(bool $only = true)
    {
        if ($only) {
            return $this->matchConditions[0];
        }
        return $this->matchConditions;
    }

    public function hasMatch(): bool
    {
        return count($this->matchConditions) > 0;
    }

    public function getMatchResult(bool $only = true)
    {
        $results = [];
        foreach ($this->matchConditions as $item) {
            $results[] = $item->result;
        }
        if ($only) {
            return $results[0];
        }
        return $results;
    }
}
