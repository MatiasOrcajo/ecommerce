<style>
    .modal-content {
        display: flex;
        flex-direction: row;
        border-radius: 0;
        max-width: 700px;
        margin: auto;
    }

    .popup-image {
        width: 50%;
        background: url('https://files.myperfit.net/tiendanapoli/75a6a2ce/form_1pguix.png') center center / cover no-repeat;
        min-height: 100%;
    }

    .popup-form {
        width: 50%;
        padding: 2rem;
    }

    .form-control {
        border-radius: 0;
        margin-bottom: 1rem;
    }

    .btn-dark {
        border-radius: 0;
    }

    @media (max-width: 768px) {
        .modal-content {
            flex-direction: column;
        }

        .popup-image,
        .popup-form {
            width: 100%;
        }

        .popup-image {
            height: 300px;
        }
    }
</style>

<!-- Modal -->
<div class="modal fade" id="promoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="popup-image d-none d-md-block"></div>
            <div class="popup-form">
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                <h5 class="mt-2">Atención 📩 Llevate 10% OFF de regalo</h5>
                <p>Unite para recibirlo</p>
                <input type="email" class="form-control" placeholder="Email">
                <input type="text" class="form-control" placeholder="Cómo te gusta que te llamen">
                <button id="subscribe_button" class="btn btn-dark w-100">Suscribirme</button>
                <small class="d-block text-muted mt-2">Recibirás un correo para validar tu email.</small>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle (JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Mostrar popup automáticamente -->
<script>
    $(document).ready(function () {


        setTimeout(function () {

            const showPromoModal = () => {
                const promoModal = new bootstrap.Modal(document.getElementById('promoModal'));
                promoModal.show();

                promoModal._element.addEventListener('hidden.bs.modal', function () {
                    const expirationTime = new Date().getTime() + (60 * 60 * 1000);
                    localStorage.setItem('promoModalData', JSON.stringify({ shown: true, expiresAt: expirationTime }));
                });
            };

            const promoData = localStorage.getItem('promoModalData');
            if (promoData) {
                const { shown, expiresAt } = JSON.parse(promoData);
                const currentTime = new Date().getTime();

                if (!shown || currentTime > expiresAt) {
                    // Si no se ha mostrado o ya expiró, mostramos el modal nuevamente
                    showPromoModal();
                }
            } else {
                // Si no hay dato registrado, mostramos el modal por primera vez
                showPromoModal();
            }
        }, 4000);
    });




</script>
