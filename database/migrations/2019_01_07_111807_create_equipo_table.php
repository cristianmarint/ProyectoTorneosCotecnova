<?php
/*
 * @Author: CristianMarinT 
 * @Date: 2019-02-20 13:48:03 
 * @Last Modified by:   CristianMarinT 
 * @Last Modified time: 2019-02-20 13:48:03 
 */
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateEquipoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipo', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->string('nombre', 100)->nullable(false);
            $table->string('logo',150)->default('storage/storage/img/equipo/default.png')->nullable();
            $table->unsignedInteger('instituto_id')->nullable()->default(NULL);
            $table->unsignedInteger('color_id')->nullable()->default(NULL);
            $table->unsignedInteger('user_id')->nullable()->default(NULL);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('instituto_id')->references('id')->on('instituto')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('color_id')->references('id')->on('color')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipo');
    }
}