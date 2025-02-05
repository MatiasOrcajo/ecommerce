import Chart from 'chart.js/auto';
import axios from "axios";

let visitorsChartInstance = null; // Variable para almacenar la instancia del gráfico


/**
 * Formats a given number to two decimal places and converts it to a localized string
 * using the 'es-ES' locale.
 *
 * @param {number} number - The number to be formatted.
 * @return {string} The formatted number as a localized string.
 */
function formatNumber(number) {
    return Number(number.toFixed(2)).toLocaleString('es-ES')
}


async function fetchVisitorsData(routeToFetch = '/api/visitors?filter=today') {
    const visitorsPrimaryData = [];
    const visitorsSecondaryData = [];
    const time = [];
    const secondaryDataTime = [];
    let primaryDataValueCounter = 0;
    let secondaryDataValueCounter = 0;

    try {

        const response = await axios.get(routeToFetch);
        const primary = JSON.parse(response.data.primary);
        const secondary = JSON.parse(response.data.secondary);

        Object.entries(primary).forEach(([key, value]) => {
            visitorsPrimaryData.push(value);
            time.push(key);
            primaryDataValueCounter += value;
        })

        Object.entries(secondary).forEach(([key, value]) => {
            visitorsSecondaryData.push(value);
            secondaryDataTime.push(key);
            secondaryDataValueCounter += value;

        });

        let percentageChange = ((primaryDataValueCounter / secondaryDataValueCounter) * 100) - 100;

        const selector = $("#total-visits-percentage-change-difference");

        if (percentageChange >= 0) {
            selector.html("&#8593;" + formatNumber(percentageChange) + "%");

            selector.css("color", "green");
        } else {
            selector.html("&#8595;" + formatNumber(percentageChange) + "%");

            selector.css("color", "red");
        }

        $("#total-visits-amount").html(formatNumber(primaryDataValueCounter));
        $("#period-1-total-visits").html(formatNumber(primaryDataValueCounter));
        $("#period-2-total-visits").html(formatNumber(secondaryDataValueCounter));


    } catch (error) {
        alert('Unable to fetch visitors data. Please try again!');
        console.error('Error en la petición:', error);
    }

    return {visitorsPrimaryData, visitorsSecondaryData, time, secondaryDataTime};
}

export async function renderVisitorsChart(routeToFetch = '/api/visitors?filter=today') {
    // Esperar a que los datos sean cargados
    const data = await fetchVisitorsData(routeToFetch);

    // Destruir el gráfico anterior si existe
    if (visitorsChartInstance) {
        visitorsChartInstance.destroy();
    }

    // Configuración del gráfico
    const config = {
        type: 'line',
        data: {
            labels: data.time, // Etiquetas obtenidas de la API
            datasets: [
                {
                    label: 'Visitas',
                    data: data.visitorsPrimaryData, // Datos obtenidos de la API
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.4 // Suavizado de la línea
                },
                {
                    label: `Mismo período anterior`,
                    data: data.visitorsSecondaryData,
                    borderColor: 'rgba(183, 176, 180, 0.8)',
                    backgroundColor: 'rgba(183, 176, 180, 1)',
                    borderWidth: 2,
                    tension: 0.6 // Suavizado de la línea
                }
            ]
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
                                return `${context.dataset.label}: ${context.raw} (${customDate})`;
                            }

                            // Por defecto, mostrar los datos normales para "Facturación"
                            return `${context.dataset.label}: ${context.raw}`;
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
                        text: 'Visitas'
                    }
                }
            }
        }
    };

    // Renderizar el gráfico
    const ctx = document.getElementById('visitors-chart').getContext('2d');
    visitorsChartInstance = new Chart(ctx, config); // Guardar la nueva instancia del gráfico
}

// Llamar a la función para renderizar el gráfico
renderVisitorsChart();



