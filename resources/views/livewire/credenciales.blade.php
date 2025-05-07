<div class="container-fluid text-center mt-5">
    <style>
        .btn-guardar, .btn-guardar:hover{
            background: #EBC010;
            color: #000;
            font-weight: 700;
        }

        .ganador-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .ganador-card {
            border: 2px solid #ffc107;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
            margin: 10px;
            width: auto;
        }
        .ganador-card:hover{
            transform: scale(1.05);
            z-index: 9;
            box-shadow: 0px 5px 5px 0px #ffc107;
        }
        .ganador-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
        }
        .ganador-label {
            font-size: 1.2rem;
            color: #fff;
            margin-bottom: 10px;
        }
    </style>
    <div class="ganador-container">
        @foreach ($users as $index => $user)
            <div class="ganador-card">
                <div class="ganador-name">Usuario: <strong style="color: #EBC010;">{{ $user->user->email }}</strong></div>
                <div class="ganador-name">Contraseña: <strong style="color: #EBC010;">{{ $credentials[$index]['password'] ?? 'No disponible' }}</strong></div>
                <div>
                    <button wire:click="editarUsuario({{ $user->user->id }})" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal">Editar Usuario</button>
                    <button wire:click="borrarUsuario({{ $user->user->id }})" class="btn btn-danger" wire:confirm="¿Estás seguro de eliminar al usuario?">Borrar Usuario</button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="actualizarUsuario">
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" id="editEmail" wire:model="editEmail" class="form-control">
                            @error('editEmail') <span class="text-danger">{{ $message }}</span> @enderror
                        </div><br>
                        <div class="form-group">
                            <label for="editPassword">Contraseña (dejar en blanco para no cambiarla)</label>
                            <input type="password" id="editPassword" wire:model="editPassword" class="form-control">
                            @error('editPassword') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning">Actualizar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.addEventListener('close-modal', event => {
            var modalElement = document.getElementById('editUserModal');
            var modal = bootstrap.Modal.getInstance(modalElement);
            modal.hide();
        });
    });
</script>