<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('addresses', function(Blueprint $table){
            if(!Schema::hasColumn('addresses', 'name')){
                $table->string('name', 100)->after('id');
            }
            if(!Schema::hasColumn('addresses', 'phone')){
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
        Schema::table('addresses', function(Blueprint $table){
            if(Schema::hasColumn('addresses', 'name')){
                $table->dropColumn('name');
            }
            if(Schema::hasColumn('addresses', 'phone')){
                $table->dropColumn('phone');
            }
        });
    }
}
