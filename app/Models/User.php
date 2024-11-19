<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class User
 * @package App
 * @category model
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'address',
        'photo',
        'company_id',
        'locale',
        'date_of_birth',
        'gender',
        'doctor_id',
        'blood_group',
        'status',
        'previous_drug',
        'text_field',
        'custom_field',
        'total_payment',
        'paid_payment',
        'due_payment',
        'attachments',
        'categorey',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'attachments' => 'array',
    ];

    /**
     * Has many relation with complains
     *
     * @return mixed
     */
    public function companies()
    {
        return $this->morphToMany(Company::class, 'user', 'user_companies', 'user_id', 'company_id');
    }

    public function doctorSchedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function patientAppointments()
    {
        return $this->hasMany(PatientAppointment::class);
    }

    public function doctorAppointments()
    {
        return $this->hasMany(PatientAppointment::class, 'doctor_id');
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo)
            return asset($this->photo);
        else
            return asset('assets/images/placeholder.jpg');
    }

    public function patientCaseStudy()
    {
        return $this->hasOne(PatientCaseStudy::class);
    }

    public function labReports()
    {
        return $this->hasMany(LabReport::class, 'patient_id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
