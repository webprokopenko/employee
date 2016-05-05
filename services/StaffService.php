<?php

namespace app\services;

use app\models\Assignment;
use app\models\Contract;
use app\models\Employee;
use app\models\Interview;
use app\models\Order;
use app\models\Recruit;
use app\repositories\AssignmentRepository;
use app\repositories\ContractRepository;
use app\repositories\EmployeeRepository;
use app\repositories\InterviewRepository;
use app\repositories\OrderRepository;
use app\repositories\PositionRepository;
use app\repositories\RecruitRepository;

class StaffService
{
    private $logger;
    private $notifier;
    private $interviewRepository;
    private $employeeRepository;
    private $orderRepository;
    private $contractRepository;
    private $recruitRepository;
    private $transactionManager;
    private $assignmentRepository;
    private $positionRepository;

    public function __construct(
        InterviewRepository $interviewRepository,
        EmployeeRepository $employeeRepository,
        OrderRepository $orderRepository,
        ContractRepository $contractRepository,
        RecruitRepository $recruitRepository,
        AssignmentRepository $assignmentRepository,
        PositionRepository $positionRepository,
        TransactionManager $transactionManager,
        TransactionManager $transactionManager,
        LoggerInterface $logger,
        NotifierInterface $notifier
    )
    {
        $this->interviewRepository = $interviewRepository;
        $this->logger = $logger;
        $this->notifier = $notifier;
        $this->employeeRepository = $employeeRepository;
        $this->orderRepository = $orderRepository;
        $this->contractRepository = $contractRepository;
        $this->recruitRepository = $recruitRepository;
        $this->transactionManager = $transactionManager;
        $this->assignmentRepository = $assignmentRepository;
        $this->positionRepository = $positionRepository;
    }

    public function joinToInterview($lastName, $firstName, $email, $date)
    {
        $interview = Interview::create($lastName, $firstName, $email, $date);
        $this->interviewRepository->add($interview);
        if ($interview->email) {
            $this->notifier->notify('interview/join', ['model' => $interview], $interview->email, 'You are joined to interview!');
        }
        $this->logger->log('Interview ' . $interview->id . ' is created');
        return $interview;
    }

    public function editInterview($id, $lastName, $firstName, $email)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->editData($lastName, $firstName, $email);
        $this->interviewRepository->save($interview);
        $this->logger->log('Interview ' . $interview->id . ' is updated');
    }

    public function moveInterview($id, $date)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->move($date);
        $this->interviewRepository->save($interview);
        if ($interview->email) {
            $this->notifier->notify('interview/move', ['model' => $interview], $interview->email, 'Your interview is moved');
        }
        $this->logger->log('Interview ' . $interview->id . ' is moved on ' . $interview->date);
    }

    public function rejectInterview($id, $reason)
    {
        $interview = $this->interviewRepository->find($id);
        $interview->reject($reason);
        $this->interviewRepository->save($interview);
        if ($interview->email) {
            $this->notifier->notify('interview/reject', ['model' => $interview], $interview->email, 'You are fail an interview');
        }
        $this->logger->log('Interview ' . $interview->id . ' is rejected');
    }

    public function createEmployee($interviewId, $firstName, $lastName, $address, $email, $orderDate, $contractDate, $recruitDate)
    {
        try {
            $interview = $this->interviewRepository->find($interviewId);
        } catch (\InvalidArgumentException $e) {
            $interview = null;
        }
        $transaction = $this->transactionManager->begin();
        try {
            $employee = Employee::create($firstName, $lastName, $address, $email);
            $this->employeeRepository->add($employee);

            if ($interview) {
                $interview->pass($employee->id);
                $this->interviewRepository->save($interview);
            }

            $order = Order::create($orderDate);
            $this->orderRepository->add($order);

            $contract = Contract::create($employee->id, $lastName, $firstName, $contractDate);
            $this->contractRepository->add($contract);

            $recruit = Recruit::create($employee->id, $order->id, $recruitDate);
            $this->recruitRepository->add($recruit);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        if ($employee->email) {
            $this->notifier->notify('employee/probation', ['model' => $employee], $employee->email, 'You are recruit');
        }
        $this->logger->log('Employee ' . $employee->id . ' is recruit');

        return $employee;
    }

    public function assignEmployee($employeeId, $positionId, $rate, $salary, $date)
    {
        $employee = $this->employeeRepository->find($employeeId);
        $position = $this->positionRepository->find($positionId);

        $order = Order::create($date);
        $this->orderRepository->add($order);

        $assignment = Assignment::create($order->id, $employee->id, $position->id, $salary, $date, $rate);
        $this->assignmentRepository->add($assignment);

        if ($employee->email) {
            $this->notifier->notify('employee/probation', ['model' => $employee], $employee->email, 'You are recruit');
        }
        $this->logger->log('Employee ' . $employee->id . ' is assigned to ' . $position->name);
    }
}