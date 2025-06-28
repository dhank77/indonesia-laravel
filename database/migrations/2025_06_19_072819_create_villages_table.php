<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (config('indonesia.data_location.village')) {
            $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kelurahan' : 'villages');
            $code = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
            $name = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';

            if (config('indonesia.data_location.district')) {
                $districtTable = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kecamatan' : 'districts');
                $districtCode = config('indonesia.pattern') === 'ID' ? 'kode_kecamatan' : 'district_code';
            } else {
                $districtTable = null;
                $districtCode = null;
            }

            Schema::create($tableName, function (Blueprint $table) use ($code, $name, $districtCode, $districtTable) {
                $table->bigIncrements('id');
                $table->char($code, 10)->unique();
                if (config('indonesia.data_location.district')) {
                    $table->char($districtCode, 7);
                }
                $table->string($name, 255);
                $table->text('meta')->nullable();
                $table->timestamps();

                if (config('indonesia.data_location.district')) {
                    $table->foreign($districtCode)
                        ->references($code)
                        ->on($districtTable)
                        ->onUpdate('cascade')->onDelete('restrict');
                }
            });
        }
    }

    public function down()
    {
        if (config('indonesia.data_location.village')) {
            $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'kelurahan' : 'villages');
            Schema::drop($tableName);
        }
    }
};
