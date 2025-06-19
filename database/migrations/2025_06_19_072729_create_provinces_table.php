<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'provinsi' : 'provinces');
        $code = config('indonesia.pattern') === 'ID' ? 'kode' : 'code';
        $name = config('indonesia.pattern') === 'ID' ? 'nama' : 'name';

        Schema::create($tableName, function (Blueprint $table) use ($code, $name) {
            $table->bigIncrements('id');
            $table->char($code, 2)->unique();
            $table->string($name, 255);
            $table->text('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        $tableName = config('indonesia.table_prefix') . (config('indonesia.pattern') === 'ID' ? 'provinsi' : 'provinces');
        Schema::drop($tableName);
    }
};
