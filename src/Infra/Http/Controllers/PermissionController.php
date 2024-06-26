<?php

namespace Src\Infra\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Src\Application\Dtos\UseCases\Permission\Create\CreatePermissionUseCaseInputDto;
use Src\Application\Dtos\UseCases\Permission\Delete\DeletePermissionUseCaseInputDto;
use Src\Application\Dtos\UseCases\Permission\Find\FindPermissionUseCaseInputDto;
use Src\Application\Dtos\UseCases\Permission\Update\UpdatePermissionUseCaseInputDto;
use Src\Application\Exceptions\BusinessException;
use Src\Application\Services\LoggerServiceInterface;
use Src\Application\UseCases\Permission\CreatePermissionUseCase;
use Src\Application\UseCases\Permission\DeletePermissionUseCase;
use Src\Application\UseCases\Permission\FindAllPermissionsUseCase;
use Src\Application\UseCases\Permission\FindPermissionUseCase;
use Src\Application\UseCases\Permission\UpdatePermissionUseCase;
use Src\Domain\Enums\HttpCode;
use Src\Infra\Exceptions\HttpException;
use Src\Infra\Helpers\Authorize;
use Src\Infra\Helpers\BaseResponse;
use Src\Infra\Http\Requests\Permission\PermissionRequest;

class PermissionController extends Controller
{
    protected LoggerServiceInterface $loggerService;

    protected FindAllPermissionsUseCase $findAllPermissionsUseCase;

    protected CreatePermissionUseCase $createPermissionUseCase;

    protected DeletePermissionUseCase $deletePermissionUseCase;

    protected FindPermissionUseCase $findPermissionUseCase;

    protected UpdatePermissionUseCase $updatePermissionUseCase;

    public function __construct(
        LoggerServiceInterface $loggerService,
        FindAllPermissionsUseCase $findAllPermissionsUseCase,
        CreatePermissionUseCase $createPermissionUseCase,
        DeletePermissionUseCase $deletePermissionUseCase,
        FindPermissionUseCase $findPermissionUseCase,
        UpdatePermissionUseCase $updatePermissionUseCase

    ) {
        $this->loggerService = $loggerService;
        $this->findAllPermissionsUseCase = $findAllPermissionsUseCase;
        $this->createPermissionUseCase = $createPermissionUseCase;
        $this->deletePermissionUseCase = $deletePermissionUseCase;
        $this->findPermissionUseCase = $findPermissionUseCase;
        $this->updatePermissionUseCase = $updatePermissionUseCase;
    }

    public function index(): JsonResponse
    {
        Authorize::hasPermission('read_all_permissions');

        try {

            $this->loggerService->info('START PermissionController index');

            $output = $this->findAllPermissionsUseCase->run();

            $this->loggerService->debug('Output PermissionController index', (object) $output);

            $this->loggerService->info('FINISH PermissionController index');

            return BaseResponse::successWithContent('Found permissions', HttpCode::OK, $output);

        } catch (BusinessException $exception) {

            $errorMessage = $exception->getMessage();

            $httpCode = HttpCode::INTERNAL_SERVER_ERROR;

            $this->loggerService->error('Error PermissionController index', $exception);

            throw new HttpException($errorMessage, $httpCode);
        }
    }

    public function store(PermissionRequest $request): JsonResponse
    {
        Authorize::hasPermission('create_permission');

        $input = new CreatePermissionUseCaseInputDto(...$request->all());

        try {

            $this->loggerService->info('START PermissionController store');

            $this->loggerService->debug('Input PermissionController store', $input);

            $this->createPermissionUseCase->run($input);

            $this->loggerService->info('FINISH PermissionController store');

            return BaseResponse::success('Permission created successfully', HttpCode::CREATED);

        } catch (BusinessException $exception) {

            $errorMessage = $exception->getMessage();

            $httpCode = HttpCode::INTERNAL_SERVER_ERROR;

            $isAlreadyExistsError = $errorMessage === 'Permission already exists';

            if ($isAlreadyExistsError) $httpCode = HttpCode::BAD_REQUEST;

            $this->loggerService->error('Error PermissionController store', $exception);

            throw new HttpException($errorMessage, $httpCode);
        }
    }

    public function show(int $id): JsonResponse
    {
        Authorize::hasPermission('read_permission');

        $input = new FindPermissionUseCaseInputDto($id);

        try {

            $this->loggerService->info('START PermissionController show');

            $this->loggerService->debug('Input PermissionController show', $input);

            $output = $this->findPermissionUseCase->run($input);

            $this->loggerService->debug('Output PermissionController show', $output);

            $this->loggerService->info('FINISH PermissionController show');

            return BaseResponse::successWithContent('Permission found', HttpCode::OK, $output);

        } catch (BusinessException $exception) {

            $errorMessage = $exception->getMessage();

            $httpCode = HttpCode::INTERNAL_SERVER_ERROR;

            $isInvalidId = $errorMessage === 'Invalid id';

            if ($isInvalidId) $httpCode = HttpCode::BAD_REQUEST;

            $this->loggerService->error('Error PermissionController show', $exception);

            throw new HttpException($errorMessage, $httpCode);
        }
    }

    public function update(PermissionRequest $request, int $id): JsonResponse
    {
        Authorize::hasPermission('update_permission');

        $name = $request->input('name');

        $input = new UpdatePermissionUseCaseInputDto($id, $name);

        try {

            $this->loggerService->info('START PermissionController update');

            $this->loggerService->debug('Input PermissionController update', $input);

            $this->updatePermissionUseCase->run($input);

            $this->loggerService->info('FINISH PermissionController update');

            return BaseResponse::success('Permission Updated successfully', HttpCode::OK);

        } catch (BusinessException $exception) {

            $errorMessage = $exception->getMessage();

            $httpCode = HttpCode::INTERNAL_SERVER_ERROR;

            $isInvalidId = $errorMessage === 'Invalid id';

            if ($isInvalidId) $httpCode = HttpCode::BAD_REQUEST;

            $this->loggerService->error('Error PermissionController update', $exception);

            throw new HttpException($errorMessage, $httpCode);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        Authorize::hasPermission('delete_permission');

        $input = new DeletePermissionUseCaseInputDto($id);

        try {

            $this->loggerService->info('START PermissionController destroy');

            $this->loggerService->debug('Input PermissionController destroy', $input);

            $this->deletePermissionUseCase->run($input);

            $this->loggerService->info('FINISH PermissionController destroy');

            return BaseResponse::success('Permission deleted successfully', HttpCode::OK);

        } catch (BusinessException $exception) {

            $errorMessage = $exception->getMessage();

            $httpCode = HttpCode::INTERNAL_SERVER_ERROR;

            $isInvalidId = $errorMessage === 'Invalid id';

            if ($isInvalidId)  $httpCode = HttpCode::BAD_REQUEST;

            $this->loggerService->error('Error PermissionController destroy', $exception);

            throw new HttpException($errorMessage, $httpCode);
        }
    }
}
