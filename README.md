# zf-log
日志组件，日志的相关操作

# 提示
- 日志级别完全参考 "\Psr\Log\LogLevel"
- 日志可以使用多个处理机，默认使用 "new FileFlusher(new Formatter())"
- 支持自定义 Flusher 和 Formatter，需要继承 "\Zf\Log\Supports\Abstracts\AFlusher" 和 "\Zf\Log\Supports\Abstracts\AFormatter"
- 日志提供8个级别的日志记录

# 使用默认的 flusher

```php
$logger = Object::create([
    'class' => Logger::class,
    'channel' => 'log'
]);
/* @var Logger $logger */
$logger->emergency('I am emergency.');
$logger->alert('I am alert.');
$logger->critical('I am critical.');
$logger->error('I am error.');
$logger->warning('I am warning.');
$logger->notice('I am notice.');
$logger->info('I am info.');
$logger->debug('I am debug.');
```

# 使用自定义的Flusher和Formatter
```php
$logger = Object::create([
    'class' => Logger::class,
    'channel' => 'log'
]);
/* @var Logger $logger */
// 文件日志处理机
$flusher = new FileFlusher(new Formatter());
$flusher->acceptTypes = [
    Logger::INFO,
    Logger::WARNING,
    Logger::CRITICAL,
];
$logger->addFlusher($flusher);

// 流式日志处理
$flusher = new StreamFlusher(new class extends AFormatter
{
    /**
     * @describe    格式化一个日志记录
     *
     * @param array $record
     *
     * @return mixed
     */
    public function format(array $record)
    {
        return json_encode($record, JSON_UNESCAPED_UNICODE);
    }
});
$flusher->acceptTypes = [
    Logger::WARNING,
    Logger::CRITICAL,
];
// 流式消息处理机
$logger->addFlusher($flusher);

$logger->emergency('I am emergency.');
$logger->alert('I am alert.');
$logger->critical('I am critical.');
$logger->error('I am error.');
$logger->warning('I am warning.');
$logger->notice('I am notice.');
$logger->info('I am info.');
$logger->debug('I am debug.');

```