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
        Schema::create('kategori', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('deskripsi',100)->nullable();
            $table->enum('kategori',['M','A','BHP','BTHP'])->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};