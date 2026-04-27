<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /*
    |--------------------------------------------------------------------------
    | Fillable / Hidden / Casts
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'password',
        'department',
        'designation',
        'manager_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',   // Auto-hashes on set (Laravel 10+)
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Who is this user's manager?
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Direct reports only (1 level)
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    /**
     * Full recursive hierarchy — eager loads all levels.
     * ⚠️ Use only when you need the full tree (e.g. org chart).
     * For large orgs, use subordinateIds() scope instead.
     */
    public function subordinatesRecursive(): HasMany
    {
        return $this->subordinates()->with('subordinatesRecursive');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes — for filtered queries in controller/index
    |--------------------------------------------------------------------------
    */

    /**
     * Filter by role name.
     * Usage: User::ofRole('admin')->get()
     */
    public function scopeOfRole(Builder $query, string $role): Builder
    {
        return $query->whereHas('roles', fn($q) => $q->where('name', $role));
    }

    /**
     * Filter by designation.
     * Usage: User::ofDesignation('manager')->get()
     */
    public function scopeOfDesignation(Builder $query, string $designation): Builder
    {
        return $query->where('designation', $designation);
    }

    /**
     * Filter by manager.
     * Usage: User::underManager(3)->get()
     */
    public function scopeUnderManager(Builder $query, int $managerId): Builder
    {
        return $query->where('manager_id', $managerId);
    }

    /**
     * Top-level users only (no manager).
     * Usage: User::topLevel()->get()
     */
    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('manager_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Collect all subordinate IDs recursively — without loading full models.
     * Much more efficient than subordinatesRecursive() for large orgs.
     * Use this for permission checks, bulk queries, etc.
     *
     * Usage: $user->subordinateIds()  → [4, 7, 12, 15, ...]
     */
    public function subordinateIds(): array
    {
        $ids = [];
        $stack = $this->subordinates()->pluck('id')->toArray();

        while (!empty($stack)) {
            $ids = array_merge($ids, $stack);
            $stack = User::whereIn('manager_id', $stack)->pluck('id')->toArray();
        }

        return array_unique($ids);
    }

    /**
     * Get avatar initials from name.
     * Usage: $user->initials  → "GS"
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper($word[0] ?? '');
        }
        return $initials;
    }

    /**
     * Check if this user manages anyone.
     */
    public function isManager(): bool
    {
        return $this->subordinates()->exists();
    }

    /**
     * Check if this user has a manager above them.
     */
    public function hasManager(): bool
    {
        return !is_null($this->manager_id);
    }
}
