<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace Zf\Log\Supports;


use Zf\Helper\Business\Context;
use Zf\Helper\Business\CryptJson;
use Zf\Helper\Exceptions\Exception;
use Zf\Helper\File;
use Zf\Helper\Format;
use Zf\Log\Supports\Abstracts\AFlusher;

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
     * @describe    清理，持久化日志队列
     *
     * @return void
     *
     * @throws Exception
     */
    public function flush()
    {
        $logger = $this->logger;
        $flushData = [];
        if (is_array($logger->acceptTypes)) {
            foreach ($logger->getRecordList() as $item) {
                if (!in_array($item['level'], $logger->acceptTypes)) {
                    continue;
                }
                $flushData[] = $this->formatter->format($item);
            }
        } else {
            foreach ($logger->getRecordList() as $item) {
                $flushData[] = $this->formatter->format($item);
            }
        }
        if (0 === count($flushData)) {
            return;
        }
        // 确保日志文件存在
        if ($this->logPath) {
            if (!is_dir($this->logPath)) {
                throw new Exception("指定的日志目录不存在");
            }
            $logPath = realpath($this->logPath);
        } else {
            $logPath = ZF_RUNTIME . '/' . $logger->channel;
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
            'channel' => $logger->channel,
            'starttime' => $logger->getStartTime(),
            'endtime' => Format::microDateTime(),
            'info' => $flushData,
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