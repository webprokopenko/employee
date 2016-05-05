<?php

namespace app\repositories;

use app\models\Order;

class OrderRepository
{
    /**
     * @param $id
     * @return Order
     * @throws \InvalidArgumentException
     */
    public function find($id)
    {
        if (!$interview = Order::findOne($id)) {
            throw new \InvalidArgumentException('Model not found');
        }
        return $interview;
    }

    public function add(Order $interview)
    {
        if (!$interview->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $interview->insert(false);
    }

    public function save(Order $interview)
    {
        if ($interview->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $interview->update(false);
    }
} 