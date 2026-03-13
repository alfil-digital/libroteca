<x-app-layout>
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 px-4 py-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Recuperar Contraseña</h3>
                    <p class="text-muted small">
                        ¿Olvidaste tu contraseña? No hay problema. Indícanos tu correo y te enviaremos un enlace para
                        restablecerla.
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <x-input-label for="email" :value="__('Correo Electrónico')" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" />
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <x-primary-button>
                            {{ __('Enviar Enlace de Restablecimiento') }}
                        </x-primary-button>
                    </div>

                    <div class="mt-4 text-center">
                        <a class="text-decoration-none small text-secondary" href="{{ route('login') }}">
                            Volver al inicio de sesión
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>