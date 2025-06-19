<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kecamatan' : 'districts');
        $code = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
        $name = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';

        $cityTable = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kabupaten' : 'cities');
        $cityCode = config('indonesia.pattern') === 'ID' ? 'kode_kabupaten' : 'city_code';

        Schema::create($tableName, function (Blueprint $table) use ($code, $name, $cityCode, $cityTable) {
            $table->bigIncrements('id');
            $table->char($code, 7)->unique();
            $table->char($cityCode, 4);
            $table->string($name, 255);
            $table->text('meta')->nullable();
            $table->timestamps();

            $table->foreign($cityCode)
                ->references($code)
                ->on($cityTable)
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down()
    {
        $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kecamatan' : 'districts');
        Schema::drop($tableName);
    }
};
