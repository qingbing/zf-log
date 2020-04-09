<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace Zf\Log\Abstracts;

/**
 * @author      qingbing<780042175@qq.com>
 * @describe    日志记录格式化处理
 *
 * Class AFormatter
 * @package Zf\Log\Supports\Abstracts
 */
abstract class AFormatter
{
    /**
     * @describe    格式化一个日志记录
     *
     * @param array $record
     *
     * @return mixed
     */
    abstract public function format(array $record);
}