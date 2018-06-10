<?php
/**
 * Created by PhpStorm.
 * User: Dylan
 * Date: 2018/3/30
 * Time: 下午3:04
 */

namespace App\Tools;


class Util
{
    public static function errorCode($errorCodeKey, $previous = null, $apiData = null, $httpCode = 200, $headers = [])
    {
        $replaces = [];
        if (is_array($errorCodeKey)) {
            if (count($errorCodeKey) === 2) {
                list($errorCodeKey, $replaces) = $errorCodeKey;
            } else {
                throw new \Exception('ErrorCode invalid: ' . var_export($errorCodeKey, true));
            }
        }
        app()->configure('errorCode');
        $error = config("errorCode.{$errorCodeKey}");
        if (!isset($error['code'])) {
            $error['code'] = $errorCodeKey;
        }
        if (!$error) {
            $error = ['code' => $errorCodeKey, 'msg' => "错误码{$errorCodeKey}", 'data' => $apiData, 'isReport' => false];
        }
        $error = array_merge(['code' => -1, 'msg' => '系统异常', 'data' => $apiData, 'isReport' => false], $error);
        $msg = $error['msg'];
        if (is_string($replaces)) {
            $msg = $replaces;
            $replaces = [];
        }

        $msg = app()->translator->getFromJson($msg, $replaces);
        throw new \App\Exceptions\EvaException($msg, $error['code']);
    }

    /**
     * 删除目录及目录下所有文件或删除指定文件
     * @param string $path 待删除目录路径
     * @param int $delDir 是否删除目录，1或true删除目录，0或false则只删除文件保留目录（包含子目录）
     * @return bool 返回删除状态
     */
    public static function delDirAndFile($path, $delDir = false)
    {
        $handle = opendir($path);
        if ($handle) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..")
                    is_dir("$path/$item") ? self::delDirAndFile("$path/$item", $delDir) : unlink("$path/$item");
            }
            closedir($handle);
            if ($delDir)
                return rmdir($path);
        } else {
            if (file_exists($path)) {
                return unlink($path);
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * 导出文件时可以使用这个函数，可以让浏览器自动触发下载功能
     *
     * @param $fileSrc 文件原始路径
     * @param $filename 下载文件名
     */
    public static function browserDownload($fileSrc, $filename)
    {
        $content = file_get_contents($fileSrc);//将文件读入字符串
        $length = strlen($content);//取得字符串长度，即文件大小，单位是字节
        $encoded_filename = rawurlencode($filename);//采用rawurlencode避免空格被替换为+
        $ua = $_SERVER["HTTP_USER_AGENT"];//获取用户浏览器UA信息
        header('Content-Type: application/octet-stream');//告诉浏览器输出内容类型，必须
        header('Content-Encoding: none');//内容不加密，gzip等，可选
        header("Content-Transfer-Encoding: binary");//文件传输方式是二进制，可选
        header("Content-Length: " . $length);//告诉浏览器文件大小，可选

        if (preg_match("/MSIE/", $ua)) {//从UA中找到MSIE判断是IE
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else {
            header('Content-Disposition: attachment; filename*="' . $encoded_filename . '"');
        }
        echo $content;
    }

    public static function fileToBase64($file)
    {
        $base64_file = '';
        if (file_exists($file)) {
            $mime_type = mime_content_type($file);
            $base64_data = base64_encode(file_get_contents($file));
            $base64_file = 'data:' . $mime_type . ';base64,' . $base64_data;

            //删除文件
            unlink($file);
        }
        return $base64_file;
    }

    public static function getShortWebUrl($originUrl)
    {
        $requestUrl = "http://suo.im/api.php?url=" . urlencode($originUrl);
        return self::httpGet($requestUrl);
    }

    /**
     * 模拟GET
     *
     * @param $url
     *
     * @return mixed|string
     */
    public static function httpGet($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $output = curl_exec($ch);
        return $output;
    }

    /**
     * 模拟POST
     *
     * @param $url
     * @param $data
     *
     * @return mixed|string
     */
    public static function httpPost($url, $data, $header = [])
    {
        if (!function_exists('curl_init')) {
            return '';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $data = curl_exec($ch);
        if (!$data) {
            error_log(curl_error($ch));
        }
        curl_close($ch);
        return $data;
    }

    /**
     * 根据idcard获取生日信息
     * @param string $idCard
     * @return string 1992-11-19
     */
    public static function getBirthDayFromIdCard($idCard)
    {
        $birthDay = strlen($idCard) == 15 ? strtotime('19' . substr($idCard, 6, 6)) : strtotime(substr($idCard, 6, 8));
        return date('Y-m-d', $birthDay);
    }

    /**
     * 根据idcard判断性别
     * @param string $idCard
     * @return integer // 1男 2女
     */
    public static function getSexFromIdCard($idCard)
    {
        $sex = strlen($idCard) == 15 ? $sex = substr($idCard, 14, 1) : substr($idCard, 16, 1);
        return $sex % 2 > 0 ? 1 : 2;
    }

    /**
     * 校验身份证
     * @param $idcard
     * @return bool
     */
    public static function verifyIdCard($idcard)
    {
        // 只能是18位
        if (strlen($idcard) != 18) {
            return false;
        }
        $idcard_base = substr($idcard, 0, 17);
        $verify_code = substr($idcard, 17, 1);
        if ($verify_code == 'x') {
            $verify_code = 'X';
        }
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        $verify_code_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $total = 0;
        for ($i = 0; $i < 17; $i++) {
            $total += (int)substr($idcard_base, $i, 1) * $factor[$i];
        }
        $mod = $total % 11;
        if ($verify_code == $verify_code_list[$mod]) {
            return true;
        } else {
            return false;
        }
    }
}