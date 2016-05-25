<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuePackIssuesPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue_issue_pack', function (Blueprint $table) {
          $table->integer('issue_id')->unsigned()->index();
          $table->foreign('issue_id')->references('id')->on('issues')->onDelete('cascade');

          $table->integer('issue_pack_id')->unsigned()->index();
          $table->foreign('issue_pack_id')->references('id')->on('issue_packs')->onDelete('cascade');

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
        Schema::drop('issue_issue_pack');
    }
}
