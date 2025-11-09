<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    Schema::table('servis', function (Blueprint $table) {
        $table->string('no_hp')->nullable()->change();
        $table->string('tipe_barang')->nullable();
        $table->string('seri_barang')->nullable();
    });
}
    public function down()
    {
        Schema::table('servis', function (Blueprint $table) {
            $table->string('no_hp')->change();
            $table->dropColumn('tipe_barang');
            $table->dropColumn('seri_barang');
        });
    }
};