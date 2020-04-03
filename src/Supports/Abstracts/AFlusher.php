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
     * @describe    设置日志收集机
     *
     * @param Logger $logger
     *
     * @return $this;
     */
    public function setLogger(Logger $logger): AFlusher
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @describe    清理，持久化日志队列
     *
     * @return mixed
     */
    abstract public function flush();
}