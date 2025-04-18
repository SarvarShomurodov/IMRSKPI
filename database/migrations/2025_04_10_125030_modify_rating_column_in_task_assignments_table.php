<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('task_assignments', function (Blueprint $table) {
        $table->decimal('rating', 5, 2)->change(); // yoki float
    });
}

public function down()
{
    Schema::table('task_assignments', function (Blueprint $table) {
        $table->integer('rating')->change(); // Agar original tipi integer bo'lsa
    });
}
};
