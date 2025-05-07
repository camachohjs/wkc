document.addEventListener('DOMContentLoaded', function () {
    const sidebarCollapseButton = document.getElementById('sidebarCollapse');
    const headerCollapseButton = document.getElementById('sidebarCollapse1');
    const headerCollapse1Button = document.getElementById('headerCollapse');
    const sidebar = document.querySelector('.left-sidebar');

    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
            sidebar.style.display = 'none';
            headerCollapseButton.style.display = 'flex';
            headerCollapse1Button.style.display = 'none';
        } else {
            sidebar.style.display = '';
            headerCollapseButton.style.display = 'none';
            headerCollapseButton.style.display = 'none';
        }
    }

    if (sidebarCollapseButton && headerCollapseButton) {
        sidebarCollapseButton.addEventListener('click', toggleSidebar);
        headerCollapseButton.addEventListener('click', toggleSidebar);
    }
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
                window.location.href = `/escuelas-edit/${escuela.id}`;
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    window.addEventListener('abrir-modal-pdf', event => {
        var modalElement = document.getElementById('pdfDiseñoModal');
        var modalInstance = new bootstrap.Modal(modalElement);
        modalInstance.show();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-pdf').forEach(function(button) {
        button.addEventListener('click', function() {
            var modalElement = document.getElementById('pdfDiseñoModal');
            var modalInstance = bootstrap.Modal.getInstance(modalElement);
            modalInstance.hide();
        });
    });
});

function showCategory(registroId, torneoNombre) {
    const dropdownButton = document.getElementById('dropdownMenuButton-' + registroId);
    if (dropdownButton) {
        dropdownButton.getElementsByTagName('span')[0].textContent = torneoNombre;
    }

    document.querySelectorAll('.text-container').forEach(function(container) {
        container.style.display = 'none';
    });

    const categoryInfoDiv = document.getElementById('category-info-' + registroId);
    if (categoryInfoDiv) {
        categoryInfoDiv.style.display = 'block';
    }
}
