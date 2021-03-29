<?php

namespace J92z\Flow\Condition;

class RuleUnit implements InfoInterface
{

    public $ruleUnit;

    public $relation;

    private $relationType = ['or', 'and', 'none'];

    public function __construct(array $ruleUnit, $relation)
    {
        $this->setUnit($ruleUnit);
        $this->setRelation($relation);
    }

    public function checkRuleUnit(ResourceCollection $resources): bool
    {
        $results = [];
        foreach ($this->ruleUnit as $unit) {
            if ($unit instanceof RuleItem) {
                $results[] = $unit->checkRule($resources);
            } elseif ($unit instanceof RuleUnit) {
                $results[] = $unit->checkRuleUnit($resources);
            } else {
                throw new \Exception("RuleUnit包含非RuleUnit/RuleItem条件单元");
            }
        }
        switch ($this->relation) {
            case "and":
                foreach ($results as $result) {
                    if (!$result) {
                        return false;
                    }
                }
                break;
            case "or":
                foreach ($results as $result) {
                    if ($result) {
                        return true;
                    }
                }
                return false;
                break;
            case "none":
                return count($results) > 0 ? $results[0] : true;
                break;
        }
        return true;
    }

    private function setRelation($relation)
    {
        $relation = \strtolower($relation);
        if (!\in_array($relation, $this->relationType)) {
            throw new \Exception("$relation 不包含在{" . implode(',', $this->relationType) . '}中');
        }
        if ($relation == "none" && count($this->ruleUnit) > 1) {
            throw new \Exception("当前条件单元之间关系不可为none");
        }
        $this->relation = $relation;
    }

    private function setUnit($ruleUnit)
    {
        foreach ($ruleUnit as $unit) {
            if (!($unit instanceof RuleUnit) && !($unit instanceof RuleItem)) {
                throw new \Exception("传入规则单元有误");
            }
        }
        $this->ruleUnit = $ruleUnit;
    }

    public function toJson(): string
    {
        $unitJson = [];
        foreach ($this->ruleUnit as $unit) {
            $unitJson[] = $unit->toJson();
        }
        return \json_encode([
            'ruleUnit' => $unitJson,
            'relation' => $this->relation,
        ]);
    }

    public function data()
    {
        $unitData = [];
        foreach ($this->ruleUnit as $unit) {
            $unitData[] = $unit->data();
        }
        return [
            'ruleUnit' => $unitData,
            'relation' => $this->relation,
        ];
    }

    public static function create($info)
    {
        if (\is_string($info)) {
            $info = \json_decode($info, true);
        }
        if (!\array_key_exists('ruleUnit', $info) || !\array_key_exists('relation', $info)) {
            throw new \Exception('RuleUnit必须存在ruleUnit,relation');
        }
        if (!\is_array($info['ruleUnit'])) {
            throw new \Exception('RuleUnit.ruleUnit必须是array');
        }
        foreach ($info['ruleUnit'] as &$item) {
            if (!\is_array($item)) {
                throw new \Exception('RuleUnit.ruleUnit子元素必须是array');
            }
            if (\array_key_exists('field', $item) && \array_key_exists('compare', $item) && \array_key_exists('value', $item)) {
                $item = RuleItem::create($item);
            } elseif (\array_key_exists('ruleUnit', $item) && \array_key_exists('relation', $item)) {
                $item = RuleUnit::create($item);
            } else {
                throw new \Exception('RuleUnit.ruleUnit存在非RuleUnit/RuleItem子元素');
            }
        }
        if (count($info['ruleUnit']) <= 1) {
            $info['relation'] = 'none';
        }
        return new self($info['ruleUnit'], $info['relation']);
    }
}
