<?php

namespace Src\Application\UseCases\Permission;

use Src\Application\Exceptions\BusinessException;
use Src\Application\UseCases\BaseUseCaseInterface;
use Src\Domain\Repositories\PermissionRepositoryInterface;

class FindPermissionUseCase implements BaseUseCaseInterface
{
    protected PermissionRepositoryInterface $repository;

    public function __construct(PermissionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function run(array $input)
    {
        $id = $input['id'];

        $output = $this->repository->find($id);

        if (! $output) {
            throw new BusinessException('Invalid id');
        }

        return $output;
    }
}
