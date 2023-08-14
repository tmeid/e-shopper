<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameColumnPhoneColumnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('orders', function(Blueprint $table){
            if(!Schema::hasColumn('orders', 'name')){
                $table->string('name', 100)->after('user_id');
            }
            if(!Schema::hasColumn('orders', 'phone')){
                $table->string('phone')->after('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('orders', function(Blueprint $table){
            if(Schema::hasColumn('orders', 'name')){
                $table->dropColumn('name');
            }
            if(!Schema::hasColumn('orders', 'phone')){
                $table->dropColumn('phone');
            }
        });
    }
}
