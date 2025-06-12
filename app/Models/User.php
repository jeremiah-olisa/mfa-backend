<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    //     'role',
    // ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

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


    public function userApps()
    {
        return $this->hasMany(UserApp::class);
    }

    public function userAppsByApp(string $app)
    {
        return $this->userApps()->where('app', $app)->orderByDesc('plan_expires_at')->first();
    }

    public function addAppToUser($app)
    {
        if ($app && !$this->userApps()->where('app', $app)->exists()) {
            $this->userApps()->create(['app' => $app]);
        }
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
