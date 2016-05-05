<?php

namespace app\repositories;

use app\models\Recruit;

class RecruitRepository
{
    /**
     * @param $id
     * @return Recruit
     * @throws \InvalidArgumentException
     */
    public function find($id)
    {
        if (!$interview = Recruit::findOne($id)) {
            throw new \InvalidArgumentException('Model not found');
        }
        return $interview;
    }

    public function add(Recruit $interview)
    {
        if (!$interview->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $interview->insert(false);
    }

    public function save(Recruit $interview)
    {
        if ($interview->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $interview->update(false);
    }
} 