<?php

declare(strict_types=1);
/**
 * This file is part of goer.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  3088760685@qq.com
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Enum;

enum CommonEnum
{
    /**
     * 状态-正常.
     */
    public const STATUS_Y = 10;

    /**
     * 状态-禁用.
     */
    public const STATUS_N = 20;

    /**
     * 成功返回code.
     */
    public const RETURN_CODE_SUCCESS = 200;

    /**
     * 需要弹窗提示的code.
     */
    public const RETURN_CODE_ERROR_PARAM = 300;

    /**
     * 失败返回code.
     */
    public const RETURN_CODE_FAIL = 400;

    /**
     * 认证失败返回code.
     */
    public const RETURN_CODE_ERROR_AUTH = 401;

    /**
     * 微信自动登录失败-跳转至短信登录.
     */
    public const LOGIN_FAIL = 402;

    /**
     * 认证失败返回code.
     */
    public const RETURN_CODE_NOT_FOUND = 404;

    /**
     * 服务器错误code.
     */
    public const RETURN_CODE_SERVER_ERROR = 500;
}
