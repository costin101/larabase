<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Cacheable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name', 'last_name', 'username', 'email', 'password', 'phone_number',
        'about', 'gender', 'birthday', 'country_id', 'lat', 'lng',
        'last_location_update', 'share_my_location', 'website', 'facebook',
        'google', 'x', 'linkedin', 'youtube', 'instagram', 'discord',
        'language', 'timezone', 'weather_unit', 'notifications_sound', 'sfw',
        'follow_privacy', 'friend_privacy', 'post_privacy', 'message_privacy',
        'confirm_followers', 'show_activities_privacy', 'birth_privacy', 'visit_privacy',
        'emailNotification', 'e_liked', 'e_reacted', 'e_shared', 'e_followed',
        'e_commented', 'e_visited', 'e_liked_page', 'e_mentioned', 'e_joined_group',
        'e_accepted', 'e_sentme_msg', 'e_last_notif', 'email_code', 'sms_code',
        'code_sent', 'time_code_sent', 'verified', 'two_factor', 'two_factor_hash',
        'two_factor_verified', 'new_email', 'new_phone', 'status', 'active',
        'banned', 'banned_reason', 'is_pro', 'pro_time', 'pro_type', 'pro_remainder',
        'points', 'daily_points', 'converted_points', 'point_day_expire',
        'balance', 'paypal_email', 'paystack_ref', 'wallet', 'social_login',
        'referrer', 'ref_user_id', 'ref_level', 'relationship_id', 'last_follow_id',
        'registered', 'lastseen', 'showlastseen', 'last_login_data',
        'last_data_update', 'last_email_sent', 'joined',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_hash',
        'email_code',
        'sms_code',
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
            'birthday' => 'date',
            'last_location_update' => 'datetime',
            'share_my_location' => 'boolean',
            'notifications_sound' => 'boolean',
            'sfw' => 'boolean',
            'follow_privacy' => 'boolean',
            'confirm_followers' => 'boolean',
            'show_activities_privacy' => 'boolean',
            'visit_privacy' => 'boolean',
            'emailNotification' => 'boolean',
            'e_liked' => 'boolean',
            'e_reacted' => 'boolean',
            'e_shared' => 'boolean',
            'e_followed' => 'boolean',
            'e_commented' => 'boolean',
            'e_visited' => 'boolean',
            'e_liked_page' => 'boolean',
            'e_mentioned' => 'boolean',
            'e_joined_group' => 'boolean',
            'e_accepted' => 'boolean',
            'e_sentme_msg' => 'boolean',
            'e_last_notif' => 'datetime',
            'time_code_sent' => 'datetime',
            'verified' => 'boolean',
            'two_factor' => 'boolean',
            'two_factor_verified' => 'boolean',
            'active' => 'boolean',
            'banned' => 'boolean',
            'is_pro' => 'boolean',
            'pro_time' => 'datetime',
            'point_day_expire' => 'datetime',
            'registered' => 'datetime',
            'lastseen' => 'datetime',
            'showlastseen' => 'boolean',
            'last_login_data' => 'datetime',
            'last_data_update' => 'datetime',
            'last_email_sent' => 'datetime',
            'joined' => 'datetime',
            'balance' => 'decimal:2',
            'wallet' => 'decimal:2',
            'lat' => 'decimal:8',
            'lng' => 'decimal:8',
        ];
    }

    /**
     * Relationships
     */

    /**
     * Summary of referrerUser
     * @return BelongsTo<User, User>
     */
    public function referrerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ref_user_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}
