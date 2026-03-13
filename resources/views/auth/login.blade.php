<x-app-layout>
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 px-4 py-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Iniciar Sesión</h3>
                    <p class="text-muted">Ingresa tus credenciales para acceder</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-3" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <x-input-label for="email" :value="__('Correo Electrónico')" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" />
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <x-input-label for="password" :value="__('Contraseña')" />
                        <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" />
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check mb-3 text-start">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label for="remember_me" class="form-check-label text-secondary small">
                            {{ __('Recordarme en este equipo') }}
                        </label>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <x-primary-button>
                            {{ __('Ingresar al Sistema') }}
                        </x-primary-button>
                    </div>

                    <div class="mt-4 text-center">
                        @if (Route::has('password.request'))
                            <a class="text-decoration-none small text-primary" href="{{ route('password.request') }}">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>