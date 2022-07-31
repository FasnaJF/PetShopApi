<?php

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
        Schema::table('users', function (Blueprint $table) {
            $table->char('uuid', 36);
            $table->string('first_name');
            $table->string('last_name');
            $table->boolean('is_admin')->default(0);
            $table->char('avatar', 36)->nullable();
            $table->string('address');
            $table->string('phone_number');
            $table->boolean('is_marketing')->default(0);
            $table->timestamp('last_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uuid');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('is_admin');
            $table->dropColumn('avatar');
            $table->dropColumn('address');
            $table->dropColumn('phone_number');
            $table->dropColumn('is_marketing');
            $table->dropColumn('last_login_at');
        });
    }
};
