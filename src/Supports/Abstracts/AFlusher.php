<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace Zf\Log\Supports\Abstracts;


use Zf\Log\Logger;

/**
 * @author      qingbing<780042175@qq.com>
 * @describe    日志冲刷
 *
 * Class AFlusher
 * @package Zf\Log\Supports\Abstracts
 */
abstract class AFlusher
{
    /**
     * @describe    接受处理的消息类型，如果不设置，表示全部结束处理
     *
     * @var array
     */
    public $acceptTypes;
    /**
     * @describe    日志收集机
     *
     * @var Logger
     */
    protected $logger;
    /**
     * @describe    消息格式机
     *
     * @var AFormatter
     */
    protected $formatter;

    /**
     * 模式方法：构造函数
     *
     * @param AFormatter $formatter
     */
    public function __construct(AFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @describe    过滤需要处理的级别日志，并交由 flushData 处理
     *
     * @param Logger $logger
     */
    public function flush(Logger $logger)
    {
        $this->logger = $logger;
        $flushData = [];
        if (is_array($this->acceptTypes)) {
            foreach ($logger->getRecordList() as $item) {
                if (!in_array($item['level'], $this->acceptTypes)) {
                    continue;
                }
                $flushData[] = $this->formatter->format($item);
            }
        } else {
            foreach ($logger->getRecordList() as $item) {
                $flushData[] = $this->formatter->format($item);
            }
        }
        if (count($flushData) > 0) {
            $this->flushData($flushData);
        }
    }

    /**
     * @describe    清理，持久化
     *
     * @param array $recordList
     */
    abstract protected function flushData(array $recordList);
}