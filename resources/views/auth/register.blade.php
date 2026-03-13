<x-app-layout>
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 px-4 py-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Crear Cuenta</h3>
                    <p class="text-muted">Regístrate para gestionar tu biblioteca</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <x-input-label for="name" :value="__('Nombre Completo')" />
                        <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus
                            autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" />
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <x-input-label for="email" :value="__('Correo Electrónico')" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required
                            autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" />
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <x-input-label for="password" :value="__('Contraseña')" />
                            <x-text-input id="password" type="password" name="password" required
                                autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6 mb-3">
                            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                            <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                                required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" />
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <x-primary-button>
                            {{ __('Registrarse en Libroteca') }}
                        </x-primary-button>
                    </div>

                    <div class="mt-4 text-center">
                        <a class="text-decoration-none small text-primary" href="{{ route('login') }}">
                            ¿Ya tienes una cuenta? Inicia sesión aquí
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>