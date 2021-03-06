<?php

namespace BookStack;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'name', 'profilePic' ,'title' , 'type' ,
    ];


    public function coursesEnrolled()
    {
        return $this->belongsToMany(Courses::class , 'courses_student' ,'student_id', 'course_id')->withPivot('commulativeGrade');
    }

    public function coursesCreated()
    {
        return $this->hasMany('BookStack\Courses','lec_id');
    }

    public function comments()
    {
        return $this->hasMany('BookStack\Comment','user_id');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    /*protected $hidden = [
        'password', 'remember_token',
    ];*/

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    /*protected $casts = [
        'email_verified_at' => 'datetime',
    ];*/
}
