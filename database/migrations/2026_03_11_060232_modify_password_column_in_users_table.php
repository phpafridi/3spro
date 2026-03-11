<?php
// database/migrations/xxxx_xx_xx_xxxxxx_modify_password_column_in_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Change password column to VARCHAR(255) to accommodate bcrypt hashes
            $table->string('password', 255)->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert back if needed (adjust based on your original length)
            $table->string('password', 60)->change();
        });
    }
};
