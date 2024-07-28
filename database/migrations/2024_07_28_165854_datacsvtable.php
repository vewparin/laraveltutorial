<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class datacsvtable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_data', function (Blueprint $table) {
            $table->id();
            $table->string('column1')->nullable();  // เปลี่ยนชื่อคอลัมน์และประเภทข้อมูลตามต้องการ
            $table->string('column2')->nullable();
            $table->string('column3')->nullable();
            // เพิ่มคอลัมน์อื่น ๆ ตามโครงสร้างของไฟล์ CSV
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('csv_data');
    }
}
