<x-app-layout>
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 px-4 py-4 text-center">
                <div class="mb-4">
                    <h3 class="fw-bold">Verifica tu Correo</h3>
                    <p class="text-muted">
                        ¡Gracias por registrarte! Antes de comenzar, ¿podrías verificar tu dirección de correo haciendo
                        clic en el enlace que te acabamos de enviar? Si no recibiste el correo, con gusto te enviaremos
                        otro.
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success border-0 small mb-4">
                        {{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo que proporcionaste durante el registro.') }}
                    </div>
                @endif

                <div class="d-flex flex-column gap-3">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <x-primary-button class="w-100">
                            {{ __('Reenviar Correo de Verificación') }}
                        </x-primary-button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link text-secondary text-decoration-none small">
                            {{ __('Cerrar Sesión') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>