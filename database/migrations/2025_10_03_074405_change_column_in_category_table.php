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
         Schema::table('category', function (Blueprint $table) {
            
            // 1. Drop the old, incorrect unique index on the 'name' column.
            // Laravel's naming convention for a unique index on 'name' is usually 
            // '[table]_[column]_unique', so we use 'category_name_unique'.
            $table->dropUnique('category_name_unique');

            // 2. Add the new composite unique key.
            // This ensures that the combination of (user_id, name) is unique.
            // e.g., User 1 can have 'Work', and User 2 can also have 'Work'.
            $table->unique(['user_id', 'name'], 'user_category_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category', function (Blueprint $table) {
            // 1. Remove the composite unique key
            $table->dropUnique('user_category_name_unique');

            // 2. Re-add the single unique key if you needed to revert completely
            // (You usually don't need this step unless the app depended on the old, incorrect constraint)
            // $table->unique('name', 'category_name_unique');
        });
    }
};
