<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'jobId', 'jobTitle', 'jobLevel', 'companyName', 'companyLogo', 'jobLocation', 'jobType', 'salaryRange', 'vacancies','jobDate', 'user_id'
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
