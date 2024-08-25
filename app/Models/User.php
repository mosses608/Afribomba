<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false){
            $query->where('staff_id', 'like' , '%' . request('search') . '%')
            ->orwhere('staff_name' , 'like' , '%' . request('search') . '%');
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_id',
        'staff_name',
        'staff_role',
        'staff_email',
        'staff_phone',
        'username',
        'password',
        'profile',
        'password_confirm',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function find($id){
        $users = self::all();

        foreach($users as $user){
            if($user['id'] == $id){
                return $user;
            }
        }
    }
}
