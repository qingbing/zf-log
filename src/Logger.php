<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace Zf\Log;


use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;
use Zf\Helper\Abstracts\Component;
use Zf\Helper\Format;
use Zf\Helper\ZList;
use Zf\Log\Supports\Flusher;
use Zf\Log\Supports\Formatter;

/**
 * @author      qingbing<780042175@qq.com>
 * @describe    日志组件
 *
 * Class Logger
 * @package Zf\Log
 */
class Logger extends Component
{
    /**
     * 定义日志级别
     */
    const EMERGENCY = LogLevel::EMERGENCY;
    const ALERT = LogLevel::ALERT;
    const CRITICAL = LogLevel::CRITICAL;
    const ERROR = LogLevel::ERROR;
    const WARNING = LogLevel::WARNING;
    const NOTICE = LogLevel::NOTICE;
    const INFO = LogLevel::INFO;
    const DEBUG = LogLevel::DEBUG;
    /**
     * @describe    日志通道、频道：sql，log...
     *
     * @var string
     */
    public $channel = 'log';
    /**
     * @describe    消息格式化
     *
     * @var \Zf\Log\Supports\Abstracts\AFlusher
     */
    public $flusher;
    /**
     * @describe    消息格式化
     *
     * @var array
     */
    public $acceptTypes;

    /**
     * @describe    日志记录列表
     *
     * @var ZList
     */
    private $_recordList;
    /**
     * @describe    logger 初始化时间
     *
     * @var string
     */
    private $_startTime;

    /**
     * 使用 psr/log 提供的额 logger trait
     */
    use LoggerTrait;

    /**
     * @describe    属性赋值后执行函数
     *
     * @throws \Zf\Helper\Exceptions\Exception
     * @throws \Zf\Helper\Exceptions\ParameterException
     */
    public function init()
    {
        $this->_startTime = Format::microDateTime();
        $this->_recordList = new ZList();
    }

    /**
     * @describe    获取日志开始时间
     *
     * @return string
     */
    public function getStartTime(): string
    {
        return $this->_startTime;
    }

    /**
     * @describe    获取列表
     *
     * @return ZList
     */
    public function getRecordList(): ZList
    {
        return $this->_recordList;
    }

    /**
     * @describe    日志计入队列，保持一个request中一个channel只有一条日志
     *
     * @param string $level
     * @param string $message
     * @param array $context
     *
     * @throws \Zf\Helper\Exceptions\Exception
     */
    public function log(string $level, string $message, array $context = [])
    {
        $this->_recordList->push([
            'time' => Format::microDateTime(),
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ]);
    }

    /**
     * @describe    冲刷消息
     */
    public function flush()
    {
        if (0 === $this->getRecordList()->count()) {
            return;
        }
        if (null === $this->flusher) {
            $this->flusher = new Flusher(new Formatter());
        }
        $this->flusher->setLogger($this);
        $this->flusher->flush();
    }

    /**
     * @throws \Exception
     */
    public function __destruct()
    {
        $this->flush();
    }
}