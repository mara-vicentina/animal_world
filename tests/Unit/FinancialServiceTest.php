<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\FinancialService;
use App\Models\Financial;
use PHPUnit\Framework\Attributes\Test;
use Mockery;

class FinancialServiceTest extends TestCase
{
    protected FinancialService $service;
    protected $financialMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->financialMock = Mockery::mock(Financial::class)->makePartial();
        $this->service = new FinancialService($this->financialMock);
    }

    #[Test]
    public function store_should_create_financial_record()
    {
        $this->financialMock->shouldReceive('save')->once()->andReturnTrue();

        $data = [
            'name'  => 'Salário',
            'type'  => 1,
            'date'  => now()->toDateString(),
            'value' => 3000.00,
        ];
        $userId = 1;

        $result = $this->service->store($data, $userId);

        $this->assertEquals('Salário', $result->name);
        $this->assertEquals($userId, $result->user_id);
    }

    #[Test]
    public function store_should_handle_multiple_records()
    {
        $this->financialMock->shouldReceive('save')->twice()->andReturnTrue();

        $userId = 2;

        $result1 = $this->service->store([
            'name'  => 'Venda',
            'type'  => 1,
            'date'  => now()->toDateString(),
            'value' => 500.00,
        ], $userId);

        $result2 = $this->service->store([
            'name'  => 'Compra',
            'type'  => 0,
            'date'  => now()->toDateString(),
            'value' => 200.00,
        ], $userId);

        $this->assertEquals('Venda', $result1->name);
        $this->assertEquals('Compra', $result2->name);
    }

    #[Test]
    public function update_should_modify_existing_record()
    {
        $mock = Mockery::mock(Financial::class)->makePartial();
        $mock->shouldReceive('save')->once()->andReturnTrue();

        $mockRepository = Mockery::mock(Financial::class);
        $mockRepository->shouldReceive('find')->with(1)->andReturn($mock);

        $service = new FinancialService($mockRepository);

        $data = [
            'name'  => 'Atualizado',
            'type'  => 1,
            'date'  => now()->toDateString(),
            'value' => 1500.00,
        ];

        $result = $service->update(1, $data);

        $this->assertNotNull($result);
        $this->assertEquals('Atualizado', $result->name);
    }

    #[Test]
    public function update_should_return_null_if_record_not_found()
    {
        $this->financialMock->shouldReceive('find')->with(9999)->andReturn(null);

        $result = $this->service->update(9999, [
            'name'  => 'Teste',
            'type'  => 1,
            'date'  => now()->toDateString(),
            'value' => 1000.00,
        ]);

        $this->assertNull($result);
    }

    #[Test]
    public function destroy_should_delete_record()
    {
        $mock = Mockery::mock(Financial::class)->makePartial();
        $mock->shouldReceive('delete')->once()->andReturnTrue();

        $mockRepository = Mockery::mock(Financial::class);
        $mockRepository->shouldReceive('find')->with(1)->andReturn($mock);

        $service = new FinancialService($mockRepository);

        $result = $service->destroy(1);

        $this->assertTrue($result);
    }

    #[Test]
    public function destroy_should_return_false_if_not_found()
    {
        $this->financialMock->shouldReceive('find')->with(9999)->andReturn(null);

        $result = $this->service->destroy(9999);

        $this->assertFalse($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
