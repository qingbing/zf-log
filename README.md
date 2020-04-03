# zf-log
日志组件，日志的相关操作


# 使用默认

```php
// 实例化
$logger = Object::create([
    'class' => Logger::class,
    'channel' => 'log',
    'acceptTypes' => [
        Logger::DEBUG,
        Logger::INFO,
        Logger::ALERT,
        Logger::WARNING,
    ]
]);

// 记录日志
$logger->alert('xxx', [
    'param' => 'xxx',
    'xx' => 'xxx',
]);

$logger->warning('I am {name}', [
    'name' => 'qingbing',
]);
```

# 使用自定义的Flusher和Formatter
```php
// 定义Formatter
$formatter = new \Zf\Log\Supports\Formatter();

// 定义持久化类
$flusher = new \Zf\Log\Supports\FileFlusher($formatter);
$flusher->maxSize = 1000;

// 实例化 $logger
$logger = Object::create([
    'class' => \Zf\Log\Logger::class,
    'channel' => 'log',
    'flusher' => 'log',
    'acceptTypes' => [
        Logger::DEBUG,
        Logger::INFO,
        Logger::ALERT,
        Logger::WARNING,
    ]
]);

```