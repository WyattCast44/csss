<?php

use App\Models\Branch;
use App\Models\OrganizationCommand;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_commands', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->string('name');
            $table->string('abbr');
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->text('logo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $this->seed();
    }

    protected function seed(): void
    {
        $usaf = Branch::where('abbr', 'USAF')->firstOrFail();

        $commands = [
            ['name' => 'Air Force Global Strike Command', 'abbr' => 'AFGSC'],
            ['name' => 'Air Force Special Operations Command', 'abbr' => 'AFSOC'],
            ['name' => 'Air Combat Command', 'abbr' => 'ACC'],
            ['name' => 'Air Education and Training Command', 'abbr' => 'AETC'],
            ['name' => 'Air Force Materiel Command', 'abbr' => 'AFMC'],
            ['name' => 'Air Force Reserve Command', 'abbr' => 'AFRC'],
            ['name' => 'Air Force Space Command', 'abbr' => 'AFSPC'],
        ];

        foreach ($commands as $command) {
            OrganizationCommand::create([
                'name' => $command['name'],
                'abbr' => $command['abbr'],
                'branch_id' => $usaf->id,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_commands');
    }
};
