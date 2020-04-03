<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace Test\Debug;


use Zf\Helper\Object;
use Zf\Log\Logger;

class AppDemo
{
    /**
     * @describe    log
     *
     * @return Logger
     *
     * @throws \ReflectionException
     * @throws \Zf\Helper\Exceptions\ClassException
     */
    public static function log()
    {
        return Object::create([
            'class' => Logger::class,
            'channel' => 'log',
            'acceptTypes' => [
                Logger::DEBUG,
                Logger::INFO,
                Logger::ALERT,
                Logger::WARNING,
            ]
        ]);
    }
}