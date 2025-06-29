<?php

use App\Models\LeaveType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique()->index();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        $this->seed();
    }

    private function seed(): void
    {
        $types = [
            [
                'name' => 'Ordinary Leave',
                'description' => 'Leave for personal reasons',
            ],
            [
                'name' => 'Emergency Leave',
                'description' => 'Leave for emergency reasons',
            ],
            [
                'name' => 'Convalescent Leave',
                'description' => 'Leave for convalescent reasons',
            ],
            [
                'name' => 'Leave Without Pay',
                'description' => 'Leave without pay',
            ],
            [
                'name' => 'Paid Leave',
                'description' => 'Leave with pay',
            ],
            [
                'name' => 'Terminal Leave',
                'description' => 'Leave before retirement',
            ],
            [
                'name' => 'Other Leave',
                'description' => 'Leave for other reasons',
            ],
            [
                'name' => 'Supervisor Pass',
                'description' => 'Pass given by a supervisor',
            ],
            [
                'name' => 'Commanding Officer Pass',
                'description' => 'Pass given by the commanding officer',
            ],

        ];

        foreach ($types as $type) {
            LeaveType::create($type);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
