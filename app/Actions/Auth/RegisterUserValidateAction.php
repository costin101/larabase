<?php

namespace App\Actions\Auth;

use App\Enums\GenderEnum;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class RegisterUserValidateAction
{
    /**
     * Custom error messages.
     */
    protected function messages(): array
    {
        return [
            'first_name.required' => __('messages.first_name_required'),
            'last_name.required' => __('messages.last_name_required'),
            'username.required' => __('messages.username_required'),
            'username.unique' => __('messages.username_taken'),
            'username.string' => __('messages.username_invalid'),
            'username.max' => __('messages.username_max'),
            'email.required' => __('messages.email_required'),
            'email.email' => __('messages.email_invalid'),
            'email.unique' => __('messages.email_taken'),
            'password.required' => __('messages.password_required'),
            'password.confirmed' => __('messages.password_confirm'),
            'password.min' => __('messages.password_min'),
            'country.required' => __('messages.country_required'),
            'country.exists' => __('messages.country_invalid'),
            'birthday.required' => __('messages.birthday_required'),
            'birthday.date' => __('messages.birthday_date'),
            'phone_number.required' => __('messages.phone_number_required'),
            'phone_number.unique' => __('messages.phone_number_taken'),
            'phone_number.max' => __('messages.phone_number_max'),
            'phone_number.min' => __('messages.phone_number_min'),
            'phone_number.phone' => __('messages.phone_number_invalid'),
        ];
    }

    /**
     * Execute the action to validate the fields.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function execute(Request $request)
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:' . User::class,
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
            'country' => 'required|int|exists:countries,id', // Ensures country exists in DB
            'birthday' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    // 1. Find the country based on the request input
                    $country = Country::where('name', $request->country)->first();

                    // 2. If country exists and has a minimum age set (ignoring -1)
                    if ($country && $country->minimum_age > 0) {
                        $birthDate = Carbon::parse($value);
                        $age = $birthDate->age;

                        if ($country->minimum_age > 0 && $age < $country->minimum_age) {
                            $fail(__('messages.under_age_limit', [
                                'age' => $country->minimum_age,
                                'country' => $country->name
                            ]));
                        }
                    }
                },
            ],
            'phone_number' => 'required|string|max:14|unique:' . User::class,
            'gender' => ['required', Rule::in(GenderEnum::getAll())],
        ];

        $validator = Validator::make($request->all(), $rules, $this->messages());
        $validator->validate();
    }
}