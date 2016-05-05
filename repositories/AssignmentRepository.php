<?php

namespace app\repositories;

use app\models\Assignment;

class AssignmentRepository
{
    /**
     * @param $id
     * @return Assignment
     * @throws \InvalidArgumentException
     */
    public function find($id)
    {
        if (!$interview = Assignment::findOne($id)) {
            throw new \InvalidArgumentException('Model not found');
        }
        return $interview;
    }

    public function add(Assignment $interview)
    {
        if (!$interview->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $interview->insert(false);
    }

    public function save(Assignment $interview)
    {
        if ($interview->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $interview->update(false);
    }
} 