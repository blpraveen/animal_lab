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
            $table->unsignedBigInteger('role_id')->nullable()->after('password');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->string('designation',250)->after('role_id');
            $table->string('employee_code',250)->after('degination');
            $table->string('department',250)->after('employee_code');
            $table->string('extension_no',250)->nullable()->after('department');
            $table->string('mobile_no',20)->nullable()->after('extension_no');
            $table->string('user_name',250)->nullable()->after('mobile_no');
            $table->date('tenure_from')->nullable()->after('user_name');
            $table->date('tenure_to')->nullable()->after('tenure_from');
            $table->text('remarks')->after('tenure_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_id');
            $table->dropColumn('degination');
            $table->dropColumn('employee_code');
            $table->dropColumn('department');
            $table->dropColumn('extension_no');
            $table->dropColumn('mobile_no');
            $table->dropColumn('user_name');
            $table->dropColumn('tenure_from');
            $table->dropColumn('tenure_to');
            $table->dropColumn('remarks');
        });
    }
};
