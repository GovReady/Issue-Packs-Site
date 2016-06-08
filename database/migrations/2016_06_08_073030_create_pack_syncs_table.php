<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackSyncsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pack_syncs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('pack_id')->unsigned();
            $table->foreign('pack_id')->references('id')->on('issue_packs')->onDelete('cascade');

            $table->string('application');
            $table->string('repo');
            $table->string('url');
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
        Schema::drop('pack_syncs');
    }
}
