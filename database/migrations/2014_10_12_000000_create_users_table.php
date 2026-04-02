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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('email')->unique();
            $table->string('role_id',);
            $table->enum('Gender',['Pria','Wanita']);
            $table->date('tanggal_lahir');
            $table->string('telepon');
            $table->string('password');
            $table->string('is_active')->default(true);
            $table->string('jabatan_id')->nullable();
            $table->string('divisi_id')->nullable();
            $table->string('nik')->nullable();
            $table->string('nip')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('created_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
