<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressColumnOrderTable extends Migration
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
            if(Schema::hasColumn('orders', 'address_id')){
                $table->dropColumn('address_id');
            }
            if(!Schema::hasColumn('orders', 'address')){
                $table->text('address')->after('user_id');
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
            if(!Schema::hasColumn('orders', 'address_id')){
                $table->unsignedInteger('address_id');
            }
            if(Schema::hasColumn('orders', 'address')){
                $table->dropColumn('address');
            }
        });
    }
}
