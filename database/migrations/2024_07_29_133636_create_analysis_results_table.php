<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalysisResultsTable extends Migration
{

    public function up()
    {
        Schema::create('analysis_results', function (Blueprint $table) {
            $table->id();
            $table->text('comment');
            $table->float('score');
            $table->string('polarity');
            // เพิ่มคอลัมน์สำหรับเวลาที่ใช้ในการประมวลผล
            $table->decimal('processing_time', 8, 4)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('analysis_results');
    }
}
