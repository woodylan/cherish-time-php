<?php

return [
    'driver'      => 'swoole',// 驱动方式
    'socket'      => env('LOG_SERVER_SOCKET', 'udp://127.0.0.1:10003'), // 服务地址
    'projectId'   => 'lumen-framework', // 项目英文名
    'partitionId' => 'lumen-framework', // 分区英文名
    'timeout'     => '1', // 超时，udp不需要 默认1秒
];
