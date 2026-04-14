<?php namespace App\Actions\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterUserValidateAction
{
    protected array $messages = [
        'first_name.required' => __('messages.first_name_required'),
        'last_name.required' => __('messages.last_name_required'),
        'email.required' => __('messages.email_required'),
        'email.email' => __('messages.email_invalid'),
        'email.unique' => __('messages.email_taken'),
        'password.required' => __('messages.password_required'),
        'password.confirmed' => __('messages.password_confirm'),
        'password.min' => __('messages.password_min'),
        'country.required' => __('messages.country_required'),
        'birthday.required' => __('messages.birthday_required'),
        'birthday.date' => __('messages.birthday_date'),
        'phone_number.required' => __('messages.phone_number_required'),
        'phone_number.unique' => __('messages.phone_number_taken'),
        'phone_number.max' => __('messages.phone_number_max'),
        'phone_number.min' => __('messages.phone_number_min'),
        'phone_number.phone' => __('messages.phone_number_invalid'),
    ];

    protected array $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|lowercase|email|max:255|unique:'.\App\Models\User::class,
        'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        'country' => 'required|string|max:255',
        'birthday' => 'required|date', 
        'phone_number' => 'required|string|max:14|unique:'.\App\Models\User::class,
    ];

    /**
     * Execute the action to validate the fields.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function execute(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $validator->validate();
    }
}