<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace Test\Debug;


use Zf\Helper\Object;
use Zf\Log\Abstracts\AFormatter;
use Zf\Log\Flushers\FileFlusher;
use Zf\Log\Flushers\StreamFlusher;
use Zf\Log\Formatters\Formatter;
use Zf\Log\Logger;

trait TLoggerTester
{
    /**
     * @describe    执行函数
     *
     * @throws \ReflectionException
     * @throws \Zf\Helper\Exceptions\ClassException
     * @throws \Zf\Helper\Exceptions\Exception
     */
    public function run()
    {
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
    }
}