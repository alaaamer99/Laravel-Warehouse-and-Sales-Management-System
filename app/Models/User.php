<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'role',
        'phone',
        'address',
        'is_active',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is sales representative
     */
    public function isSalesRepresentative(): bool
    {
        return $this->role === 'sales_representative';
    }

    /**
     * Get the sales representative profile
     */
    public function salesRepresentative()
    {
        return $this->hasOne(SalesRepresentative::class);
    }

    /**
     * Get purchase invoices created by this user
     */
    public function purchaseInvoices()
    {
        return $this->hasMany(PurchaseInvoice::class);
    }

    /**
     * Get sales invoices created by this user
     */
    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class);
    }

    /**
     * Get payments recorded by this user
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get representative withdrawals created by this user
     */
    public function representativeWithdrawals()
    {
        return $this->hasMany(RepresentativeWithdrawal::class);
    }
}
