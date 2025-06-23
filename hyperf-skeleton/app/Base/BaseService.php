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

use app\enum\CommonEnum;
use support\exception\BusinessException;
use utils\Date;

class BaseService
{
    /**
     * @var bool 数据边界启用状态
     */
    protected $scope = false;

    /**
     * 排序字段.
     * @var string
     */
    protected $orderField = '';

    /**
     * 排序方式.
     * @var string
     */
    protected $orderType = 'DESC';

    /**
     * 设置数据边界.
     * @param mixed $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * 设置排序字段.
     * @param mixed $field
     */
    public function setOrderField($field)
    {
        $this->orderField = $field;
    }

    /**
     * 设置排序方式.
     * @param mixed $type
     */
    public function setOrderType($type)
    {
        $this->orderType = $type;
    }

    /**
     * 分页查询数据.
     * @param mixed $query
     * @return mixed
     */
    public function getList($query)
    {
        $saiType = request()->input('saiType', 'list');
        $page = request()->input('page', 1);
        $limit = request()->input('limit', 10);
        $orderBy = request()->input('orderBy', '');
        $orderType = request()->input('orderType', $this->orderType);

        if ($page < 1 || $limit < 1) {
            throw new BusinessException('非法参数', CommonEnum::RETURN_CODE_FAIL);
        }
        if ($limit > 30) {
            throw new BusinessException('前端数据不能超过30条', CommonEnum::RETURN_CODE_FAIL);
        }

        if (empty($orderBy)) {
            $orderBy = $this->orderField !== '' ? $this->orderField : 'id';
        }
        $query->order($orderBy, $orderType);
        if ($saiType === 'all') {
            $data['data'] = $query->select()->toArray();
            return $data;
        }
        return $query->paginate($limit, false, ['page' => $page])->toArray();
    }

    /**
     * 获取全部数据.
     * @param mixed $query
     * @return mixed
     */
    public function getAll($query)
    {
        $orderBy = request()->input('orderBy', '');
        $orderType = request()->input('orderType', $this->orderType);

        if (empty($orderBy)) {
            $orderBy = $this->orderField !== '' ? $this->orderField : $this->model->getPk();
        }
        $query->order($orderBy, $orderType);
        return $query->select()->toArray();
    }

    public function editStatus($query)
    {
        $id = request()->post('id', '');
        $status = request()->post('status', '');
        if (! in_array($status, [CommonEnum::STATUS_Y, CommonEnum::STATUS_N])) {
            throw new BusinessException('状态值错误', CommonEnum::RETURN_CODE_FAIL);
        }
        if (empty($id)) {
            throw new BusinessException('参数错误', CommonEnum::RETURN_CODE_FAIL);
        }
        $data = [
            'status' => $status,
            'update_time' => Date::now(),
        ];
        return $query->getQuery()
            ->where('id', $id)
            ->update($data);
    }
}
