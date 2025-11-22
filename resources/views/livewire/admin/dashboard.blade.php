<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">Dashboard de Ventas Semanales</h1>

    <div class="bg-white shadow rounded-lg p-6">
        <canvas id="ventasChart" height="100"></canvas>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:load', function () {
        const ctx = document.getElementById('ventasChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($fechas),
                datasets: [{
                    label: 'Ventas Diarias',
                    data: @json($ventas),
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 50 }
                    }
                }
            }
        });
    });
</script>
