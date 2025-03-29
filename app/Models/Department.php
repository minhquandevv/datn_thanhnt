<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    use HasFactory;

    protected $primaryKey = 'department_id';

    protected $fillable = ['name'];
    
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function jobOffers()
    {
        return $this->hasMany(JobOffer::class, 'department_id', 'department_id');
    }

    public function interns()
    {
        return $this->hasMany(Intern::class, 'department_id', 'department_id');
    }

    public function mentors()
    {
        return $this->hasMany(Mentor::class, 'department_id', 'department_id');
    }
}
