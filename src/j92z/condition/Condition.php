<?php

namespace J92z\Flow\Condition;

class Condition implements InfoInterface
{

    public $ruleUnit;

    public $priority = 0;

    public $result;

    public function __construct(RuleUnit $ruleUnit, int $priority, Result $result)
    {
        $this->ruleUnit = $ruleUnit;
        $this->priority = $priority;
        $this->result   = $result;
    }

    public function checkCondition(ResourceCollection $resources): bool
    {
        return $this->ruleUnit->checkRuleUnit($resources);
    }

    public function toJson(): string
    {
        return \json_encode([
            'ruleUnit' => $this->ruleUnit->toJson(),
            'priority' => $this->priority,
            'result'   => $this->result->toJson(),
        ]);
    }

    public function data()
    {
        return [
            'ruleUnit' => $this->ruleUnit->data(),
            'priority' => $this->priority,
            'result'   => $this->result->data(), 
        ];
    }

    public static function create($info)
    {
        if (\is_string($info)) {
            $info = \json_decode($info, true);
        }
        if (!\array_key_exists('ruleUnit', $info) || !\array_key_exists('result', $info) || !\array_key_exists('priority', $info)) {
            throw new \Exception('Condition必须存在ruleUnit,relation,priority');
        }
        if (!\is_numeric($info['priority']) || $info['priority'] < 0) {
            throw new \Exception('Condition.priority必须为整数且大于等于0');
        }
        $ruleUnit = RuleUnit::create($info['ruleUnit']);
        $result = Result::create($info['result']);
        return new self($ruleUnit, $info['priority'], $result);
    }
}
