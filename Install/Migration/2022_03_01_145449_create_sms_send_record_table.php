<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateSmsSendRecordTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sms_send_record', function (Blueprint $table) {
            $table->bigIncrements('record_id');
            $table->string('sms_platform_id', 32)
                ->nullable(false)
                ->default('')
                ->comment('短信平台');
            $table->string('phone', 32)
                ->nullable(false)
                ->default('')
                ->comment('发送手机号');
            $table->string('template', 128)
                ->nullable(false)
                ->default('')
                ->comment('模板方式发送的需要传');
            $table->string('content', 512)
                ->nullable(false)
                ->default('')
                ->comment('消息内容');
            $table->string('param_data', 1024)
                ->nullable(false)
                ->default('')
                ->comment('参数内容，json格式');
            $table->string('result_status', 32)
                ->nullable(false)
                ->default('')
                ->comment('执行状态');
            $table->string('result_info', 1024)
                ->nullable(false)
                ->default('')
                ->comment('执行结果');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_send_record');
    }
}
