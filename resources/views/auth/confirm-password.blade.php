<x-app-layout>
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 px-4 py-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Confirmar Contraseña</h3>
                    <p class="text-muted small">
                        Esta es un área segura de la aplicación. Por favor, confirma tu contraseña antes de continuar.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <!-- Password -->
                    <div class="mb-3">
                        <x-input-label for="password" :value="__('Contraseña')" />
                        <x-text-input id="password" type="password" name="password" required
                            autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" />
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <x-primary-button>
                            {{ __('Confirmar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>