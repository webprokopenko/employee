<?php

namespace app\repositories;

use app\models\Position;

class PositionRepository
{
    /**
     * @param $id
     * @return Position
     * @throws \InvalidArgumentException
     */
    public function find($id)
    {
        if (!$interview = Position::findOne($id)) {
            throw new \InvalidArgumentException('Model not found');
        }
        return $interview;
    }

    public function add(Position $interview)
    {
        if (!$interview->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $interview->insert(false);
    }

    public function save(Position $interview)
    {
        if ($interview->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $interview->update(false);
    }
} 