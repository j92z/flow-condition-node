<?php

namespace J92z\Flow\Condition;

class Result implements InfoInterface
{

    public $info;

    public function __construct($info)
    {
        if (!\is_array($info)) {
            $tempInfo = json_decode($info, true);
            if (\is_null($tempInfo) && !\is_bool($info) && !\is_numeric($info) && !\is_string($info)) {
                throw new \Exception("Result解析失败");
            }
            if (\is_array($tempInfo)) {
                $info = $tempInfo;
            }
        }
        $this->checkLegal($info);
        $this->info = $info;
    }

    public function checkLegal($info)
    {
        if (\is_array($info)) {
            foreach ($info as $item) {
                if (\is_array($item) || \is_object($item)) {
                    throw new \Exception("Result只允许一维元素或二维元素");
                }
            }
        }
    }

    public function toJson(): string
    {
        if (is_object($this->info) || is_array($this->info)) {
            return \json_encode($this->info);
        }
        return $this->info;
    }

    public function data()
    {
        return $this->info;
    }

    public static function create($info)
    {
        return new self($info);
    }
}
