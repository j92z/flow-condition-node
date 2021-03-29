<?php

namespace J92z\Flow\Condition;

class ResourceCollection
{
    public $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    public static function genResource(array $resource)
    {
        $collection = [];
        foreach ($resource as $key => $value) {
            $collection[] = new Resource($key, $value);
        }
        return new self($collection);
    }

    public function data()
    {
        $resources = [];
        foreach ($this->collection as $resource) {
            $resources[] = $resource->data();
        }
        return $resources;
    }

}
