<?php

namespace app\repositories;

use app\models\Contract;

class ContractRepository
{
    /**
     * @param $id
     * @return Contract
     * @throws \InvalidArgumentException
     */
    public function find($id)
    {
        if (!$interview = Contract::findOne($id)) {
            throw new \InvalidArgumentException('Model not found');
        }
        return $interview;
    }

    public function add(Contract $interview)
    {
        if (!$interview->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $interview->insert(false);
    }

    public function save(Contract $interview)
    {
        if ($interview->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $interview->update(false);
    }
} 