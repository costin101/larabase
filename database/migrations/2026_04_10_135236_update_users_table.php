<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique();
            $table->string('phone_number');
            
            // Personal Info
            $table->string('about')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->date('birthday');
            // FIXED: corrected 'refferences' to 'references'
            $table->foreignId('country_id')->constrained('countries')->onDelete('set null');
            
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->timestamp('last_location_update')->nullable();
            $table->boolean('share_my_location')->default(false);
            
            // Social Links
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('google')->nullable();
            $table->string('x')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->string('discord')->nullable();
            
            // Language & Preferences
            $table->string('language')->default('en');
            $table->integer('timezone')->nullable();
            $table->enum('weather_unit', ['celsius', 'fahrenheit'])->default('celsius');
            $table->boolean('notifications_sound')->default(true);
            $table->boolean('sfw')->default(true);
            
            // Privacy Settings
            $table->boolean('follow_privacy')->default(true);
            $table->enum('friend_privacy',['public', 'followers', 'disabled'])->default('public');
            $table->enum('post_privacy',['public', 'followers', 'private'])->default('public');
            $table->enum('message_privacy', ['public', 'private', 'disabled'])->default('public');
            $table->boolean('confirm_followers')->default(false);
            $table->boolean('show_activities_privacy')->default(true);
            $table->enum('birth_privacy', ['public', 'followers' ,'private'])->default('private');
            $table->enum('birth_year_privacy', ['public', 'followers' ,'private'])->default('private');
            $table->boolean('visit_privacy')->default(true);
            
            // Notification Preferences
            $table->boolean('emailNotification')->default(false);
            $table->boolean('e_liked')->default(true);
            $table->boolean('e_reacted')->default(true);
            $table->boolean('e_shared')->default(true);
            $table->boolean('e_followed')->default(true);
            $table->boolean('e_commented')->default(true);
            $table->boolean('e_visited')->default(true);
            $table->boolean('e_liked_page')->default(true);
            $table->boolean('e_mentioned')->default(true);
            $table->boolean('e_joined_group')->default(true);
            $table->boolean('e_accepted')->default(true);
            $table->boolean('e_sentme_msg')->default(true);
            $table->timestamp('e_last_notif')->nullable();
            
            // Authentication & Verification
            $table->string('email_code')->nullable();
            $table->string('sms_code')->nullable();
            $table->string('code_sent')->nullable();
            $table->timestamp('time_code_sent')->nullable();
            $table->boolean('verified')->default(false);
            $table->boolean('two_factor')->default(false);
            $table->string('two_factor_hash')->nullable();
            $table->boolean('two_factor_verified')->default(false);
            $table->string('new_email')->nullable();
            $table->string('new_phone')->nullable();
            
            // Account Status & Role
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->boolean('active')->default(true);
            $table->boolean('banned')->default(false);
            $table->string('banned_reason')->nullable();
            
            // Pro Features
            $table->boolean('is_pro')->default(false);
            $table->timestamp('pro_time')->nullable();
            $table->string('pro_type')->nullable();
            $table->string('pro_remainder')->nullable();
            
            // Points & Rewards
            $table->integer('points')->default(0);
            $table->integer('daily_points')->default(0);
            $table->integer('converted_points')->default(0);
            $table->timestamp('point_day_expire')->nullable();
            
            // Wallet & Payment
            $table->decimal('balance', 10, 2)->default(0);
            $table->string('paypal_email')->nullable();
            $table->string('paystack_ref')->nullable();
            $table->decimal('wallet', 10, 2)->default(0);
            
            // Social Login & Referral
            $table->string('social_login')->nullable();
            $table->string('referrer')->nullable();
            $table->unsignedBigInteger('ref_user_id')->nullable();
            $table->integer('ref_level')->default(0);
            
            // Relationships & Tracking
            $table->unsignedBigInteger('relationship_id')->nullable();
            $table->unsignedBigInteger('last_follow_id')->nullable();
            
            // Metadata
            $table->timestamp('registered')->useCurrent();
            $table->timestamp('lastseen')->nullable();
            $table->boolean('showlastseen')->default(true);
            $table->timestamp('last_login_data')->nullable();
            $table->timestamp('last_data_update')->nullable();
            $table->timestamp('last_email_sent')->nullable();
            $table->timestamp('joined')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop Foreign Key first
            $table->dropForeign(['country_id']);
            
            $table->dropColumn([
                'phone_number', 'about', 'gender', 'birthday', 'country_id',
                'lat', 'lng', 'last_location_update', 'share_my_location',
                'website', 'facebook', 'google', 'x', 'linkedin', 'youtube', 'instagram', 'discord',
                'language', 'timezone', 'weather_unit', 'notifications_sound', 'sfw',
                'follow_privacy', 'friend_privacy', 'post_privacy', 'message_privacy',
                'confirm_followers', 'show_activities_privacy', 'birth_privacy', 'visit_privacy',
                'emailNotification', 'e_liked', 'e_reacted', 'e_shared', 'e_followed',
                'e_commented', 'e_visited', 'e_liked_page', 'e_mentioned', 'e_joined_group',
                'e_accepted', 'e_sentme_msg', 'e_last_notif',
                'email_code', 'sms_code', 'code_sent', 'time_code_sent', 'verified',
                'two_factor', 'two_factor_hash', 'two_factor_verified', 'new_email', 'new_phone',
                'status', 'active', 'banned', 'banned_reason',
                'is_pro', 'pro_time', 'pro_type', 'pro_remainder',
                'points', 'daily_points', 'converted_points', 'point_day_expire',
                'balance', 'paypal_email', 'paystack_ref', 'wallet',
                'social_login', 'referrer', 'ref_user_id', 'ref_level',
                'relationship_id', 'last_follow_id',
                'registered', 'lastseen', 'showlastseen', 'last_login_data',
                'last_data_update', 'last_email_sent', 'joined'
            ]);
        });
    }
};