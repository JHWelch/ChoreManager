<x-guest-layout>
  <div class="fixed top-0 right-0 px-6 py-4">
    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
  </div>

  <x-authentication-card>
    <x-slot name="logo">
      <x-authentication-card-logo />
    </x-slot>

    <x-validation-errors class="mb-4" />

    @if (session('status'))
      <div class="mb-4 text-sm font-medium text-green-600">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div>
        <x-label for="email" value="{{ __('Email') }}" />
        <x-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus />
      </div>

      <div class="mt-4">
        <x-label for="password" value="{{ __('Password') }}" />
        <x-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="current-password" />
      </div>

      <div class="block mt-4">
        <label for="remember_me" class="flex items-center">
          <x-checkbox id="remember_me" name="remember" />
          <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>
      </div>

      <div class="flex items-center justify-end mt-4">
        @if (Route::has('password.request'))
          <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('password.request') }}">
            {{ __('Forgot your password?') }}
          </a>
        @endif

        <x-button class="ml-4">
          {{ __('Login') }}
        </x-button>
      </div>
    </form>
  </x-authentication-card>
</x-guest-layout>
