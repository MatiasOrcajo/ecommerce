import Chart from 'chart.js/auto';
import axios from 'axios';

let chartInstance = null; // Variable para almacenar la instancia del gráfico

/**
 * Fetches sales data from the API and processes it into separate arrays for sales data and corresponding months.
 *
 * @return {Promise<{salesData: Array, months: Array}>} A promise that resolves to an object containing the sales data and months.
 */
async function fetchSalesData(routeToFetch = '/api/sales') {
    const salesData = [];
    const months = [];

    try {
        const response = await axios.get(routeToFetch);
        Object.entries(response.data).forEach(([key, value]) => {
            salesData.push(value);
            months.push(key);
        });
    } catch (error) {
        alert('Unable to fetch sales data. Please try again!');
        console.error('Error en la petición:', error);
    }

    return { salesData, months };
}

/**
 * Renders a sales chart using Chart.js based on the fetched sales data.
 *
 * @return {Promise<void>} A promise that resolves when the sales chart has been successfully rendered.
 */
async function renderSalesChart(routeToFetch = '/api/sales') {
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
            labels: data.months, // Etiquetas obtenidas de la API
            datasets: [{
                label: 'Facturación por Mes',
                data: data.salesData, // Datos obtenidos de la API
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
                            return `Facturación: $${context.raw}`;
                        }
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
    const months = [];

    try {
        const response = await axios.get('/api/visitors');
        Object.entries(response.data).forEach(([key, value]) => {
            visitorsData.push(value);
            months.push(key);
        });
    } catch (error) {
        alert('Unable to fetch visitors data. Please try again!');
        console.error('Error en la petición:', error);
    }

    return { visitorsData, months };
}


async function renderVisitorsChart() {
    // Esperar a que los datos sean cargados
    const data = await fetchVisitorsData();
    // Configuración del gráfico
    const config = {
        type: 'line',
        data: {
            labels: data.months, // Etiquetas obtenidas de la API
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

    renderSalesChart('/api/sales?filter='+selectedValue);
});
