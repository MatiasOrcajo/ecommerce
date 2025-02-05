import Chart from 'chart.js/auto';
import axios from 'axios';

let chartInstance = null; // Variable para almacenar la instancia del gráfico


/**
 * Formats a given number to two decimal places and converts it to a localized string
 * using the 'es-ES' locale.
 *
 * @param {number} number - The number to be formatted.
 * @return {string} The formatted number as a localized string.
 */
function formatNumber(number){
    return Number(number.toFixed(2)).toLocaleString('es-ES')
}


/**
 * Fetches sales data from the API and processes it into separate arrays for sales data and corresponding time.
 *
 * @return {Promise<{salesPrimaryData: Array, time: Array}>} A promise that resolves to an object containing the sales data and time.
 */
async function fetchSalesData(routeToFetch = '/api/sales?filter=today') {

    const salesPrimaryData = [];
    const salesSecondaryData = [];
    const time = [];
    const secondaryDataTime = [];
    let primaryDataValueCounter = 0;
    let secondaryDataValueCounter = 0;

    try {
        const response = await axios.get(routeToFetch);
        const primary = JSON.parse(response.data.primary);
        const secondary = JSON.parse(response.data.secondary);

        Object.entries(primary).forEach(([key, value]) => {
            salesPrimaryData.push(value);
            time.push(key);
            primaryDataValueCounter += value;
        })
        Object.entries(secondary).forEach(([key, value]) => {
            salesSecondaryData.push(value);
            secondaryDataTime.push(key);
            secondaryDataValueCounter += value;

        });

        let percentageChange = ((primaryDataValueCounter / secondaryDataValueCounter)*100) - 100;
        const selector = $("#total-billing-percentage-change-difference");

        if(percentageChange >= 0) {
            selector.html("&#8593;"+ formatNumber(percentageChange) + "%");

            selector.css("color", "green");
        }
        else {
            selector.html("&#8595;"+ formatNumber(percentageChange) + "%");

            selector.css("color", "red");
        }

        $("#total-billing-amount").html("ARS "+formatNumber(primaryDataValueCounter));
        $("#period-1-total-billing").html("ARS "+formatNumber(primaryDataValueCounter));
        $("#period-2-total-billing").html("ARS "+formatNumber(secondaryDataValueCounter));


    } catch (error) {
        alert('Unable to fetch sales data. Please try again!');
        console.error('Error en la petición:', error);
    }

    return {salesPrimaryData, time, secondaryDataTime, salesSecondaryData};
}

/**
 * Renders a sales chart using Chart.js based on the fetched sales data.
 *
 * @return {Promise<void>} A promise that resolves when the sales chart has been successfully rendered.
 */
async function renderSalesChart(routeToFetch = '/api/sales?filter=today') {
    // Esperar a que los datos sean cargados
    const data = await fetchSalesData(routeToFetch);

    // Destruir el gráfico anterior si existe
    if (chartInstance) {
        chartInstance.destroy();
    }

    // Configuración del gráfico
    const config = {
        type: 'bar',
        data: {
            labels: data.time, // Etiquetas obtenidas de la API
            datasets: [
                {
                    label: 'Facturación',
                    data: data.salesPrimaryData, // Datos obtenidos de la API
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 3,
                    tension: 0.3 // Suavizado de la línea
                },
                {
                    label: `Mismo período anterior`,
                    data: data.salesSecondaryData,
                    borderColor: 'rgba(183, 176, 180, 0.8)',
                    backgroundColor: 'rgba(183, 176, 180, 1)',
                    borderWidth: 2,
                    tension: 0.6 // Suavizado de la línea
                }
            ]
        },
        interaction: {
            mode: 'index',
            intersect: false
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            // Mostrar una fecha personalizada para "Mismo período anterior"
                            if (context.dataset.label === 'Mismo período anterior') {
                                const previousDates = data.secondaryDataTime; // Array con las fechas personalizadas
                                const index = context.dataIndex;
                                const customDate = previousDates[index] || 'Fecha no disponible';
                                return `${context.dataset.label}: $${context.raw} (${customDate})`;
                            }

                            // Por defecto, mostrar los datos normales para "Facturación"
                            return `${context.dataset.label}: $${context.raw}`;
                        },
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tiempo'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Facturación'
                    }
                }
            }
        }
    };

    // Renderizar el gráfico
    const ctx = document.getElementById('sales-chart').getContext('2d');
    chartInstance = new Chart(ctx, config); // Guardar la nueva instancia del gráfico
}

// Llamar a la función para renderizar el gráfico
renderSalesChart();


async function fetchVisitorsData() {
    const visitorsData = [];
    const time = [];

    try {
        const response = await axios.get('/api/visitors');
        Object.entries(response.data).forEach(([key, value]) => {
            visitorsData.push(value);
            time.push(key);
        });
    } catch (error) {
        alert('Unable to fetch visitors data. Please try again!');
        console.error('Error en la petición:', error);
    }

    return {visitorsData, time};
}


async function renderVisitorsChart() {
    // Esperar a que los datos sean cargados
    const data = await fetchVisitorsData();
    // Configuración del gráfico
    const config = {
        type: 'line',
        data: {
            labels: data.time, // Etiquetas obtenidas de la API
            datasets: [{
                label: 'Visitas por Mes',
                data: data.visitorsData, // Datos obtenidos de la API
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                tension: 0.4 // Suavizado de la línea
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `Visitas al sitio: ${context.raw}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Meses'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Visitas'
                    }
                }
            }
        }
    };

    // Renderizar el gráfico
    const ctx = document.getElementById('visitors-chart').getContext('2d');
    new Chart(ctx, config);
}

// Llamar a la función para renderizar el gráfico
renderVisitorsChart();


$("#time-filter").on("change", function (e) {
    // Obtener el valor seleccionado
    const selectedValue = $(this).val();

    renderSalesChart('/api/sales?filter=' + selectedValue);
});
