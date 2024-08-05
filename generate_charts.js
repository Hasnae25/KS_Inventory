const { ChartJSNodeCanvas } = require('chartjs-node-canvas');
const fs = require('fs');

const width = 800; // Width of the chart
const height = 600; // Height of the chart

const chartJSNodeCanvas = new ChartJSNodeCanvas({ width, height });

const equipmentData = JSON.parse(process.argv[2] || '[]');
const affectationData = JSON.parse(process.argv[3] || '[]');
const scrapData = JSON.parse(process.argv[4] || '[]');

// Function to generate a chart
const generateChart = async (data, config) => {
    try {
        const buffer = await chartJSNodeCanvas.renderToBuffer(config);
        fs.writeFileSync(data.fileName, buffer);
        console.log(`Chart saved as ${data.fileName}`);
    } catch (error) {
        console.error(`Error generating chart for ${data.fileName}:`, error);
    }
};

// Configuration for each chart
const equipmentConfig = {
    type: 'bar',
    data: {
        labels: equipmentData.map(item => item['st-code']),
        datasets: [{
            label: 'Quantities',
            data: equipmentData.map(item => item['st-qte']),
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
};

const affectationConfig = {
    type: 'pie',
    data: {
        labels: affectationData.map(item => item['st-affectation']),
        datasets: [{
            label: 'Quantities by Affectation',
            data: affectationData.map(item => item['total_quantity']),
            backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
            borderWidth: 1
        }]
    }
};

const scrapConfig = {
    type: 'line',
    data: {
        labels: scrapData.map(item => item['reason']),
        datasets: [{
            label: 'Scrap Quantities',
            data: scrapData.map(item => item['total_quantity']),
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
};

// Generate all charts
generateChart({ fileName: 'equipmentChart.png' }, equipmentConfig);
generateChart({ fileName: 'affectationChart.png' }, affectationConfig);
generateChart({ fileName: 'scrapChart.png' }, scrapConfig);
