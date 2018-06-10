<?php

namespace App\Http\Controllers\Common;

use App\Define\RetCode;
use App\Http\Controllers\Controller;
use App\Tools\Config;
use App\Tools\StringTool;
use App\Tools\Upload;
use App\Tools\Util;
use Illuminate\Http\Request;

/**
 * @apiDefine common 通用
 *            通用相关接口
 */
class UploadFile extends Controller
{
    /**
     * @api {post} /api/admin/v1/common/upload_file 上传文件
     * @apiGroup common
     *
     * @apiParam {String} auth Auth
     *
     * @apiSuccessExample Success-Response:
     * {"code":0,"msg":"success","data":{"url":"steam\/file\/test\/3929ecb655a87ca1.png","name":"\u5c4f\u5e55\u5feb\u7167 2017-11-08 \u4e0a\u534810.24.09 (2).png"}}
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

        $extension = $file->getClientOriginalExtension();
        $extension = strtolower($extension); //mp4 jpg

        $content = file_get_contents($filePath);

        //上传文件
        $uploadToOSS = new Upload();
        $uploadToOSS->setDir(Config::envConfig('app.uploadFilePath'), StringTool::uuid(), $extension);
        $url = $uploadToOSS->uploadContent($content);

        return $this->render(RetCode::SUCCESS, 'success', [
            'url'  => $url,
            'name' => $file->getClientOriginalName()
        ]);
    }
}