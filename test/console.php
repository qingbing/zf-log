<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */
require("../vendor/autoload.php");

/**
 * 运行控制台任务
 *
 * eg : php console.php --c=DebugCommand --id=5
 */
\DebugBootstrap\AppTester::getInstance()->runConsole();