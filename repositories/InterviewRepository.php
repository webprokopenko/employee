<?php

namespace app\repositories;

use app\models\Interview;

class InterviewRepository
{
    /**
     * @param $id
     * @return Interview
     * @throws \InvalidArgumentException
     */
    public function find($id)
    {
        if (!$interview = Interview::findOne($id)) {
            throw new \InvalidArgumentException('Model not found');
        }
        return $interview;
    }

    public function add(Interview $interview)
    {
        if (!$interview->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $interview->insert(false);
    }

    public function save(Interview $interview)
    {
        if ($interview->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $interview->update(false);
    }
} 