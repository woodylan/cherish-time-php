<?php

namespace App\Http\Controllers\Common;


use App\Define\RetCode;
use App\Http\Controllers\Controller;
use App\Tools\Config;
use App\Tools\StringTool;
use App\Tools\Upload;
use App\Tools\Util;
use Illuminate\Http\Request;

class UploadZip extends Controller
{
    /**
     * @api {post} /api/admin/v1/common/upload_zip 上传zip文件
     * @apiGroup common
     *
     * @apiParam {String} auth Auth
     *
     * @apiSuccessExample Success-Response:
     * {"code":0,"msg":"success","data":{"url":"course\/file\/test\/ba7bd7be22074d3aa994da751f597cf6","name":"game1.zip"}}
     */
    public function run(Request $request)
    {
        //判断请求中是否包含name=file的上传文件
        if (!$request->hasFile('file')) {
            //没有上传文件
            Util::errorCode(RetCode::FILE_IS_EMPTY);
        }

        $file = $request->file('file');
        //判断文件上传过程中是否出错
        if (!$file->isValid()) {
            //上传文件出错
            Util::errorCode(RetCode::UPLOAD_ERROR);
        }

        $filePath = $file->getRealPath();

        $zip = new \ZipArchive();

        if ($zip->open($filePath) !== true) {
            //上传的非zip文件
            Util::errorCode(RetCode::NOT_ZIP_FILE);
        }

        $dirName = StringTool::createUuid();
        $localDirectory = storage_path('zip') . "/{$dirName}";

        //解压压缩包
        if ($zip->extractTo($localDirectory) !== true) {
            //解压失败
            @Util::delDirAndFile($localDirectory, true);
            Util::errorCode(RetCode::NOT_ZIP_FILE);
        }

        $zip->close();

        if (file_exists($localDirectory . '/index.html') === false) {
            //不存在index.html文件
            @Util::delDirAndFile($localDirectory, true);
            Util::errorCode(RetCode::NOT_INDEX_HTML_FILE);
        }

        //上传文件夹
        $ossDirPath = Config::envConfig('app.uploadFilePath') . $dirName;
        $uploadToOSS = new Upload();
        $url = $uploadToOSS->uploadDir($localDirectory, $ossDirPath);

        return $this->render(RetCode::SUCCESS, 'success', [
            'url'  => $url,
            'name' => $file->getClientOriginalName()
        ]);
    }
}