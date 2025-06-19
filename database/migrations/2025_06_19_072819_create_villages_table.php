<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kelurahan' : 'villages');

        $code = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
        $name = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';

        $districtTable = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kecamatan' : 'districts');
        $districtCode = config('indonesia.pattern') === 'ID' ? 'kode_kecamatan' : 'district_code';

        Schema::create($tableName, function (Blueprint $table) use ($code, $name, $districtCode, $districtTable) {
            $table->bigIncrements('id');
            $table->char($code, 10)->unique();
            $table->char($districtCode, 7);
            $table->string($name, 255);
            $table->text('meta')->nullable();
            $table->timestamps();

            $table->foreign($districtCode)
                ->references($code)
                ->on($districtTable)
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down()
    {
        $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kelurahan' : 'villages');
        Schema::drop($tableName);
    }
};
