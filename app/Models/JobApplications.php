<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplications extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'job_applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'gender',
        'contact',
        'pref_loc',
        'expected_ctc',
        'current_ctc',
        'notice',
    ];

    public $timestamps = true;

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function workExp() {
        return $this->hasMany('App\Models\WorkExp', 'job_application');
    }

    public function eduDetail() {
        return $this->hasMany('App\Models\EduDetail', 'job_application');
    }

    public function keySkills() {
        return $this->hasMany('App\Models\KeySkills', 'job_application');
    }

    public function knownLang() {
        return $this->hasMany('App\Models\KnownLang', 'job_application');
    }
}
