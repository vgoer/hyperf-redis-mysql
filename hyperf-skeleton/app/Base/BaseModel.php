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

namespace App\Base;

use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model as HyperfModel;

class BaseModel extends HyperfModel
{
    // 软删除
    use SoftDeletes;
}
