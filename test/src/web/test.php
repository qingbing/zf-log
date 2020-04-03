<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */


namespace Test\Web;
class test extends \DebugBootstrap\Abstracts\Tester
{

    /**
     * @describe    执行函数
     */
    public function run()
    {
//        header('x-g7-openapi-error-message: error message detail');

        print_r($_SERVER);


    }
}