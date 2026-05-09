<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    // Nvériyiw wesh l'utilisateur 3ndo l7a9 ydir had la requête
    public function authorize(): bool
    {
        return true;
    }

    // Les règles dyal lvalidation
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'mot_de_passe' => ['required', 'string'],
        ];
    }

    // L'authentification b les informations lli dkhel
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Ndawzo 'mot_de_passe' f blast 'password' 7it Auth::attempt kay9leb 3la 'password'
        if (! Auth::attempt(['email' => $this->email, 'password' => $this->mot_de_passe], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    // Nvériyiw wesh dar bzaf dyal les tentatives
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    // La clé dyal throttle bach n3erfo chhal mn mra 7awel
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
