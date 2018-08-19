<?php

namespace App\Logic\Weapp;

use App\Define\Common;
use App\Define\RetCode;
use App\Exceptions\EvaException;
use App\Formatter\UserFormatter;
use App\Logic\BaseLogic;
use App\Models\TimeModel;
use App\Models\UserModel;
use App\Tools\ArrayTool;
use App\Tools\StringTool;
use App\Tools\Util;
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
                return Util::errorCode(RetCode::WECHAT_LOGIN_ERR);
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
                return Util::errorCode(RetCode::WECHAT_LOGIN_ERR);
            }
        }

        $userModel = new UserModel();
        $userModelOne = $userModel->getByOpenId($weappInfo['openId']);

        if (empty($userModelOne)) {
            //新增用户
            $userModelOne = $userModel->addNew($weappInfo['openId'], $weappInfo['nickName'], $weappInfo['gender'], $weappInfo['city'], $weappInfo['province'], $weappInfo['country'], $weappInfo['avatarUrl']);

            //新增记录
            $timeModel = new TimeModel(StringTool::createUuid());
            $inputData = [
                'name'   => '安装惜时光',
                'type'   => Common::TIME_TYPE_ASC,
                'date'   => date('Ymd', time()),
                'color'  => ["#fc9e9a", "#fed89c"],
                'remark' => '记下珍贵的日子'
            ];
            $inputData['userId'] = $userModelOne->id;
            $timeModel->add($inputData, $userModelOne);
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

    public function checkAuth($auth)
    {
        $redis = Redis::get('cherishTime:' . $auth);
        if (empty($redis)) {
            return Util::errorCode(RetCode::ERR_NO_LOGIN);
        }

        $redisArray = json_decode($redis, true);

        return [
            'auth'     => $auth,
            'userInfo' => $redisArray['userInfo']
        ];
    }
}