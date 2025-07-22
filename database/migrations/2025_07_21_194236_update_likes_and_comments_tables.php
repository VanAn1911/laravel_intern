<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Cập nhật bảng likes
        Schema::table('likes', function (Blueprint $table) {
            $table->boolean('is_like')->default(true)->after('user_id'); // true = like, false = dislike
        });

        // Cập nhật bảng comments
        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade')->after('post_id');
        });
    }

    public function down(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->dropColumn('is_like');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
