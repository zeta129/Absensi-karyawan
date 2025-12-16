<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('attendances')) {
            Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->index();
            $table->timestamp('timestamp');
            $table->string('method');
            $table->float('confidence')->nullable();
            $table->string('device_id')->nullable();
            $table->boolean('anomaly_flag')->default(false);
            $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
