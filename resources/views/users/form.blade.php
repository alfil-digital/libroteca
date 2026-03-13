<form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}">
    <!-- Determina si la acción es crear o actualizar -->
    @csrf <!-- Token de seguridad para prevenir ataques de falsificación de solicitud -->
    @if(isset($user)) <!-- Si estamos editando, usamos el método PUT de HTTP -->
        @method('PUT')
    @endif

    <div class="row g-3"> <!-- Fila de Bootstrap con espacio entre columnas -->
        <!-- Campo: Nombre -->
        <div class="col-md-6"> <!-- Ocupa media pantalla en dispositivos medianos o más -->
            <label for="first_name" class="form-label fw-bold">Nombre</label> <!-- Etiqueta con estilo Bootstrap -->
            <input type="text" name="first_name" id="first_name"
                class="form-control @error('first_name') is-invalid @enderror"
                value="{{ old('first_name', $user->person->first_name ?? '') }}" required autofocus>
            <!-- Entrada con validación visual -->
            @error('first_name') <!-- Muestra error de validación bajo el campo -->
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo: Apellido -->
        <div class="col-md-6"> <!-- Columna para el apellido -->
            <label for="last_name" class="form-label fw-bold">Apellido</label>
            <input type="text" name="last_name" id="last_name"
                class="form-control @error('last_name') is-invalid @enderror"
                value="{{ old('last_name', $user->person->last_name ?? '') }}" required>
            @error('last_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo: DNI / Identificación -->
        <div class="col-md-6">
            <label for="id_number" class="form-label fw-bold">Número de Identificación (DNI)</label>
            <input type="text" name="id_number" id="id_number"
                class="form-control @error('id_number') is-invalid @enderror"
                value="{{ old('id_number', $user->person->id_number ?? '') }}" required>
            @error('id_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo: Correo Electrónico -->
        <div class="col-md-6">
            <label for="email" class="form-label fw-bold">Correo Electrónico</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email ?? '') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo: Rol del Sistema -->
        <div class="col-md-6">
            <label for="role_id" class="form-label fw-bold">Rol del Sistema</label>
            <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                <!-- Selector desplegable de Bootstrap -->
                <option value="">Selecciona un Rol</option> <!-- Opción inicial vacía -->
                @foreach($roles as $role) <!-- Recorre los roles disponibles -->
                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }} <!-- Muestra el nombre del rol -->
                    </option>
                @endforeach
            </select>
            @error('role_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo: Teléfono -->
        <div class="col-md-6">
            <label for="phone" class="form-label fw-bold">Teléfono (Opcional)</label>
            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone', $user->person->phone ?? '') }}">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo: Dirección -->
        <div class="col-12"> <!-- Columna que ocupa todo el ancho disponible -->
            <label for="address" class="form-label fw-bold">Dirección (Opcional)</label>
            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror"
                rows="2">{{ old('address', $user->person->address ?? '') }}</textarea> <!-- Área de texto -->
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campos de Contraseña -->
        <div class="col-md-6">
            <label for="password" class="form-label fw-bold">
                Contraseña {{ isset($user) ? '(Dejar en blanco para no cambiar)' : '' }} <!-- Etiqueta dinámica -->
            </label>
            <input type="password" name="password" id="password"
                class="form-control @error('password') is-invalid @enderror" {{ isset($user) ? '' : 'required' }}>
            <!-- Contraseña obligatoria solo al crear -->
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="password_confirmation" class="form-label fw-bold">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" {{ isset($user) ? '' : 'required' }}> <!-- Campo espejo para confirmar la clave -->
        </div>
    </div>

    <!-- Botonera de Acciones -->
    <div class="mt-5 d-flex justify-content-end align-items-center gap-3">
        <!-- Alineación a la derecha con espacio entre elementos -->
        <a href="{{ route('users.index') }}" class="text-secondary text-decoration-none">Cancelar</a>
        <!-- Enlace para volver sin guardar -->
        <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm"> <!-- Botón de envío principal -->
            {{ isset($user) ? 'Actualizar Usuario' : 'Crear Usuario' }} <!-- Texto dinámico -->
        </button>
    </div>
</form>