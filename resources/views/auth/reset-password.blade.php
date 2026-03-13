<x-app-layout>
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 px-4 py-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Nueva Contraseña</h3>
                    <p class="text-muted small">Establece tu nueva contraseña de acceso</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div class="mb-3">
                        <x-input-label for="email" :value="__('Correo Electrónico')" />
                        <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)"
                            required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" />
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <x-input-label for="password" :value="__('Contraseña Nueva')" />
                        <x-text-input id="password" type="password" name="password" required
                            autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <x-input-label for="password_confirmation" :value="__('Confirmar Nueva Contraseña')" />
                        <x-text-input id="password_confirmation" type="password" name="password_confirmation" required
                            autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" />
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <x-primary-button>
                            {{ __('Restablecer Contraseña') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>