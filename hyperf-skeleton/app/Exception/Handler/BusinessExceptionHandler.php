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

namespace App\Exception\Handler;

use App\Exception\BusinessException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

use function Hyperf\Config\config;

class BusinessExceptionHandler extends ExceptionHandler
{
    // 依赖注入获取请求
    #[Inject]
    protected RequestInterface $request;

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 判断被捕获到的异常是希望被捕获的异常
        if ($throwable instanceof BusinessException) {
            $debug = config('app.app_debug', true);

            $code = $throwable->getCode();
            $json = [
                'code' => $code ? $code : 500,
                'message' => $code !== 500 ? $throwable->getMessage() : 'Server internal error',
            ];

            if ($debug) {
                $json['request_url'] = $this->request->getMethod() . ' ' . $this->request->url();
                $json['timestamp'] = date('Y-m-d H:i:s');
                $json['full_url'] = $this->request->fullUrl();
                $json['request_param'] = $this->request->all();
                $json['exception_handle'] = get_class($throwable);
                $json['exception_info'] = [
                    'code' => $throwable->getCode(),
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                    'trace' => explode("\n", $throwable->getTraceAsString()),
                ];
            }

            // 阻止异常冒泡
            $this->stopPropagation();
            return $response->withStatus(500)->withBody(new SwooleStream(json_encode($json, JSON_UNESCAPED_UNICODE)));
        }

        // 交给下一个异常处理器
        return $response;
    }

    /**
     * 判断该异常处理器是否要对该异常进行处理.
     */
    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
