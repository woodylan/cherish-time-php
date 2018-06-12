<?php

namespace App\Tools;

use App\Define\RetCode;
use App\Exceptions\EvaException;
use OSS\Core\OssException;
use OSS\OssClient;

class Upload
{
    private $_bucket = "meishakeji-oss1";

    private $_ossClient = null;

    public $_dir = '';

    const WECHAT_AVATAR = 1;

    private $_bucketNameMap = [
        self::WECHAT_AVATAR => "users/avatar/wechat/%s.png",
    ];

    public function __construct()
    {
        $accessKeyId = env('OSS_ACCESS_KEY_ID');
        $accessKeySecret = env('OSS_ACCESS_KEY_SECRET');
        $endpoint = env('OSS_ENDPOINT');

        $this->_ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
    }

    public function setDir($dir, $fileName, $ext)
    {
        if (empty($fileName)) {
            $name = StringTool::createUuid();
        } else {
            $name = $fileName;
        }
        $this->_dir = $dir . $name . '.' . $ext;
    }

    public function uploadFileFromUrl($url, $type)
    {
        $fileName = sprintf($this->_bucketNameMap[$type], md5($url));
        //获取图片源数据
        $fileData = Util::httpGet($url);
        try {
            $this->_ossClient->putObject($this->_bucket, $fileName, $fileData);
        } catch (OssException $e) {
            throw new EvaException($e->getMessage(), $e->getCode());
        }
        return $fileName;
    }

    public function uploadBase64($file, $allow = 'image')
    {
        $ext = $this->_getImageExt($file);
        $startIndex = strpos($file, ';base64,') + strlen(';base64,');
        $file = substr($file, $startIndex);
        $content = base64_decode($file);

        $this->setDir(Config::envConfig('app.uploadFilePath'), StringTool::createUuid(), $ext);

        return $this->uploadContent($content);
    }

    //上传文件流
    public function uploadContent($content)
    {
        try {
            $ret = $this->_ossClient->putObject($this->_bucket, $this->_dir, $content);
            if ($ret['info']['http_code'] == 200) {
                return $this->_dir;
            } else {
                //上传错误
                Util::errorCode(RetCode::UPLOAD_ERROR);
            }
        } catch (OssException $e) {
            throw new EvaException($e->getMessage(), $e->getCode());
        }
    }

    //上传文件地址
    public function uploadPath($filePath)
    {
        try {
            $ret = $this->_ossClient->uploadFile($this->_bucket, $this->_dir, $filePath);
            @unlink($filePath);//删除文件
            if ($ret['info']['http_code'] == 200) {
                return $this->_dir;
            } else {
                Util::errorCode(RetCode::UPLOAD_ERROR);
            }
        } catch (OssException $e) {
            throw new EvaException($e->getMessage(), $e->getCode());
        }
    }

    //上传文件夹
    public function uploadDir($localDirectory, $ossDirPath)
    {
        try {
            $ret = $this->_ossClient->uploadDir($this->_bucket, $ossDirPath, $localDirectory, '.|..|.svn|.git', true);
            @Util::delDirAndFile($localDirectory, true);
            if (count($ret['succeededList'])) {
                return $ossDirPath;
            } else {
                Util::errorCode(RetCode::UPLOAD_ERROR);
            }
        } catch (OssException $e) {
            throw new EvaException($e->getMessage(), $e->getCode());
        }
    }

    private static function _getImageExt($file)
    {
        switch (getimagesize($file)[2]) {
            case 1:
                return 'gif';
            case 2:
                return 'jpeg';
            case 3:
                return 'png';
            default:
                Util::errorCode(RetCode::IMAGE_FORMAT_ERROR);
        }
    }
}