<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumn2LengthInCsvDataTable extends Migration
{
    public function up()
    {
        Schema::table('csv_data', function (Blueprint $table) {
            $table->text('column2')->change(); // เปลี่ยนจาก string เป็น text
        });
    }

    public function down()
    {
        Schema::table('csv_data', function (Blueprint $table) {
            $table->string('column2', 255)->change(); // กลับเป็น string ความยาว 255
        });
    }
}
