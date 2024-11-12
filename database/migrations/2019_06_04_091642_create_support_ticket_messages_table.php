<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSupportTicketMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_ticket_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('support_ticket_id')->unsigned();
            $table->integer('messageable_id')->unsigned();
            $table->string('messageable_type');
            $table->longText('body');
            $table->tinyInteger('is_read')->comment('0: Un-Read, 1: Read')->default(0);
            $table->timestamps();
            $table->foreign('support_ticket_id')->references('id')->on('support_tickets');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_ticket_messages');
    }
}
