
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('caption', 255);
            $table->string('path', 500)->unique();
            $table->string('original_name', 255);
            $table->unsignedBigInteger('size');
            $table->timestamps();
        });

        DB::statement("
            ALTER TABLE photos
            ADD CONSTRAINT photos_valid_values
            CHECK (
                length(trim(caption)) > 0
                AND length(trim(path)) > 0
                AND length(trim(original_name)) > 0
                AND size > 0
                AND size <= 2097152
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
