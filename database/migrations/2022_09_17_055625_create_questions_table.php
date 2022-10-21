<?php

use App\Constants\DBConstants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(DBConstants::TABLE_QUESTIONS, function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->text('answer');
            $table->tinyInteger('language_id');
            $table->timestamp('keywords_extracted_at')->nullable();
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
        Schema::dropIfExists('questions');
    }
};
