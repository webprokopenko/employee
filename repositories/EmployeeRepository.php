<?php

namespace app\repositories;

use app\models\Employee;

class EmployeeRepository
{
    /**
     * @param $id
     * @return Employee
     * @throws \InvalidArgumentException
     */
    public function find($id)
    {
        if (!$interview = Employee::findOne($id)) {
            throw new \InvalidArgumentException('Model not found');
        }
        return $interview;
    }

    public function add(Employee $interview)
    {
        if (!$interview->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $interview->insert(false);
    }

    public function save(Employee $interview)
    {
        if ($interview->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $interview->update(false);
    }
} 