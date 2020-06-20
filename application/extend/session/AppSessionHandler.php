<?php

/*
 * 用户会话 Handler
 *
 * @Created: 2020-06-19 13:04:39
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\session;

use app\constants\common\AppCacheCode;
use app\exception\AppException;
use app\extend\common\AppCache;
use app\extend\common\AppResponse;
use app\traits\common\EntityMake;
use Firebase\JWT\JWT;

class AppSessionHandler {

    use EntityMake;

    /**
     * 混淆密钥
     *
     * @var string
     */
    protected $jwtKey = '';

    public function __construct() {
        $this->jwtKey = env('JWT.KEY');
    }

    /**
     * 生成TOKEN
     *
     * @param integer $uid 用户uid
     * @return string token
     */
    public function buildToken($uid) {
        $code = $this->buildCode($uid);

        // 标识该code生效
        AppCache::tag(AppCacheCode::USER__SESSION_CODE)->set($uid, ['code' => $code]);

        // 根据code生成token
        $token = JWT::encode(['uid' => $uid, 'code' => $code, 'time' => milliTimestamp()], $this->jwtKey, 'HS256');

        return $token;
    }

    /**
     * 解析TOKEN
     *
     * @param string $token
     * @return AppSession
     * @throws AppException
     */
    public function parseToken($token) {
        try {
            $tokenInfo = JWT::decode($token, $this->jwtKey, ['HS256']);
            $tokenInfo = (array) $tokenInfo;
        } catch (\Exception $e) {
            throw new AppException('token信息有误');
        }

        // 判断token携带的信息是否有效
        if (!$tokenInfo || empty($tokenInfo['code']) || empty($tokenInfo['uid'])) {
            throw new AppException('token无效');
        }

        // 判断token中的code是否有效
        $userSession = AppCache::tag(AppCacheCode::USER__SESSION_CODE)->get($tokenInfo['uid']);
        if (!$userSession || empty($userSession['code']) || $tokenInfo['code'] != $userSession['code']) {
            throw new AppException('token无效或已过期');
        }

        return AppSession::make()->setSession($tokenInfo['uid'], $token);
    }

    /**
     * 构建32位code
     *
     * @param string $confuse 混淆符
     * @return string code
     */
    private function buildCode($confuse = '') {
        // 生成code (1~18位的随机字符串 + 毫秒时间戳 + 自定义的混淆字符串)
        $code = randomStr(max(18 - strlen($confuse), 1)) . milliTimestamp() . $confuse;

        // 截取前32位
        $code = substr($code, 0, 32);

        return $code;
    }

}