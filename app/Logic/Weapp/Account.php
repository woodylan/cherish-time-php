<?php

namespace App\Logic\Weapp;

use App\Exceptions\EvaException;
use App\Formatter\UserFormatter;
use App\Logic\BaseLogic;
use App\Models\UserModel;
use App\Tools\ArrayTool;
use App\Tools\StringTool;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\HttpException;
use Illuminate\Support\Facades\Redis;

class Account extends BaseLogic
{
    const AUTH_EXIST_TIME = 86400; //24个小时

    public function login($code, $iv, $encryptedData)
    {
        $config = [
            'app_id'        => env('WECHAT_MINI_PROGRAM_APPID'),
            'secret'        => env('WECHAT_MINI_PROGRAM_SECRET'),

            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

//            'log' => [
//                'level' => 'debug',
//                'file'  => __DIR__ . '/wechat.log',
//            ],
        ];

        $miniProgram = Factory::miniProgram($config);

        //通过code获取sessionKey
        try {
            $sessionKey = $miniProgram->auth->session($code);
        } catch (HttpException $e) {
            \Log::error('code获取sessionKey失败:' . $e->getMessage());
            if (env('APP_DEBUG', false)) {
                throw new EvaException('小程序报错：' . $e->getMessage(), $e->getCode());
            } else {
                //登录失败
                return errorCode('logic.errWechatLogin');
            }
        }

        //通过sessionKey、iv、encryptedData获取用户信息
        try {
            $weappInfo = $miniProgram->encryptor->decryptData($sessionKey['session_key'], $iv, $encryptedData);
        } catch (HttpException $e) {
            \Log::error('获取微信用户信息失败:' . $e->getMessage());
            if (env('APP_DEBUG', false)) {
                throw new EvaException('小程序报错：' . $e->getMessage(), $e->getCode());
            } else {
                //登录失败
                return errorCode('logic.errWechatLogin');
            }
        }

        $userModel = new UserModel();
        $userModelOne = $userModel->getByOpenId($weappInfo['openId']);

        if (empty($userModelOne)) {
            //新增用户
            $userModelOne = $userModel->addNew($weappInfo['openId'], $weappInfo['nickName'], $weappInfo['gender'], $weappInfo['city'], $weappInfo['province'], $weappInfo['country'], $weappInfo['avatarUrl']);


        } else {
            //更新用户信息
            $userModelOne = $userModelOne->updateUserInfo($weappInfo['nickName'], $weappInfo['gender'], $weappInfo['city'], $weappInfo['province'], $weappInfo['country']);
        }

        $userInfo = ArrayTool::modelToArray($userModelOne, [
            new UserFormatter(),
            'userDetailFormat'
        ]);

        $auth = StringTool::createUuid();
        $authArray = [
            'userId'   => $userModelOne->id,
            'userInfo' => $userInfo,
        ];

        Redis::setex('cherishTime:' . $auth, self::AUTH_EXIST_TIME, json_encode($authArray));

        return [
            'auth'     => $auth,
            'userInfo' => $userInfo,
        ];
    }
}