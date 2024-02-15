97% of storage used … If you run out, you won't have enough storage to create, edit, and upload files. Get 100 GB of storage for Rp 26.900,00 Rp 7.000,00/month for 3 months.
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangmasuk', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_masuk');
            $table->smallInteger('qty_masuk')->default(1);
            $table->foreignId('barang_id');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangmasuk');
    }
};