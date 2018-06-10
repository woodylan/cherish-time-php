<?php

namespace App\Http\Controllers;

use App\Define\RetCode;
use App\Exceptions\ApiInternalServerException;
use App\Tools\ArrayTool;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    // auth
    protected $auth;
    // 设备号
    protected $deviceId;
    // 客户端平台 1:android 2:ios
    protected $platform;
    // 客户端版本
    protected $version;
    // 客户端渠道
    protected $channel;
    // 系统
    protected $system;
    // 客户端型号 iPhoneX iPhone8
    protected $model;
    // 输入的data
    protected $inputData;

    public function __construct()
    {
        $this->_getBaseParam();
        $this->_getInputData();
        $this->apiValidate();
    }

    private function _getBaseParam()
    {
        $request = app()->request;
        $this->auth = app()->request->headers->get('Auth');

        if (empty($this->auth)) {
            $this->auth = $request->input('auth');
        }
        // 基础字段
        $this->deviceId = $this->_input['deviceId'] ?? '';
        $this->platform = $this->_input['platform'] ?? '';
        $this->version  = $this->_input['version'] ?? '';
        $this->channel  = $this->_input['channel'] ?? '';
        $this->system   = $this->_input['system'] ?? '';
        $this->model    = $this->_input['model'] ?? '';
    }

    private function _getInputData()
    {
        $dataString = app()->request->input('data');
        if ($dataString && !is_string($dataString)) {
            throw new ApiInternalServerException("data request error");
        }
        $inputData = $dataString ? json_decode($dataString, true) : [];

        if (is_array($inputData)) {
            //将键名转换为小写
            $this->inputData = ArrayTool::lcFirstArray($inputData, false, true);
        }
    }

    protected function getUserId()
    {
        $user = \Auth::user();
        return $user->userId;
    }

    protected function getUser()
    {
        return \Auth::user();
    }

    protected function getAdminUser()
    {
        return \Auth::adminUser();
    }

    protected function only($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();
        return array_only($this->inputData, $keys);
    }

    protected function all()
    {
        return $this->inputData ?: [];
    }

    protected function input($key, $default = null)
    {
        return array_get($this->inputData, $key, $default);
    }

    protected function exists($key)
    {
        return array_key_exists($key, $this->inputData ?: []);
    }

    protected function apiValidate()
    {
        //如果不存在该方法则停止
        if (!method_exists($this, 'rules')) {
            return;
        }

        //调用子类的rules方法
        $paramRules = $this->rules();

        //如果没有验证规则则停止
        if (empty($paramRules)) {
            return;
        }

        $rules = [];
        $attributeName = [];

        foreach ($paramRules as $paramName => $value) {
            //构建 验证规则数组 和 别名数组
            list($rules[$paramName], $attributeName[$paramName]) = $value;
        }
        $validation = app()->validator->make($this->all(), $rules)->setAttributeNames($attributeName);
        //验证失败抛出异常
        if ($validation->fails()) {
            throw new ApiInternalServerException($validation->messages()->first(), RetCode::PARAM_ERROR);
        }
    }

    protected function render($code, $msg, $data = array())
    {
        $retData = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data ?: (object)[],
        ];

        return $retData;
    }

}
