<?php

use App\Models\Player;
use App\Models\PlayerList;
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
        Schema::create('player_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PlayerList::class)->constrained();
            $table->foreignIdFor(Player::class)->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_list_items');
    }
};
