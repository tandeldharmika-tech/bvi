<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Attendance;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles;
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
     protected $fillable = [
        'name',
        'email',
        'password',
        'personal',
        'contact',
        'official',
        'payroll',
        'education',
        'experience',
        'salary_history',
        'documents',
        'emergency_contacts',
        'notes',
        'audit',
        'parent_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            // Add JSON casting
            'personal' => 'array',
            'contact' => 'array',
            'official' => 'array',
            'payroll' => 'array',
            'education' => 'array',
            'experience' => 'array',
            'salary_history' => 'array',
            'documents' => 'array',
            'emergency_contacts' => 'array',
            'notes' => 'array',
            'audit' => 'array',
        ];
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function tasks()
{
    return $this->hasMany(Task::class, 'assigned_to', 'id'); // use 'assigned_to' as FK
}

public function manager()
{
    return $this->belongsTo(User::class, 'parent_id');
}

public function teamMembers()
{
    return $this->hasMany(User::class, 'parent_id');
}

public function visibleUsers()
    {
        // Admin can see all users
        if ($this->hasRole('Admin')) {
            return self::query();
        }

        // Get current user's role IDs
        $roleIds = $this->roles->pluck('id')->toArray();

        // Get all child roles recursively
        $childRoleIds = $this->getAllChildRoles($roleIds);

        // Fetch users based on manager or role hierarchy
        return self::where(function ($query) use ($childRoleIds) {
            $query->where('parent_id', $this->id) // direct reports
                  ->orWhereHas('roles', function ($r) use ($childRoleIds) {
                      $r->whereIn('role_id', $childRoleIds); // indirect reports
                  });
        });
    }

    /**
     * Recursive helper to get all child roles
     */
    public function getAllChildRoles($roleIds)
    {
        $children = \Spatie\Permission\Models\Role::whereIn('parent_id', $roleIds)->pluck('id')->toArray();
        if (empty($children)) return [];
        return array_merge($children, $this->getAllChildRoles($children));
    }

}
