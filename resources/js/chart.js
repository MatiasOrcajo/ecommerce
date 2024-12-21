import Chart from 'chart.js/auto';
import axios from 'axios';

async function fetchSalesData() {
    const salesData = [];
    const months = [];

    try {
        const response = await axios.get('/api/sales');
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

async function renderChart() {
    // Esperar a que los datos sean cargados
    const data = await fetchSalesData();

    // Configuración del gráfico
    const config = {
        type: 'bar',
        data: {
            labels: data.months, // Etiquetas obtenidas de la API
            datasets: [{
                label: 'Ventas por Mes',
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
                            return `Ventas: ${context.raw}`;
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
                        text: 'Ventas'
                    }
                }
            }
        }
    };

    // Renderizar el gráfico
    const ctx = document.getElementById('chartjs-line').getContext('2d');
    new Chart(ctx, config);
}

// Llamar a la función para renderizar el gráfico
renderChart();

