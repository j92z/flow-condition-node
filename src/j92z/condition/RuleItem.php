<?php

namespace J92z\Flow\Condition;

class RuleItem implements InfoInterface
{

    public $field;

    public $compare;

    public $value;

    private $compareType = [">", ">=", "<", "<=", "="];

    public function __construct($field, $compare, $value)
    {
        $this->checkCompare($compare);
        $this->field   = $field;
        $this->compare = $compare;
        $this->value   = $value;
    }

    private function checkCompare($compare)
    {
        if (!\in_array($compare, $this->compareType)) {
            throw new \Exception("$compare 不包含在{" . implode(',', $this->compareType) . '}中');
        }
    }

    public function checkRule(ResourceCollection $resources): bool
    {
        foreach ($resources->collection as $resource) {
            if ($resource->key !== $this->field) {
                continue;
            }
            switch ($this->compare) {
                case ">":
                    return $resource->value > $this->value;
                    break;
                case ">=":
                    return $resource->value >= $this->value;
                    break;
                case "<":
                    return $resource->value < $this->value;
                    break;
                case "<=":
                    return $resource->value <= $this->value;
                    break;
                case "=":
                    return $resource->value == $this->value;
                    break;
            }
        }
        return false;
    }

    public function toJson(): string
    {
        return \json_encode([
            'field'   => $this->field,
            'compare' => $this->compare,
            'value'   => $this->value,
        ]);
    }

    public function data()
    {
        return [
            'field'   => $this->field,
            'compare' => $this->compare,
            'value'   => $this->value,
        ];
    }

    public static function create($info)
    {
        if (\is_string($info)) {
            $info = \json_decode($info, true);
        }
        if (!\array_key_exists('field', $info) || !\array_key_exists('compare', $info) || !\array_key_exists('value', $info)) {
            throw new \Exception('RuleItem必须存在field,compare,value');
        }
        return new self($info['field'], $info['compare'], $info['value']);
    }
}
