<?php

use App\Define\RetCode;

return [
    // 参数错误
    RetCode::ERR_PARAM                    => ['msg' => '参数不正确'],

    // 系统错误
    //-10~-99表示系统级错误
    RetCode::ERR_WRONG_REDIS_OPERATE      => ['msg' => 'redis操作错误'],//redis操作错误
    RetCode::ERR_WRONG_MYSQL_OPERATE      => ['msg' => 'mysql操作错误'], //mysql操作错误
    RetCode::ERR_WRONG_CACHE_OPERATE      => ['msg' => 'memcache操作错误'], //memcache操作错误
    RetCode::ERR_WRONG_SYSTEM_OPERATE     => ['msg' => '系统错误'], //系统错误
    RetCode::ERR_WRONG_FORMAT_JSON        => ['msg' => 'json格式化错误'], //json格式化错误
    RetCode::ERR_WRONG_HTTP_GET_REQUEST   => ['msg' => 'http get请求错误'], //http get请求错误
    RetCode::ERR_WRONG_HTTP_POST_REQUEST  => ['msg' => 'http post请求错误'], //http post请求错误
    RetCode::ERR_WRONG_WECHAT_API_REQUEST => ['msg' => '微信请求接口错误'], //微信请求接口错误
    RetCode::ERR_WRONG_PINGPP_API_REQUEST => ['msg' => 'ping++支付申请失败'], //ping++支付申请失败

    // 公共错误
    RetCode::ERR_NO_LOGIN                 => ['msg' => '用户未登录'],
    RetCode::ERR_ACCOUNT                  => ['msg' => '帐号或密码错误'],
    RetCode::ERR_ACCESS_DENIED            => ['msg' => '没有权限'],
    RetCode::ERR_NO_WECHAT_LOGIN          => ['msg' => '微信未登录'],
    RetCode::ERR_WECHAT_NO_BIND_PHONE     => ['msg' => '未绑定手机号'],
    RetCode::ERR_CAPTCHA_INVALID          => ['msg' => '验证码不正确'],
    RetCode::UPLOAD_ERROR                 => ['msg' => '上传错误'],
    RetCode::IMAGE_FORMAT_ERROR           => ['msg' => '图片格式错误'],
    RetCode::FILE_IS_EMPTY                => ['msg' => '没有上传文件'],
    RetCode::NOT_ZIP_FILE                 => ['msg' => '上传的非zip文件'],
    RetCode::ZIP_ERROR                    => ['msg' => '解压失败'],
    RetCode::NOT_INDEX_HTML_FILE          => ['msg' => '不存在index.html文件'],
    RetCode::WECHAT_LOGIN_ERR             => ['msg' => '微信登录失败'],

    // 基础逻辑错误
    RetCode::ERR_OBJECT_NOT_FOUND         => ['msg' => '数据不存在'],
    RetCode::ERR_LOST_ID_ARGUMENT         => ['msg' => '缺少ID'],

];