<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\User;

class AddApiTokenToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("api_token", 60);
        });
        $users = User::where('api_token', '=', '')->orWhereNull('api_token')->get();
        foreach ($users as $user ) {
            $user->api_token = str_random(60);
            $user->save();
        }
        Schema::table('users', function (Blueprint $table) {
            $table->unique('api_token');
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("api_token");
        });
        //
    }
}
