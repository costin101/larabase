<?php namespace App\Actions\User;

use App\Models\Country;
use Illuminate\Http\Request;

class CreateUserAction
{
    public function execute(Request $request)
    {
        try {
            $data = $request->all();
            
            \DB::beginTransaction();
            $user = \App\Models\User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
                'country_id' => $data['country'],
                'birthday' => $data['birthday'],
                'phone_number' => $data['phone_number'],
                'email_code' => rand(100000, 999999),
                'sms_code' => rand(100000, 999999),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'geneder' => $data['gender'],
            ]);
            \DB::commit();

            return $user;
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error creating user: '.$e->getMessage());
            throw $e;
        }
    }
}