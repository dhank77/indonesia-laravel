<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (config('indonesia.data_location.city')) {

            $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kabupaten' : 'cities');
            $code = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
            $name = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';

            if (config('indonesia.data_location.province')) {
                $provinceTable = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'provinsi' : 'provinces');
                $provinceCode = config('indonesia.pattern') === 'ID' ? 'kode_provinsi' : 'province_code';
            } else {
                $provinceTable = null;
                $provinceCode = null;
            }

            Schema::create($tableName, function (Blueprint $table) use ($code, $name, $provinceCode, $provinceTable) {
                $table->bigIncrements('id');
                $table->char($code, 4)->unique();
                if (config('indonesia.data_location.province')) {
                    $table->char($provinceCode, 2);
                }
                $table->string($name, 255);
                $table->text('meta')->nullable();
                $table->timestamps();

                if (config('indonesia.data_location.province')) {
                    $table->foreign($provinceCode)
                        ->references($code)
                        ->on($provinceTable)
                        ->onUpdate('cascade')->onDelete('restrict');
                }
            });
        }
    }

    public function down()
    {
        if (config('indonesia.data_location.city')) {
            $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kabupaten' : 'cities');
            Schema::drop($tableName);
        }
    }
};
