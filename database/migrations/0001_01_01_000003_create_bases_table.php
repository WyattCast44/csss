<?php

use App\Models\Base;
use App\Models\Branch;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bases', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->string('name')->index();
            $table->string('abbr')->index();
            $table->string('icao_code', 8)->nullable()->index();
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->timestamps();
            $table->softDeletes();
        });

        $this->seed();
    }

    protected function seed(): void
    {
        $usaf = Branch::where('abbr', 'USAF')->firstOrFail();

        $bases = [
            ['name' => 'Altus Air Force Base', 'abbr' => 'AAFB', 'icao_code' => 'KLTS'],
            ['name' => 'Andersen Air Force Base', 'abbr' => 'AAFB-GU', 'icao_code' => 'PGUA'],
            ['name' => 'Barksdale Air Force Base', 'abbr' => 'BAFB', 'icao_code' => 'KBAD'],
            ['name' => 'Beale Air Force Base', 'abbr' => 'BAFB-CA', 'icao_code' => 'KBAB'],
            ['name' => 'Cannon Air Force Base', 'abbr' => 'CAFB-NM', 'icao_code' => 'KCVS'],
            ['name' => 'Columbus Air Force Base', 'abbr' => 'CAFB-MS', 'icao_code' => 'KCBM'],
            ['name' => 'Creech Air Force Base', 'abbr' => 'CAFB', 'icao_code' => 'KINS'],
            ['name' => 'Davis-Monthan Air Force Base', 'abbr' => 'DMAFB', 'icao_code' => 'KDMA'],
            ['name' => 'Dover Air Force Base', 'abbr' => 'DAFB', 'icao_code' => 'KDOV'],
            ['name' => 'Dyess Air Force Base', 'abbr' => 'DAFB-TX', 'icao_code' => 'KDYS'],
            ['name' => 'Eglin Air Force Base', 'abbr' => 'EAFB', 'icao_code' => 'KVPS'],
            ['name' => 'Ellsworth Air Force Base', 'abbr' => 'EAFB-SD', 'icao_code' => 'KRCA'],
            ['name' => 'Fairchild Air Force Base', 'abbr' => 'FAFB', 'icao_code' => 'KSKA'],
            ['name' => 'Goodfellow Air Force Base', 'abbr' => 'GAFB', 'icao_code' => null],
            ['name' => 'Grand Forks Air Force Base', 'abbr' => 'GFAFB', 'icao_code' => 'KRDR'],
            ['name' => 'Hanscom Air Force Base', 'abbr' => 'HAFB', 'icao_code' => 'KBED'],
            ['name' => 'Hill Air Force Base', 'abbr' => 'HAFB-UT', 'icao_code' => 'KHIF'],
            ['name' => 'Holloman Air Force Base', 'abbr' => 'HAFB-NM', 'icao_code' => 'KHMN'],
            ['name' => 'Hurlburt Field', 'abbr' => 'HF', 'icao_code' => 'KHRT'],
            ['name' => 'Joint Base Andrews', 'abbr' => 'JBA', 'icao_code' => 'KADW'],
            ['name' => 'Joint Base Charleston', 'abbr' => 'JBC', 'icao_code' => 'KCHS'],
            ['name' => 'Joint Base Elmendorf-Richardson', 'abbr' => 'JBER', 'icao_code' => 'PAED'],
            ['name' => 'Joint Base Langley-Eustis', 'abbr' => 'JBLE', 'icao_code' => 'KLFI'],
            ['name' => 'Joint Base McGuire-Dix-Lakehurst', 'abbr' => 'JBMDL', 'icao_code' => 'KWRI'],
            ['name' => 'Joint Base San Antonio–Lackland', 'abbr' => 'JBSA-LAK', 'icao_code' => 'KSKF'],
            ['name' => 'Joint Base San Antonio–Randolph', 'abbr' => 'JBSA-RND', 'icao_code' => 'KRND'],
            ['name' => 'Keesler Air Force Base', 'abbr' => 'KAFB', 'icao_code' => 'KBIX'],
            ['name' => 'Laughlin Air Force Base', 'abbr' => 'LAFB', 'icao_code' => 'KDLF'],
            ['name' => 'Little Rock Air Force Base', 'abbr' => 'LRAFB', 'icao_code' => 'KLRF'],
            ['name' => 'Los Angeles Air Force Base', 'abbr' => 'LAAFB', 'icao_code' => null],
            ['name' => 'Luke Air Force Base', 'abbr' => 'LUKE', 'icao_code' => 'KLUF'],
            ['name' => 'MacDill Air Force Base', 'abbr' => 'MAFB', 'icao_code' => 'KMCF'],
            ['name' => 'Maxwell Air Force Base', 'abbr' => 'MAFB-AL', 'icao_code' => 'KMXF'],
            ['name' => 'Minot Air Force Base', 'abbr' => 'MAFB-ND', 'icao_code' => 'KMIB'],
            ['name' => 'Moody Air Force Base', 'abbr' => 'MOAFB', 'icao_code' => 'KVAD'],
            ['name' => 'Mountain Home Air Force Base', 'abbr' => 'MHAFB', 'icao_code' => 'KMUO'],
            ['name' => 'Nellis Air Force Base', 'abbr' => 'NAFB', 'icao_code' => 'KLSV'],
            ['name' => 'Offutt Air Force Base', 'abbr' => 'OAFB', 'icao_code' => 'KOFF'],
            ['name' => 'Patrick Space Force Base', 'abbr' => 'PSFB', 'icao_code' => 'KCOF'],
            ['name' => 'Peterson Space Force Base', 'abbr' => 'PET-SFB', 'icao_code' => 'KPET'],
            ['name' => 'Pope Army Airfield', 'abbr' => 'PAAF', 'icao_code' => 'KPOB'],
            ['name' => 'Robins Air Force Base', 'abbr' => 'RAFB', 'icao_code' => 'KWRB'],
            ['name' => 'Schriever Space Force Base', 'abbr' => 'SSFB', 'icao_code' => null],
            ['name' => 'Scott Air Force Base', 'abbr' => 'SAFB', 'icao_code' => 'KBLV'],
            ['name' => 'Seymour Johnson Air Force Base', 'abbr' => 'SJAFB', 'icao_code' => 'KGSB'],
            ['name' => 'Shaw Air Force Base', 'abbr' => 'SAFB-SC', 'icao_code' => 'KSSC'],
            ['name' => 'Sheppard Air Force Base', 'abbr' => 'SHAFB', 'icao_code' => 'KSPS'],
            ['name' => 'Tinker Air Force Base', 'abbr' => 'TAFB', 'icao_code' => 'KTIK'],
            ['name' => 'Travis Air Force Base', 'abbr' => 'TRAFB', 'icao_code' => 'KSUU'],
            ['name' => 'Vance Air Force Base', 'abbr' => 'VAFB', 'icao_code' => 'KEND'],
            ['name' => 'Vandenberg Space Force Base', 'abbr' => 'VSFB', 'icao_code' => 'KVBG'],
            ['name' => 'Whiteman Air Force Base', 'abbr' => 'WAFB', 'icao_code' => 'KSZL'],
            ['name' => 'Wright-Patterson Air Force Base', 'abbr' => 'WPAFB', 'icao_code' => 'KFFO'],
        ];

        foreach ($bases as $base) {
            Base::create([
                'name' => $base['name'],
                'abbr' => $base['abbr'],
                'branch_id' => $usaf->id,
                'icao_code' => $base['icao_code'],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bases');
    }
};
