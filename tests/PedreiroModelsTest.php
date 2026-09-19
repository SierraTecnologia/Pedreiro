<?php

namespace Pedreiro\Test;

use PHPUnit\Framework\Attributes\Test;
use Pedreiro\Models\ApiCallsCount;
use Pedreiro\Models\Attribute;
use Pedreiro\Models\AttributeEntity;
use Pedreiro\Models\Base;
use Pedreiro\Models\RedirectRule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class SamplePedreiroModel extends Base
{
    protected $table = 'sample_pedreiro_models';
    protected $fillable = ['code', 'name'];
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';
}

class PedreiroModelsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        Schema::create('sample_pedreiro_models', function (Blueprint $table) {
            $table->string('code')->primary();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    #[Test]
    public function it_runs_all_pedreiro_migrations()
    {
        $this->assertTrue(Schema::hasTable('redirect_rules'), 'redirect_rules table should exist');
        $this->assertTrue(Schema::hasTable('api_calls_count'), 'api_calls_count table should exist');
    }

    #[Test]
    public function it_supports_redirect_rule_crud()
    {
        $rule = RedirectRule::create([
            'from' => '/old-path',
            'to' => '/new-path',
            'code' => 301,
        ]);

        $this->assertNotNull($rule->id);
        $this->assertEquals('/old-path', $rule->from);
        $this->assertEquals('/new-path', $rule->to);

        $found = RedirectRule::where('from', '/old-path')->first();
        $this->assertNotNull($found);
        $this->assertEquals(301, $found->code);
    }

    #[Test]
    public function it_supports_api_calls_count_model()
    {
        $counter = new ApiCallsCount();
        $this->assertInstanceOf(ApiCallsCount::class, $counter);
    }

    #[Test]
    public function it_supports_base_create_if_not_exist_and_return()
    {
        $item = SamplePedreiroModel::createIfNotExistAndReturn([
            'code' => 'sample-1',
            'name' => 'First Sample',
        ]);

        $this->assertNotNull($item);
        $this->assertEquals('sample-1', $item->code);
        $this->assertEquals('First Sample', $item->name);

        $existing = SamplePedreiroModel::createIfNotExistAndReturn('sample-1');
        $this->assertEquals('First Sample', $existing->name);
        $this->assertEquals(1, SamplePedreiroModel::where('code', 'sample-1')->count());
    }
}
