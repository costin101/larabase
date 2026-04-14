<?php namespace App\Actions\User;

class CreateUserAction
{
    public function execute(array $data)
    {
        try {
            \DB::beginTransaction();
            $user = \App\Models\User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
                'country' => $data['country'],
                'birthday' => $data['birthday'],
                'phone_number' => $data['phone_number'],
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