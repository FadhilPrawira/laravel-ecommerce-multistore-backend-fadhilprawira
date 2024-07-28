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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('password');
            $table->string('address')->nullable()->after('phone');
            $table->string('country')->nullable()->after('address');
            // province
            $table->string('province')->nullable()->after('country');
            // city
            $table->string('city')->nullable()->after('province');
            // district
            $table->string('district')->nullable()->after('city');
            // postal code
            $table->string('postal_code')->nullable()->after('district');
            // role
            $table->enum(
                'role',
                ['customer', 'seller']
            )->default('customer')->after('postal_code');
            // photo
            $table->string('photo')->nullable()->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('address');
            $table->dropColumn('country');
            $table->dropColumn('province');
            $table->dropColumn('city');
            $table->dropColumn('district');
            $table->dropColumn('postal_code');
            $table->dropColumn('role');
            $table->dropColumn('photo');
        });
    }
};
