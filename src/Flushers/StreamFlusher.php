<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace Zf\Log\Flushers;


use Zf\Helper\Business\Context;
use Zf\Helper\Format;
use Zf\Log\Abstracts\AFlusher;

class StreamFlusher extends AFlusher
{
    /**
     * @describe    describe
     *
     * @var string
     */
    public $stream = "php://stdout";

    /**
     * @describe    清理，持久化
     *
     * @param array $recordList
     */
    protected function flushData(array $recordList)
    {
        // 准备日志信息
        $data = [
            'logId' => Context::getLogId(),
            'method' => Context::requestMethod(),
            'requestUri' => Context::requestUri(),
            'channel' => $this->logger->channel,
            'starttime' => $this->logger->getStartTime(),
            'endtime' => Format::microDateTime(),
            'info' => $recordList,
            'refer' => Context::urlReferrer(),
        ];
        // 日志输出
        file_put_contents($this->stream, var_export($data, true) . PHP_EOL);
    }
}