<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Intern;


class CertInterns extends Model
{
    //
    use HasFactory;

    protected $table = 'cert_interns';
    protected $primaryKey = 'cert_id';
    protected $guarded = ['cert_id'];

    protected $fillable = [
        'intern_id', 'cert_name', 'score', 'cert_image'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    // Relationship với TTS
    public function intern()
    {
        return $this->belongsTo(Intern::class, 'intern_id');
    }
}
