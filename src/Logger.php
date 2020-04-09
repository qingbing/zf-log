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
use Zf\Helper\Exceptions\Exception;
use Zf\Helper\Format;
use Zf\Helper\ZList;
use Zf\Log\Abstracts\AFlusher;
use Zf\Log\Flushers\FileFlusher;
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
     * @var AFlusher[]
     */
    private $_flushers;
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
     * @throws Exception
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
     * @throws Exception
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
     * @describe    用户没有设置消费处理机时，默认使用文件方式
     *
     * @throws Exception
     */
    protected function defaultFlusher()
    {
        $flusher = new FileFlusher(new Formatter());
        $this->getFlushers()->push($flusher);
    }

    /**
     * @describe    获取日志处理机
     *
     * @return ZList|AFlusher[]
     * @throws Exception
     * @throws \Zf\Helper\Exceptions\ParameterException
     */
    public function getFlushers()
    {
        if (null === $this->_flushers) {
            $this->_flushers = new ZList();
        }
        return $this->_flushers;
    }

    /**
     * @describe    添加一个消息处理机
     *
     * @param AFlusher $flusher
     * @param bool $append
     * @throws Exception
     */
    public function addFlusher(AFlusher $flusher, $append = true)
    {
        if (!$this->getFlushers()->contains($flusher)) {
            if ($append) {
                $this->getFlushers()->push($flusher);
            } else {
                $this->getFlushers()->unshift($flusher);
            }
        }
    }

    /**
     * @describe    移除一个消息处理机
     *
     * @param AFlusher $flusher
     * @throws Exception
     * @throws \Zf\Helper\Exceptions\ParameterException
     */
    public function removeFlusher(AFlusher $flusher)
    {
        $this->getFlushers()->remove($flusher);
    }

    /**
     * @describe    冲刷消息
     *
     * @throws Exception
     */
    public function flush()
    {
        $recordList = $this->getRecordList();
        // 没有消息，放回终止
        if (0 === count($recordList)) {
            return;
        }
        // 没有设置日志消息处理机时创建一个默认的
        if (0 === count($this->getFlushers())) {
            $this->defaultFlusher();
        }
        foreach ($this->getFlushers() as $flusher) {
            $flusher->flush($this);
        }
    }

    /**
     * 魔术方法：析构函数
     *
     * @throws Exception
     */
    public function __destruct()
    {
        $this->flush();
    }
}