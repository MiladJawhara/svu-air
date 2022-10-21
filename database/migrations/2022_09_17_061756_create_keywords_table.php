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
        Schema::create(DBConstants::TABLE_KEYWORDS, function (Blueprint $table) {
            $table->id();
            $table->string("text")->unique();
            $table->unsignedBigInteger('frequency')->default(0);
            $table->tinyInteger('language_id');
            $table->timestamps();
        });

        Schema::create(DBConstants::TABLE_KEYWORDS_QUESTIONS, function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('keyword_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('frequency')->default(0);

            $table->timestamps();


            $table->foreign('keyword_id')
            ->references('id')
            ->on(DBConstants::TABLE_KEYWORDS)
            ->onDelete('cascade');

            $table->foreign('question_id')
            ->references('id')
            ->on(DBConstants::TABLE_QUESTIONS)
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(DBConstants::TABLE_KEYWORDS_QUESTIONS);
        Schema::dropIfExists(DBConstants::TABLE_KEYWORDS);
    }
};
