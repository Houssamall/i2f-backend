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
        Schema::create('financiers', function (Blueprint $table) {
            $table->id();
            $table->string('designation')->default('0');
            $table->string('exN')->default('0');
            $table->string('exN1')->default('0');
            $table->string('var')->default('0');
            $table->foreignId('id_User')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('fiscals', function (Blueprint $table) {
            $table->id();
            $table->string('mois')->default('0');
            $table->string('tvaN')->default('0');
            $table->string('tvaN1')->default('0');
            $table->string('tvaVAR')->default('0');
            $table->string('irN')->default('0');
            $table->string('irN1')->default('0');
            $table->string('irVAR')->default('0');
            $table->string('isN')->default('0');
            $table->string('isN1')->default('0');
            $table->string('isVAR')->default('0');
            $table->foreignId('id_User')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('socials', function (Blueprint $table) {
            $table->id();
            $table->string('mois')->default('0');
            $table->string('masseN')->default('0');
            $table->string('masseN1')->default('0');
            $table->string('masseVAR')->default('0');
            $table->string('cnssN')->default('0');
            $table->string('cnssN1')->default('0');
            $table->string('cnssVAR')->default('0');
            $table->foreignId('id_User')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socials');
        Schema::dropIfExists('fiscals');
        Schema::dropIfExists('financiers');
    }
};
