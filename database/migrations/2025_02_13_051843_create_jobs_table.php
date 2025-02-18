<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // database/migrations/xxxx_xx_xx_create_jobs_table.php

public function up()
{
    Schema::create('jobs', function (Blueprint $table) {
        $table->increments('id')->primary();
        $table->string('jobId', 50)->unique();
        $table->string('jobTitle');
        $table->string('jobLevel');
        $table->string('companyName');
        $table->string('companyLogo')->nullable();
        $table->string('jobLocation');
        $table->string('jobType');
        $table->string('salaryRange')->nullable();
        $table->integer('vacancies');
        $table->date('jobDate');
        $table->unsignedInteger('user_id'); // Foreign key referencing 'id' in the users table
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Adding the foreign key constraint
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};
