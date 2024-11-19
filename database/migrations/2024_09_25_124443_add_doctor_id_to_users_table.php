<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoctorIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the doctor_id column, make it nullable
            $table->unsignedBigInteger('doctor_id')->nullable()->index(); // Add index for faster lookups

            // Optional: Add a foreign key constraint
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('set null'); // Reference the id in users table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['doctor_id']);

            // Drop the doctor_id column
            $table->dropColumn('doctor_id');
        });
    }
}
