<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace Zf\Log\Supports;


use Zf\Log\Supports\Abstracts\AFormatter;

/**
 * @author      qingbing<780042175@qq.com>
 * @describe    默认日志记录格式化处理
 *
 * Class Formatter
 * @package Zf\Log\Supports
 */
class Formatter extends AFormatter
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
        return $record;
    }
}