<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace Test\Web;


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

    public function send($url, $header, $content)
    {
        $ch = curl_init();
        if (substr($url, 0, 5) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
        $response = curl_exec($ch);
        if ($error = curl_error($ch)) {
            die($error);
        }
        curl_close($ch);
        return $response;
    }

    public function sentTest()
    {
        $res = $this->send('http://zf.us/zf-log/test/bootstrap.php?c=test', [
            'ZF-LOG-ID:JxRaZezavm3HXM3d9pWnYiqqQC1SJbsU',
            'language:zh',
            'region:GZ'
        ], [
            'name' => 'fdipzone'
        ]);

        print_r($res);
    }
}