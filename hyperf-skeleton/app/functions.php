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
use App\Enum\CommonEnum;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;
use Tinywan\Jwt\JwtToken;

if (! function_exists('success')) {
    /**
     * 成功返回json内容.
     */
    function success(ResponseInterface $response, array|string $data = [], int $code = CommonEnum::RETURN_CODE_SUCCESS, string $msg = 'success'): Psr7ResponseInterface
    {
        if (is_string($data) and $msg == 'success') {
            $msg = $data;
        }

        return $response->json(['code' => $code, 'message' => $msg, 'data' => $data]);
    }
}

if (! function_exists('fail')) {
    /**
     * 失败返回json内容.
     */
    function fail(ResponseInterface $response, string $message = 'fail', int $code = CommonEnum::RETURN_CODE_FAIL, $data = null): Psr7ResponseInterface
    {
        $return = [
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];

        return $response->json($return);
    }
}

if (! function_exists('getNumber')) {
    /**
     * 生成订单编号
     * 规则：$txt + 当前时间（年月日时分秒） + 随机4位随机数.
     */
    function getNumber(string $txt = 'DD'): string
    {
        // 获取当前日期时间格式化字符串，精确到秒
        $datetime = date('YmdHis');
        // 生成4位随机数
        $random = mt_rand(1000, 9999);
        return $txt . $datetime . $random;
    }
}

if (! function_exists('getOrderSn')) {
    /**
     * 生成唯一订单编号.
     *
     * @param string $prefix 订单前缀(可选，用于区分业务类型)
     * @return string 唯一订单编号
     */
    function getOrderSn(string $prefix = 'SN'): string
    {
        // 获取当前时间戳(精确到微秒)
        $microtime = microtime(true);
        // 将微秒时间戳转换为字符串并去掉小数点
        $timestamp = str_replace('.', '', sprintf('%.4f', $microtime));
        // 生成随机数(增加唯一性)
        $random = mt_rand(10000, 99999);
        // 组合各部分生成订单编号
        $orderNumber = $prefix . date('Ymd') . $timestamp . $random;
        // 确保长度一致，可以截取或填充
        // 限制最大长度
        return substr($orderNumber, 0, 32);
    }
}

if (! function_exists('convertAmountToCn')) {
    /**
     * 将数值金额转换为中文大写金额.
     * @param float $amount 金额(支持到分)
     * @param bool $isRound 是否对小数进行四舍五入
     * @return string 中文大写金额
     */
    function convertAmountToCn($amount, $isRound = true)
    {
        // 判断输出的金额是否为数字或数字字符串
        if (! is_numeric($amount)) {
            return '要转换的金额只能为数字!';
        }

        // 金额为 0,则直接输出"零元整"
        if ($amount == 0) {
            return '人民币零元整';
        }

        // 金额不能为负数
        if ($amount < 0) {
            return '要转换的金额不能为负数!';
        }

        // 金额不能超过万亿,即 12 位
        if (strlen($amount) > 12) {
            return '要转换的金额不能为万亿及更高金额!';
        }

        // 预定义中文转换的数组
        $digital = ['零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖'];
        // 预定义单位转换的数组
        $position = ['仟', '佰', '拾', '亿', '仟', '佰', '拾', '万', '仟', '佰', '拾', '元'];

        // 将金额的数值字符串拆分成数组
        $amountArr = explode('.', $amount);

        // 将整数位的数值字符串拆分成数组
        $integerArr = str_split($amountArr[0], 1);

        // 将整数部分替换成大写汉字
        $result = '';
        $integerArrLength = count($integerArr);     // 整数位数组的长度
        $positionLength = count($position);         // 单位数组的长度
        for ($i = 0; $i < $integerArrLength; ++$i) {
            // 如果数值不为 0,则正常转换
            if ($integerArr[$i] != 0) {
                $result = $result . $digital[$integerArr[$i]] . $position[$positionLength - $integerArrLength + $i];
            } else {
                // 如果数值为 0, 且单位是亿,万,元这三个的时候,则直接显示单位
                if (($positionLength - $integerArrLength + $i + 1) % 4 == 0) {
                    $result = $result . $position[$positionLength - $integerArrLength + $i];
                }
            }
        }

        // 如果小数位也要转换
        if (count($amountArr) > 1) {
            // 将小数位的数值字符串拆分成数组
            $decimalArr = str_split($amountArr[1], 1);
            // 将角替换成大写汉字. 如果为 0,则不替换
            if ($decimalArr[0] != 0) {
                $result = $result . $digital[$decimalArr[0]] . '角';
            }
            // 将分替换成大写汉字. 如果为 0,则不替换
            if (count($decimalArr) > 1 && $decimalArr[1] != 0) {
                $result = $result . $digital[$decimalArr[1]] . '分';
            }
        } else {
            $result = $result . '整';
        }

        return $result;
    }
}

if (! function_exists('getCurrentInfo')) {
    /**
     * 获取当前登录用户.
     */
    function getCurrentInfo(): array|bool
    {
        if (! request()) {
            return false;
        }
        try {
            $token = JwtToken::getExtend();
        } catch (Throwable $e) {
            return false;
        }
        return $token;
    }
}

if (! function_exists('getDistance')) {
    /**
     * 根据经纬度获取距离.
     * @param $lng :目标经度
     * @param $lat :目标维度
     * @param $currentLng :当前经度
     * @param $currentLat :当前纬度
     * @return float|int
     */
    function getDistance($lng, $lat, $currentLng, $currentLat)
    {
        if (empty($currentLng) || empty($currentLat) || empty($lng) || empty($lat)) {
            return 0;
        }

        // 地球半径（单位：米）
        $earthRadius = 6378138;
        // 将角度转为弧度
        $lat1 = deg2rad((float) $currentLat);
        $lng1 = deg2rad((float) $currentLng);
        $lat2 = deg2rad((float) $lat);
        $lng2 = deg2rad((float) $lng);
        // 计算差值
        $dLat = $lat2 - $lat1;
        $dLng = $lng2 - $lng1;
        // Haversine 公式
        $a = sin($dLat / 2) ** 2 + cos($lat1) * cos($lat2) * (sin($dLng / 2) ** 2);
        $c = 2 * asin(sqrt($a));
        // 距离（单位：米）
        $distanceInMeters = $earthRadius * $c;
        // 转换为千米，并保留两位小数
        return round($distanceInMeters / 1000, 2);
    }
}

if (! function_exists('phonePass')) {
    // 这是默认密码，手机号后六位
    function phonePass(string $phone): string
    {
        preg_match('/\d{6}$/', $phone, $matches);

        return password_hash($matches[0], PASSWORD_DEFAULT);
    }
}

if (! function_exists('getFileIds')) {
    /**
     * 获取文件id集合.
     */
    function getFileIds(array $file): string
    {
        if (empty($file)) {
            return 0;
        }
        return implode('-', array_column($file, 'id'));
    }
}
