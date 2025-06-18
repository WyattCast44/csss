<?php

use App\Models\Branch;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->index();
            $table->string('dodid')->unique()->index()->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('personal_email')->nullable()->unique();
            $table->string('personal_phone')->nullable();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();

            // Contact Information
            $table->json('phone_numbers')->nullable();
            $table->json('emails')->nullable();

            // military information
            $table->foreignId('branch_id')->nullable()->constrained();
            $table->foreignId('rank_id')->nullable()->constrained();
            $table->string('job_duty_code')->nullable(); // afsc, mos, etc.

            // organization information
            $table->foreignId('personal_organization_id')->nullable();
            $table->foreignId('current_organization_id')->nullable();

            // Stored / calculated columns

            // display name, format: Last, First Middle (if applicable)
            $table->string('display_name')->storedAs(
                'CONCAT_WS(" ", CONCAT(last_name, ", ", first_name), CONCAT(LEFT(IFNULL(middle_name, ""), 1), IF(middle_name IS NOT NULL, ".", "")))'
            );

            // filament 2fa
            $table->boolean('has_email_authentication')->default(false);

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        $this->seed();
    }

    private function seed(): void
    {
        User::create([
            'dodid' => '9999999999',
            'first_name' => 'System',
            'last_name' => 'User',
            'nickname' => 'System Account',
            'branch_id' => Branch::where('abbr', 'N/A')->first()->id,
            'rank_id' => Rank::where('abbr', 'N/A')->first()->id,
            'job_duty_code' => 'N/A',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
