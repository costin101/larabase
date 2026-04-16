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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password');
            $table->string('username')->unique();
            
            // Personal Info
            $table->string('about')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->date('birthday');
            $table->boolean('birthday_verified')->default(false);

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
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('inactive');
            $table->boolean('active')->default(false);
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
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
