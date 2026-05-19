<?php

declare(strict_types=1);

namespace App\Domain\Users\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Support\Traits\HasGlobalSearch;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

#[Appends([
    'avatar_url',
])]
#[Fillable([
    'type',
    'name',
    'email',
    'password',
    'google2fa_secret',
    'active',
    'timezone',
    'last_login_at',
    'last_login_ip',
    'to_be_logged_out',
    'provider',
    'provider_id',
])]
#[Hidden([
    'password',
    'remember_token',
    'google2fa_secret',
])]
class User extends Authenticatable implements HasMedia
{
    use HasFactory;
    use HasGlobalSearch;
    use HasRoles;
    use HasUuids;
    use Impersonate;
    use InteractsWithMedia;
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'type', 'active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public const TYPE_ADMIN = 'admin';

    public const TYPE_USER = 'user';

    /**
     * The attributes that can be searched globally.
     *
     * @var array<string>
     */
    protected $searchable = ['name', 'email'];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'last_login_at' => 'datetime',
            'to_be_logged_out' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'google2fa_secret' => 'encrypted',
            'password_changed_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->type === self::TYPE_ADMIN || $this->hasRole('admin');
    }

    public function hasAdminAccess(): bool
    {
        return $this->can('access_admin_panel');
    }

    public function canImpersonate(): bool
    {
        return $this->hasAdminAccess();
    }

    public function canBeImpersonated(): bool
    {
        // Typically you can't impersonate yourself (handled by package)
        // and in many systems, you can't impersonate another Admin for security
        // But for debugging, we might want to allow it.
        // Let's allow impersonating anyone who is not the current authenticated user.
        return true;
    }

    public function isUser(): bool
    {
        return $this->type === self::TYPE_USER;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_picture')
            ->singleFile()
            ->useFallbackUrl('https://ui-avatars.com/api/?name=' . urlencode($this->name ?? ''));
    }

    protected function getAvatarUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('profile_picture') ?: 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? '');
    }
}
