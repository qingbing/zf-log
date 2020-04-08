<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace Test\Command;


use DebugBootstrap\Abstracts\Tester;
use Test\Debug\TLoggerTester;

/**
 * @author      qingbing<780042175@qq.com>
 * @describe    LogTester
 *
 * Class LogTester
 * @package Test\Web
 */
class LoggerTester extends Tester
{
    use TLoggerTester;
}