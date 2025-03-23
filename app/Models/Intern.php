<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Mentor;
use App\Models\CertInterns;
use App\Models\Task;


class Intern extends Authenticatable
{
    use Notifiable;

    protected $table = 'interns';
    protected $primaryKey = 'intern_id';
    protected $guarded = ['intern_id'];

    protected $fillable = [
        'intern_name', 'birthdate', 'gender', 'email', 'phone', 'university',
        'major', 'address', 'citizen_id', 'citizen_id_image', 'degree',
        'degree_image', 'username', 'password', 'department', 'position', 'mentor_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationship với mentor
    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id');
    }

    // Relationship với chứng chỉ
    public function cert_interns()
    {
        return $this->hasMany(CertInterns::class, 'intern_id');
    }

    // Relationship với công việc được giao
    public function tasks()
    {
        return $this->hasMany(Task::class, 'intern_id');
    }
}