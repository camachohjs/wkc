document.addEventListener('livewire:load', function () {
    Livewire.hook('message.sent', (message, component) => {
        if (message.updateQueue && message.updateQueue.includes('passwordStrength')) {
            component.call('updatePasswordStrength');
        }
    });

    Livewire.on('passwordStrengthUpdated', function (data) {
        let passwordStrength = data.passwordStrength;
        let progressBar = document.querySelector('.progress-bar');
        progressBar.style.width = passwordStrength * 20 + '%';
    });
});

document.addEventListener('livewire:load', function () {
    Livewire.on('openCreateModal', () => {
        console.log('Evento openCreateModal recibido');
        $('#createModal').modal('show'); 
    });

    Livewire.on('closeModal', () => {
        console.log('Evento closeModal recibido');
        $('#createModal').modal('hide');
    });
});

function init() {
    setTimeout(() => {
        location.reload();
    }, 1000);
}

document.addEventListener('livewire:load', function () {
    Livewire.hook('message.sent', (message, component) => {
        component.fecha_actual = (new Date()).toISOString().slice(0, 16);
    });
});

//se muestre tooltip
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    window.addEventListener('sugerirEscuela', event => {
        const escuela = event.detail[0].escuela;

        Swal.fire({
            title: 'Confirmación',
            text: `¿Te refieres a la escuela "${escuela.nombre}"?`,
            icon: 'question',
            customClass: {
                content: 'blanco'
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/agregar-escuela/${escuela.id}`;
            }
        });
    });
});