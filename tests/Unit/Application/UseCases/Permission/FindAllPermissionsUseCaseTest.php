<?php

namespace Tests\Unit;

use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Application\UseCases\Permission\FindAllPermissionsUseCase;
use Src\Domain\Repositories\PermissionRepositoryInterface;

class FindAllPermissionsUseCaseTest extends TestCase
{
    public function test_should_find_all(): void
    {

        $repositoryMock = Mockery::mock(PermissionRepositoryInterface::class);

        $mockFindAll = [
            (object) [
                'id' => 1,
                'name' => 'create_permission',
                'guard_name' => 'api',
                'created_at' => 'now',
                'updated_at' => 'now',
            ],
        ];

        $repositoryMock
            ->shouldReceive('findAll')
            ->andReturn($mockFindAll);

        $useCase = new FindAllPermissionsUseCase($repositoryMock);

        $output = $useCase->run();

        $expectedOutput = $mockFindAll;

        $this->assertEquals($expectedOutput, $output);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
