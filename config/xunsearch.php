<?php

// xunsearch

return [
    'default'   => 'library_doc', // 默认搜索库
    'databases' => [
        'library_doc' => [ // 文档库文档
            'project.name'            => 'library_doc',
            'project.default_charset' => 'utf-8',
            'server.index'            => env('XUNSEARCH.SERVER_INDEX'),
            'server.search'           => env('XUNSEARCH.SERVER_SEARCH'),
            'id'                      => ['type' => 'id'],
            'title'                   => ['type' => 'title', 'index' => 'mixed', 'cutlen' => 150, 'tokenizer' => 'scws(7)'],
            'content'                 => ['type' => 'body', 'index' => 'mixed', 'cutlen' => 150, 'tokenizer' => 'scws(7)'],
            'library_id'              => ['type' => 'string', 'index' => 'self', 'tokenizer' => 'full'],
            'library_doc_id'          => ['type' => 'string', 'index' => 'self', 'tokenizer' => 'full'],
        ],
    ],
];
