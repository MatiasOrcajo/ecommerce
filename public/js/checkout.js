document.addEventListener("DOMContentLoaded", () => {
    "use strict";

    /* =========================================================================
     * 1) SELECTORES Y ESTADO COMPARTIDO
     * ========================================================================= */

    // Estado de selección (se usa al enviar la orden)
    let selectedShipmentMethod = null;
    let selectedPaymentMethod = null; // (se setea por los botones de pago)

    // Botones de métodos de pago / envío
    const paymentMethodButtons = document.querySelectorAll(".payment_method");
    const shipmentMethodButtons = document.querySelectorAll(".shipping_method");

    // Campos de dirección
    const provinceEl = document.getElementById('province');
    const localityEl = document.getElementById('locality');


    const addressSectionEl = document.getElementById('address-section');

// Summary (step 2)
    const summaryEmailEl = document.getElementById('summaryEmail');
    const summaryPhoneEl = document.getElementById('summaryPhone');
    const summaryShippingMethodEl = document.getElementById('summaryShippingMethod');
    const summaryAddressEl = document.getElementById('summaryAddress');
    const summaryCityZipEl = document.getElementById('summaryCityZip');
    const summaryEditContactBtn = document.getElementById('summaryEditContact');
    const summaryEditShippingBtn = document.getElementById('summaryEditShipping');


    function toggleAddressSectionByShipmentMethod(method) {
        if (!addressSectionEl) return;

        if (method === 'take-away') {
            addressSectionEl.classList.add('d-none');
        } else {
            addressSectionEl.classList.remove('d-none');
            const zip = document.getElementById('zip_code')?.value || '';
            const zipReadonly = document.getElementById('zip_code_readonly');
            if (zipReadonly) zipReadonly.value = zip;
        }
    }


    // Opción “llega hoy / mañana / lunes”
    const arrivesWrapper = document.getElementById('shipping-option-wrapper');
    const arrivesTitleEl = document.getElementById('shipping-option-title');
    const arrivesSubEl = document.getElementById('shipping-option-subtitle');

    // Botones de pasos (tabs)
    const steps = document.querySelectorAll("#steps .li-step");
    const tabs = document.querySelectorAll(".tab-pane");

    // Totales / cupón
    let oldOrderTotalBeforeCoupon = 0;
    let total = 0;
    let helperTotalAmountToBeDisplayed = 0;
    let couponIsApplied = 0;
    let coupon_id = null;

    /* =========================================================================
     * 2) CONSTANTES
     * ========================================================================= */

    // Partidos/Localidades habilitados para la opción “llega hoy/mañana/lunes”
    const AVAILABLE_FOR_ARRIVES_TODAY = [
        'Almirante Brown', 'Avellaneda', 'Berazategui', 'Berisso', 'Campana', 'Cañuelas',
        'Ciudad Autónoma de Buenos Aires', 'Ensenada', 'Escobar', 'Esteban Echeverría',
        'Ezeiza', 'Florencio Varela', 'General Las Heras', 'General Rodríguez',
        'General San Martín', 'Hurlingham', 'Ituzaingó', 'José C. Paz', 'La Matanza',
        'La Plata', 'Lanús', 'Lomas de Zamora', 'Luján', 'Malvinas Argentinas',
        'Marcos Paz', 'Merlo', 'Moreno', 'Morón', 'Pilar', 'Presidente Perón', 'Quilmes',
        'San Fernando', 'San Isidro', 'San Miguel', 'San Vicente', 'Tigre', 'Tres de Febrero',
        'Vicente López', 'Zárate'
    ];

    // Barrios de CABA (para completar el select de localidad)
    const BARRIOS_CABA = [
        "Agronomía", "Almagro", "Balvanera", "Barracas", "Belgrano", "Boedo", "Caballito",
        "Chacarita", "Coghlan", "Colegiales", "Constitución", "Flores", "Floresta", "La Boca",
        "La Paternal", "Liniers", "Mataderos", "Monte Castro", "Monserrat", "Nueva Pompeya",
        "Núñez", "Palermo", "Parque Avellaneda", "Parque Chacabuco", "Parque Chas",
        "Parque Patricios", "Puerto Madero", "Recoleta", "Retiro", "Saavedra", "San Cristóbal",
        "San Nicolás", "San Telmo", "Vélez Sársfield", "Versalles", "Villa Crespo",
        "Villa del Parque", "Villa Devoto", "Villa General Mitre", "Villa Lugano", "Villa Luro",
        "Villa Ortúzar", "Villa Pueyrredón", "Villa Real", "Villa Riachuelo", "Villa Santa Rita",
        "Villa Soldati", "Villa Urquiza"
    ];

    /* =========================================================================
     * 3) UTILIDADES
     * ========================================================================= */

    /**
     * Retorna la fecha/hora actual en Buenos Aires (UTC-3) con componentes útiles.
     * @returns {{year:number, month:number, day:number, hour:number, minute:number, second:number, weekday:number, date:Date}}
     */
    function getNowInBuenosAires() {
        try {
            // Usar Intl con zona "America/Argentina/Buenos_Aires"
            const parts = new Intl.DateTimeFormat('en-CA', {
                timeZone: 'America/Argentina/Buenos_Aires',
                hour12: false,
                year: 'numeric', month: '2-digit', day: '2-digit',
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            })
                .formatToParts(new Date())
                .reduce((acc, p) => {
                    if (p.type !== 'literal') acc[p.type] = parseInt(p.value, 10);
                    return acc;
                }, {});

            // Fecha "como si fuera UTC" con los componentes de BA.
            // Esto permite usar getUTC* y setUTC* como si fueran locales de BA.
            const baLikeUTC = new Date(Date.UTC(
                parts.year, parts.month - 1, parts.day,
                parts.hour, parts.minute, parts.second
            ));

            return {
                year: parts.year,
                month: parts.month,
                day: parts.day,
                hour: parts.hour,
                minute: parts.minute,
                second: parts.second,
                weekday: baLikeUTC.getUTCDay(), // 0=Dom ... 6=Sáb (día de BA)
                date: baLikeUTC
            };
        } catch (_) {
            // Fallback: UTC-3 fijo (BA no tiene DST actualmente)
            const now = new Date();
            const utcMs = now.getTime() + now.getTimezoneOffset() * 60000;
            const baMs = utcMs - (3 * 3600000); // UTC-3
            const ba = new Date(baMs);

            return {
                year: ba.getUTCFullYear(),
                month: ba.getUTCMonth() + 1,
                day: ba.getUTCDate(),
                hour: ba.getUTCHours(),
                minute: ba.getUTCMinutes(),
                second: ba.getUTCSeconds(),
                weekday: ba.getUTCDay(), // 0=Dom ... 6=Sáb
                date: ba
            };
        }
    }

    /**
     * Regla de negocio: compone el título de la opción de envío en BA
     * (Llega hoy / mañana / lunes) + cuenta regresiva hasta las 13:00.
     * @param {{hour:number, weekday:number}} nowBA
     * @returns {string} HTML con el título y el contador
     */
    function computeShippingTitleForBA(nowBA) {
        const hour = nowBA.hour;
        const weekday = nowBA.weekday;        // 0=Dom...6=Sáb
        const isWeekday = weekday >= 1 && weekday <= 5; // Lun-Vie
        const beforeCutoff = hour <= 12;

        // Helper de padding
        const pad = (n) => String(n).padStart(2, '0');

        // “Ahora” en BA usando Intl (timeZone segura)
        const parts = new Intl.DateTimeFormat('en-CA', {
            timeZone: 'America/Argentina/Buenos_Aires',
            hour12: false,
            year: 'numeric', month: '2-digit', day: '2-digit',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        }).formatToParts(new Date()).reduce((acc, p) => {
            if (p.type !== 'literal') acc[p.type] = parseInt(p.value, 10);
            return acc;
        }, {});

        const baNow = new Date(Date.UTC(
            parts.year, parts.month - 1, parts.day,
            parts.hour, parts.minute, parts.second
        ));

        let title;
        let deadline = new Date(baNow);

        // Fines de semana: llega el lunes
        if (!isWeekday) {
            title = '¡Llega gratis el lunes!';
            const daysAhead = (1 - weekday + 7) % 7 || 7; // próximo lunes
            deadline.setUTCDate(deadline.getUTCDate() + daysAhead);
            deadline.setUTCHours(15, 0, 0, 0);
        }
        // Días de semana antes de las 13: hoy
        else if (beforeCutoff) {
            title = '¡Llega gratis hoy!';
            deadline.setUTCHours(15, 0, 0, 0);
        }
        // Viernes después de las 13: lunes
        else if (weekday === 5) {
            title = '¡Llega gratis el lunes!';
            const daysAhead = (1 - weekday + 7) % 7 || 7;
            deadline.setUTCDate(deadline.getUTCDate() + daysAhead);
            deadline.setUTCHours(15, 0, 0, 0);
        }
        // Resto: mañana
        else {
            title = '¡Llega gratis mañana!';
            deadline.setUTCDate(deadline.getUTCDate() + 1);
            deadline.setUTCHours(15, 0, 0, 0);
        }

        // Cuenta regresiva HH:MM:SS
        const msLeft = Math.max(0, deadline - baNow);
        const totalSec = Math.floor(msLeft / 1000);
        const hh = Math.floor(totalSec / 3600) - 3;
        const mm = Math.floor((totalSec % 3600) / 60);
        const ss = totalSec % 60;

        const countdown = `${hh}:${pad(mm)}:${pad(ss)}`;

        return `${title}`;
    }

    /**
     * ¿Provincia/localidad habilita la opción “llega hoy/…”?
     */
    function isEligibleForArrivesToday(selectedProvince, selectedLocality) {
        return (
            (selectedProvince === 'Buenos Aires' &&
                AVAILABLE_FOR_ARRIVES_TODAY.includes(selectedLocality)) ||
            selectedProvince === 'Ciudad Autónoma de Buenos Aires'
        );
    }

    /**
     * Setea el título dinámico de la opción “llega …”
     */
    function setTitle() {
        const nowBA = getNowInBuenosAires();
        arrivesTitleEl.innerHTML = computeShippingTitleForBA(nowBA);
    }

    /**
     * Muestra/oculta el wrapper de la opción “llega …” según provincia/localidad.
     * También inicia (cada vez que se llama) un intervalo de 1s para refrescar el título.
     * Nota: se mantiene este comportamiento para no cambiar la funcionalidad original.
     */
    function updateShippingOptionUI() {
        if (!provinceEl || !localityEl || !arrivesWrapper || !arrivesTitleEl) return;

        const selectedProvince = provinceEl.value || '';
        const selectedLocality = localityEl.value || '';
        const eligible = isEligibleForArrivesToday(selectedProvince, selectedLocality);

        arrivesWrapper.classList.toggle('d-none', !eligible);
        if (!eligible) return;

        setInterval(setTitle, 1000); // igual que en el código original
    }

    /**
     * Convierte % de descuento a “resto” en decimales (100→0, 20→0.8, etc.)
     */
    function getRemainingPercentageInDecimals(discount) {
        return 1 - (discount / 100);
    }

    /* =========================================================================
     * 4) LISTENERS INICIALES / SELECCIÓN DE MÉTODO DE ENVÍO
     * ========================================================================= */

    // Seteo del método de envío según los botones
    document.getElementById("take-away-button")
        ?.addEventListener("click", () => {
            selectedShipmentMethod = "take-away";
            toggleAddressSectionByShipmentMethod(selectedShipmentMethod);
        });

    document.getElementById("andreani-button")
        ?.addEventListener("click", () => {
            selectedShipmentMethod = "andreani";
            toggleAddressSectionByShipmentMethod(selectedShipmentMethod);
        });

    document.getElementById("shipping-option-wrapper")
        ?.addEventListener("click", () => {
            selectedShipmentMethod = "FLEX";
            toggleAddressSectionByShipmentMethod(selectedShipmentMethod);
        });

    // Mostrar/ocultar la opción “llega …” al cambiar localidad/provincia
    // (bloque que en el original estaba duplicado; lo conservamos)
    document.getElementById('locality')?.addEventListener('change', (event) => {
        const selectedLocality = event.target.value;
        const selectedProvince = document.getElementById('province').value;
        const arrivesTodayButton = document.getElementById('shipping-option-wrapper');

        if ((selectedProvince === "Buenos Aires" && AVAILABLE_FOR_ARRIVES_TODAY.includes(selectedLocality))
            || selectedProvince === "Ciudad Autónoma de Buenos Aires") {
            arrivesTodayButton.classList.remove('d-none');
        } else {
            arrivesTodayButton.classList.add('d-none');
        }
    });

    document.getElementById('province')?.addEventListener('change', (event) => {
        const selectedLocality = document.getElementById('locality').value;
        const selectedProvince = event.target.value;
        const arrivesTodayButton = document.getElementById('shipping-option-wrapper');

        if ((selectedProvince === "Buenos Aires" && AVAILABLE_FOR_ARRIVES_TODAY.includes(selectedLocality))
            || selectedProvince === "Ciudad Autónoma de Buenos Aires") {
            arrivesTodayButton.classList.remove('d-none');
        } else {
            arrivesTodayButton.classList.add('d-none');
        }
    });

    /* =========================================================================
 * 5) STYLING DE BOTONES (PAGO / ENVÍO)
 * ========================================================================= */

// Método de pago: usamos clase .selected
    paymentMethodButtons.forEach((button) => {
        button.addEventListener("click", () => {
            // quitamos selección de todos
            paymentMethodButtons.forEach(btn => btn.classList.remove('selected'));

            // marcamos el clickeado
            button.classList.add('selected');

            selectedPaymentMethod = button.getAttribute('data-payment-method');
        });
    });

// Método de envío: solo manejamos visual, el valor ya lo seteás
// en los listeners de "take-away-button", "andreani-button" y "shipping-option-wrapper".
    shipmentMethodButtons.forEach((button) => {
        button.addEventListener("click", () => {
            shipmentMethodButtons.forEach(btn => btn.classList.remove('selected'));
            button.classList.add('selected');
        });
    });


    /* =========================================================================
     * 6) POBLADO DE PROVINCIAS / LOCALIDADES
     * ========================================================================= */

    /**
     * Carga provincias en <select id="province"> y, al cambiar,
     * carga municipios en <select id="locality"> (más barrios CABA si corresponde).
     */
    function listProvinces() {
        axios.get("https://apis.datos.gob.ar/georef/api/provincias?campos=id,nombre")
            .then(response => {
                const province = response.data.provincias
                    .sort((a, b) => a.nombre.localeCompare(b.nombre));

                let html = "";
                province.forEach(p => {
                    html += `<option value="${p.nombre}">${p.nombre}</option>`;
                });
                html += `<option selected="true" disabled="disabled">Seleccione una opción</option>`;
                document.getElementById('province').innerHTML = html;
            });

        document.getElementById('province')?.addEventListener('change', (event) => {
            axios.get(`https://apis.datos.gob.ar/georef/api/municipios?provincia=${event.target.value}&campos=id,nombre&max=500`)
                .then(response => {
                    const locality = response.data.municipios
                        .sort((a, b) => a.nombre.localeCompare(b.nombre));

                    let html = "";
                    locality.forEach(l => {
                        if (!l.nombre.includes("Comuna")) {
                            html += `<option value="${l.nombre}">${l.nombre}</option>`;
                        }
                    });

                    if (event.target.value === "Ciudad Autónoma de Buenos Aires") {
                        BARRIOS_CABA.forEach(bar => {
                            html += `<option value="${bar}">${bar}</option>`;
                        });
                    }

                    html += `<option selected="true" disabled="disabled">Seleccione una opción</option>`;
                    document.getElementById('locality').innerHTML = html;
                });
        });
    }

    listProvinces();

    /* =========================================================================
     * 7) NAVEGACIÓN ENTRE PASOS (TABS)
     * ========================================================================= */

    steps.forEach((step) => {
        step.addEventListener("click", (event) => {
            const clickedStep = event.target.closest(".li-step");
            if (clickedStep) {
                let targetStep = null;

                if (clickedStep.id === "step-client") {
                    targetStep = "step1";
                    document.getElementById('step-client').classList.add("grey-background");
                    document.getElementById('step-payment').classList.remove("grey-background");
                } else {
                    targetStep = "step2";
                    document.getElementById('step-client').classList.remove("grey-background");
                    document.getElementById('step-payment').classList.add("grey-background");
                }
                setActiveTab(targetStep);
            }

            const otherStep = Array.from(steps).find(s => s !== step);
            step.classList.add("grey-background");
            otherStep?.classList.remove("grey-background");
        });
    });

    document.getElementById('continue-to-payment-step-button')
        ?.addEventListener('click', () => {
            setActiveTab("step2");
            document.getElementById('step-client').classList.remove("grey-background");
            document.getElementById('step-payment').classList.add("grey-background");
            fillSummary();
        });

    summaryEditContactBtn?.addEventListener('click', () => {
        setActiveTab('step1');
    });

    summaryEditShippingBtn?.addEventListener('click', () => {
        setActiveTab('step1');
    });

    /**
     * Activa la pestaña dada (step1/step2) y ajusta visualmente los pasos.
     */
    function setActiveTab(targetId) {
        tabs.forEach((tab) => {
            tab.classList.remove("show", "active");
            if (tab.id === targetId) tab.classList.add("show", "active");
        });

        steps.forEach((step) => {
            step.classList.remove("active");
            if (step.id === "step-client" && targetId === "step1") {
                step.classList.add("active", "grey-background");
            } else if (step.id === "step-payment" && targetId === "step2") {
                step.classList.add("active", "grey-background");
            }
        });
    }

    /* =========================================================================
     * 8) ENVÍO DEL FORMULARIO
     * ========================================================================= */

    document.getElementById('submit')?.addEventListener('click', () => {
        // Validaciones mínimas
        const requiredFields = [
            'firstName', 'lastName', 'phone', 'documentNumber', 'email'
        ];

// Solo obligamos domicilio si NO es retiro en CABA
        if (selectedShipmentMethod !== 'take-away') {
            requiredFields.push('locality', 'province', 'street', 'number', 'zip_code');
        }

        let isValid = true;
        requiredFields.forEach((field) => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            toastr.error("Por favor, complete todos los campos obligatorios.");
            return;
        }


        if (!selectedPaymentMethod || !selectedShipmentMethod) {

            toastr.error("Por favor, seleccione un método de pago y de envío.");
            return;
        }

        const data = {
            name: document.getElementById('firstName').value,
            surname: document.getElementById('lastName').value,
            phone: document.getElementById('phone').value,
            dni: document.getElementById('documentNumber').value,
            email: document.getElementById('email').value,
            locality: document.getElementById('locality').value,
            province: document.getElementById('province').value,
            street: document.getElementById('street').value,
            number: document.getElementById('number').value,
            apartment: document.getElementById('apartment').value,
            zip_code: document.getElementById('zip_code').value,
            observations: document.getElementById('observations').value,
            coupon_id: coupon_id,
            payment_method: selectedPaymentMethod,
            shipping_method: selectedShipmentMethod
        };

        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $('#submit').html('<div class="spinner-border text-white" role="status"><span class="visually-hidden">Cargando...</span></div>');

        $.ajax({
            type: "POST",
            url: '/pay',
            data: {data: JSON.stringify(data), _token: $('meta[name="csrf-token"]').attr('content')},
            success: function (res) {
                window.open(res.init_point, '_blank');
                $('#submit').html('Finalizar compra');
            },
            error: function () {
                $('#submit').html('Finalizar compra');
            }
        });
    });

    /* =========================================================================
     * 9) RESUMEN DE CARRITO / CUPÓN
     * ========================================================================= */

    /**
     * Pinta el resumen de items del carrito, maneja borrado, totales y cupón aplicado.
     */
    function getItemsSummary() {
        $('#items-summary-container').html("");

        $.ajax({
            type: "GET",
            url: '/cart-info',
            success: function (xhr) {
                if (xhr.order_total == 0) {
                    location.reload();
                }

                const isCouponApplied = xhr.is_coupon_applied;
                if (isCouponApplied) coupon_id = xhr.coupon_id;

                const products = xhr.products;
                let html = "";
                total = xhr.total;
                oldOrderTotalBeforeCoupon = xhr.old_order_total_before_coupon_was_applied;

                if (Object.entries(products).length <= 0) {
                    location.reload();
                }

                const moneyAR = (v) => {
                    const nf = new Intl.NumberFormat('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    return `$${nf.format(Number(v))}`;
                };

                Object.entries(products).forEach(([_, product]) => {
                    const priceHtml = (product.subtotal > product.total)
                        ? `<del><h4>${moneyAR(product.subtotal)}</h4></del>
                           <h4 class="text-success ms-2">${moneyAR(product.total)}</h4>`
                        : `<h4 class="text-success">${moneyAR(product.total)}</h4>`;

                    html += `
                        <div class="cart-item-card p-3 my-3 border rounded w-100 w-md-75 position-relative">
                            <button class="x-cart-button delete_cart_product"
                                    data-variant-id="${product.product_variant_id}">
                                X
                            </button>

                            <div class="d-flex gap-3">
                                <!-- Thumbnail -->
                                <div class="order-summary-thumbnail flex-shrink-0">
                                    <img src="${product.pic}" alt="" class="img-fluid">
                                    <div class="item-quantity">${product.quantity}</div>
                                </div>

                                <!-- Info + precio -->
                                <div class="d-flex flex-column flex-md-row flex-grow-1 justify-content-between">
                                    <!-- Texto del producto -->
                                    <a href="/productos/${product.slug}"
                                       target="_blank"
                                       class="cart-item-link text-decoration-none text-dark flex-grow-1">
                                        <h5 class="cart-item-title mb-1">
                                            ${product.product_name}
                                        </h5>

                                        <div class="small text-muted mb-1">
                                            Talle: ${product.size}
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <div class="color-box"
                                                 data-color="${product.color}"
                                                 title="${product.color_name}"
                                                 style="background:${product.color}; width:18px; height:18px; margin-right:0.5rem;">
                                            </div>
                                            <span class="small text-muted">${product.color_name}</span>
                                        </div>
                                    </a>

                                    <!-- Precio -->
                                    <div class="cart-item-price mt-2 mt-md-0 ms-md-3 text-md-end">
                                        ${priceHtml}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                $('#order_total').html(`<h1>${moneyAR(xhr.order_total)}</h1>`);
                $('#items-summary-container').empty().append(html);

                $('#order_total_inline').html(moneyAR(xhr.order_total));

                if (isCouponApplied) {
                    $('#order_total').html(`<del><h1>${moneyAR(xhr.order_total)}</h1></del> <h1>${moneyAR(xhr.order_total_after_coupon_applied)}</h1>`);
                    $('#coupon-validated-success').html("Cupón validado");
                    $('#coupon-validated-failed').html("");
                    $('#coupon-success-code').html(`Aplicado ${xhr.coupon_discount}% OFF`);
                    $('#order_total_inline').html(moneyAR(xhr.order_total_after_coupon_applied));
                }

                // Eliminar producto del carrito
                document.querySelectorAll('.delete_cart_product').forEach(element => {
                    element.addEventListener('click', (event) => {
                        const product_variant_id = event.target.getAttribute('data-variant-id');

                        $.ajax({
                            type: "DELETE",
                            url: '/cart',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                product_variant_id
                            },
                            success: function () {
                                getItemsSummary();
                                updateCartCounter();
                                toastr.success("Producto eliminado");
                            }
                        });
                    });
                });
            },
            error: function () {
                $('#items-summary-container').html("");
            }
        });
    }

    getItemsSummary();

    // Validar cupón (mismo flujo original)
    document.getElementById('validate-coupon-button')
        ?.addEventListener('click', () => {
            const coupon = document.getElementById('coupon').value;

            axios.get(`/validate-coupon?code=${coupon}`)
                .then(() => {
                    getItemsSummary();
                })
                .catch(error => {
                    $('#coupon-validated-success').html("");
                    const errorMessage = error?.response?.data?.message || "Unexpected error occurred.";
                    $('#coupon-validated-failed').html(errorMessage);
                });
        });

    /* =========================================================================
     * 10) INICIALIZACIÓN DE UI “LLEGA HOY/MAÑANA/LUNES”
     * ========================================================================= */

    // Al cambiar provincia/localidad recalculamos visibilidad
    if (provinceEl) provinceEl.addEventListener('change', updateShippingOptionUI);
    if (localityEl) localityEl.addEventListener('change', updateShippingOptionUI);

    // Estado inicial al cargar
    updateShippingOptionUI();

    // Refresco cada 60s (como en el original)
    setInterval(updateShippingOptionUI, 60 * 1000);


    /**
     * fill summary
     */

    function fillSummary() {
        if (summaryEmailEl) summaryEmailEl.textContent = document.getElementById('email')?.value || '';
        if (summaryPhoneEl) summaryPhoneEl.textContent = document.getElementById('phone')?.value || '';

        // Texto del método de envío
        let shippingText = 'A definir';
        if (selectedShipmentMethod === 'take-away') {
            shippingText = 'Retiro en CABA';
        } else if (selectedShipmentMethod === 'andreani') {
            shippingText = 'Andreani Estandar “Envío a domicilio”';
        } else if (selectedShipmentMethod === 'FLEX') {
            shippingText = arrivesTitleEl?.textContent || 'Envío rápido';
        }

        if (summaryShippingMethodEl) summaryShippingMethodEl.textContent = shippingText;

        // Dirección
        const street = document.getElementById('street')?.value || '';
        const number = document.getElementById('number')?.value || '';
        const apartment = document.getElementById('apartment')?.value || '';
        const localityText = localityEl?.options[localityEl.selectedIndex]?.text || '';
        const provinceText = provinceEl?.options[provinceEl.selectedIndex]?.text || '';
        const zip = document.getElementById('zip_code')?.value || '';

        let addressLine = '';
        if (street || number) {
            addressLine = street;
            if (number) addressLine += ' ' + number;
            if (apartment) addressLine += ', ' + apartment;
        }

        if (selectedShipmentMethod === 'take-away') {
            if (summaryAddressEl) summaryAddressEl.textContent = 'Retiro en persona';
            if (summaryCityZipEl) summaryCityZipEl.textContent = '';
        } else {
            if (summaryAddressEl) summaryAddressEl.textContent = addressLine;
            if (summaryCityZipEl) summaryCityZipEl.textContent =
                `${localityText}${localityText && provinceText ? ', ' : ''}${provinceText}${zip ? ' (CP ' + zip + ')' : ''}`;
        }
    }


    // === Toggle "Ver detalles de mi compra" ===
    const checkoutSummaryToggle = document.getElementById('checkoutSummaryToggle');
    const checkoutSummaryBody = document.getElementById('checkoutSummaryBody');
    const checkoutSummaryChevron = document.getElementById('checkoutSummaryChevron');

    checkoutSummaryToggle?.addEventListener('click', () => {
        if (!checkoutSummaryBody) return;
        const isOpen = checkoutSummaryBody.classList.toggle('is-open');
        if (checkoutSummaryChevron) {
            checkoutSummaryChevron.classList.toggle('rotated', isOpen);
        }
    });

});
