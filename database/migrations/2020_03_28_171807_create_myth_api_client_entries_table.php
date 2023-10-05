<?php
/**
 * Copyright MyTh
 * Website: https://4MyTh.com
 * Email: mythpe@gmail.com
 * Copyright © 2006-2020 MyTh All rights reserved.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class MythApiClient
 */
class CreateMythApiClientEntriesTable extends Migration
{
    protected $table = 'myth_api_client_entries';

    /**
     * create_myth_api_client_entries_table
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        !Schema::hasTable($this->table) && Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('syncable');
            $table->unsignedBigInteger("myth_api_manager_id");
            $table->string("manager_name");
            $table->boolean('must_sync')->default(true);
            $table->timestamp('las_sync_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
