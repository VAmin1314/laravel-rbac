<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRbacTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create(config('rbac.table.roles'), function (Blueprint $table) {

            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50);
            $table->timestamps();
        });

        Schema::create(config('rbac.table.permissions'), function (Blueprint $table) {

            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50);
            $table->string('http_method')->nullable();
            $table->text('http_path')->nullable();
            $table->timestamps();

        });

        Schema::create(config('rbac.table.role_users'), function (Blueprint $table){

            $table->integer('role_id', false, true);
            $table->integer('user_id', false, true);
            $table->unique(['role_id', 'user_id']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on(config('rbac.table.users'))->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on(config('rbac.table.roles'))->onDelete('cascade');
        });

        Schema::create(config('rbac.table.role_permissions'), function (Blueprint $table) {

            $table->integer('role_id', false, true);
            $table->integer('permission_id', false, true);
            $table->unique(['role_id', 'permission_id']);
            $table->timestamps();

            $table->foreign('permission_id')->references('id')->on(config('rbac.table.permissions'))->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on(config('rbac.table.roles'))->onDelete('cascade');


        });

        Schema::create(config('rbac.table.user_permissions'), function (Blueprint $table){

            $table->integer('user_id', false, true);
            $table->integer('permission_id', false, true);
            $table->unique(['user_id', 'permission_id']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on(config('rbac.table.users'))->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on(config('rbac.table.permissions'))->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('rbac.table.user_permissions'));
        Schema::dropIfExists(config('rbac.table.role_permissions'));
        Schema::dropIfExists(config('rbac.table.role_users'));
        Schema::dropIfExists(config('rbac.table.permissions'));
        Schema::dropIfExists(config('rbac.table.roles'));
    }
}
