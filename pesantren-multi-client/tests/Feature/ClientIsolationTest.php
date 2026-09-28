<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientUser;
use App\Models\Student;
use App\Models\User;
use App\Support\ClientContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ClientIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected Client $clientA;
    protected Client $clientB;
    protected User $userA;
    protected User $userB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientA = Client::create([
            'name' => 'Client A', 'slug' => 'client-a', 'status' => 'active',
        ]);

        $this->clientB = Client::create([
            'name' => 'Client B', 'slug' => 'client-b', 'status' => 'active',
        ]);

        $this->userA = User::create([
            'name' => 'User A', 'email' => 'a@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin_pesantren', 'status' => 'active',
        ]);

        $this->userB = User::create([
            'name' => 'User B', 'email' => 'b@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin_pesantren', 'status' => 'active',
        ]);

        ClientUser::create([
            'client_id' => $this->clientA->id,
            'user_id'   => $this->userA->id,
            'role'      => 'admin_pesantren',
            'is_active' => true,
        ]);

        ClientUser::create([
            'client_id' => $this->clientB->id,
            'user_id'   => $this->userB->id,
            'role'      => 'admin_pesantren',
            'is_active' => true,
        ]);
    }

    #[Test]
    public function global_scope_only_returns_active_client_rows(): void
    {
        $ctx = app(ClientContext::class);

        $ctx->set($this->clientA);
        Student::create([
            'name' => 'Student A1', 'student_number' => 'A001',
            'qr_token' => 'QA1', 'status' => 'active',
        ]);
        Student::create([
            'name' => 'Student A2', 'student_number' => 'A002',
            'qr_token' => 'QA2', 'status' => 'active',
        ]);

        $ctx->set($this->clientB);
        Student::create([
            'name' => 'Student B1', 'student_number' => 'B001',
            'qr_token' => 'QB1', 'status' => 'active',
        ]);

        $ctx->set($this->clientA);
        $this->assertEquals(2, Student::count());

        $ctx->set($this->clientB);
        $this->assertEquals(1, Student::count());

        $ctx->clear();
    }

    #[Test]
    public function context_is_cleared_between_requests(): void
    {
        $ctx = app(ClientContext::class);
        $ctx->set($this->clientA);

        $this->assertTrue($ctx->has());

        $this->app->forgetScopedInstances();

        $this->assertFalse(
            app(ClientContext::class)->has(),
            'ClientContext bocor antar request'
        );
    }

    #[Test]
    public function policy_denies_cross_client_view(): void
    {
        $ctx = app(ClientContext::class);

        $ctx->set($this->clientB);
        $studentB = Student::create([
            'name' => 'Student B', 'student_number' => 'B001',
            'qr_token' => 'QB', 'status' => 'active',
        ]);

        $ctx->set($this->clientA);

        $this->assertFalse($this->userA->can('view', $studentB));

        $ctx->clear();
    }

    #[Test]
    public function policy_allows_same_client_view(): void
    {
        $ctx = app(ClientContext::class);
        $ctx->set($this->clientA);

        $studentA = Student::create([
            'name' => 'Student A', 'student_number' => 'A001',
            'qr_token' => 'QA', 'status' => 'active',
        ]);

        $this->assertTrue($this->userA->can('view', $studentA));

        $ctx->clear();
    }
}
