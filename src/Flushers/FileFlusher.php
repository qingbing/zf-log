<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace Zf\Log\Flushers;


use Zf\Helper\Business\Context;
use Zf\Helper\Business\CryptJson;
use Zf\Helper\Exceptions\Exception;
use Zf\Helper\File;
use Zf\Helper\Format;
use Zf\Log\Abstracts\AFlusher;
use Zf\Log\Logger;

/**
 * 定义缓存目录
 */
defined("ZF_RUNTIME") or define("ZF_RUNTIME", dirname(realpath('.')) . '/runtime');

/**
 * @author      qingbing<780042175@qq.com>
 * @describe    默认日志冲刷
 *
 * Class FileFlusher
 * @package Zf\Log\Supports
 */
class FileFlusher extends AFlusher
{
    /**
     * @describe    接受处理的消息类型，如果不设置，表示全部结束处理
     *
     * @var array
     */
    public $acceptTypes = [
        Logger::EMERGENCY,
        Logger::ALERT,
        Logger::CRITICAL,
        Logger::ERROR,
        Logger::WARNING,
        Logger::NOTICE,
        Logger::INFO,
    ];
    /**
     * @describe    日志持久化目录
     *
     * @var string
     */
    public $logPath;
    /**
     * @describe    单文件字节大于该值就重启一个文件
     *
     * @var int
     */
    public $maxSize = 2000000;

    /**
     * @describe    清理，持久化
     *
     * @param array $recordList
     * @throws Exception
     */
    protected function flushData(array $recordList)
    {
        // 确保日志文件存在
        if ($this->logPath) {
            if (!is_dir($this->logPath)) {
                throw new Exception("指定的日志目录不存在");
            }
            $logPath = realpath($this->logPath);
        } else {
            $logPath = ZF_RUNTIME . '/' . $this->logger->channel;
            if (!is_dir($logPath)) {
                File::mkdir($logPath, 0777, false);
            }
        }
        // 真实文件存放地址
        $file = $logPath . '/' . date('Ymd') . '.log';
        if (file_exists($file) && filesize($file) > $this->maxSize) {
            $newFile = $logPath . '/' . date('Ymd_His') . '.log';
            @rename($file, $newFile);
        }
        // 准备日志信息
        $data = [
            'logId' => Context::getLogId(),
            'channel' => $this->logger->channel,
            'starttime' => $this->logger->getStartTime(),
            'endtime' => Format::microDateTime(),
            'info' => $recordList,
            'refer' => Context::urlReferrer(),
        ];
        // 日志写入文件
        File::putContent($file, sprintf('%s %s %s %s %s' . "\r\n"
            , Format::microDateTime()
            , Context::userHostAddress()
            , Context::requestMethod()
            , Context::requestUri()
            , CryptJson::encode($data, false)
        ));
    }
}