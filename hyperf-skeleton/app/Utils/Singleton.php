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

namespace App\Utils;

trait Singleton
{
    private static $instance;

    /**
     * get instance.
     *
     * @param mixed ...$args
     *
     * @return static
     */
    public static function instance(...$args)
    {
        $className = md5(get_called_class() . serialize($args));
        if (! isset(self::$instance[$className])) {
            self::$instance[$className] = new static(...$args);
        }
        return self::$instance[$className];
    }
}
