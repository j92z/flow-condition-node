#### 流程节点条件判断引擎

```
composer require j92z/flow-condition-node
```

```
require __DIR__ . '/vendor/autoload.php';

use J92z\Flow\Condition\Node;

$info = [
    [
        'ruleUnit' => [
            'ruleUnit' => [
                [
                    'field'   => 'a',
                    'compare' => '=',
                    'value'   => 2,
                ],
                [
                    'field'   => 'b',
                    'compare' => '>',
                    'value'   => 5,
                ]
            ],
            'relation' => 'and',
        ],
        'priority' => 1,
        'result'   => [
            'to' => "李四",
        ],
    ],
    [
        'ruleUnit' => [
            'ruleUnit' => [
                [
                    'field'   => 'a',
                    'compare' => '=',
                    'value'   => 1,
                ],
                [
                    'field'   => 'b',
                    'compare' => '>',
                    'value'   => 4,
                ]
            ],
            'relation' => 'or',
        ],
        'priority' => 2,
        'result'   => [
            'to' => "张三",
        ],
    ],
    [
        'ruleUnit' => [
            'ruleUnit' => [
                [
                    'field'   => 'a',
                    'compare' => '=',
                    'value'   => 1,
                ],
                [
                    'field'   => 'b',
                    'compare' => '>',
                    'value'   => 4,
                ]
            ],
            'relation' => 'and',
        ],
        'priority' => 3,
        'result'   => [
            'to' => "王五",
        ],
    ],
    [
        'ruleUnit' => [
            'ruleUnit' => [
                [
                    'field'   => 'a',
                    'compare' => '=',
                    'value'   => 1,
                ],
                [
                    'ruleUnit' => [
                        [
                            'field'   => 'c',
                            'compare' => '=',
                            'value'   => 2,
                        ],
                        [
                            'field'   => 'd',
                            'compare' => '=',
                            'value'   => 5,
                        ]
                    ],
                    'relation' => 'and',
                ]
            ],
            'relation' => 'and',
        ],
        'priority' => 4,
        'result'   => [
            'to' => "赵六",
        ],
    ],
];

$resource = [
    "a" => 1,
    "b" => 4,
    "c" => 2,
    "d" => 5
];



$json = Node::create($info)->toJson();

var_dump($json);

$node = Node::create($json, $resource);

var_dump($node->getMatch());

var_dump($node->getMatchResult(false));

var_dump($node->getMatchResult());
```