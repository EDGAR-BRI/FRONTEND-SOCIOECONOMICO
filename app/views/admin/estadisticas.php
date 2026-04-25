<?php
    $statsView = isset($stats_view) ? trim((string)$stats_view) : 'resumen';
    if ($statsView === '') {
        $statsView = 'resumen';
    }

    $filters = isset($filters) && is_array($filters) ? $filters : [];
    $from = isset($filters['from']) ? (string)$filters['from'] : '';
    $to = isset($filters['to']) ? (string)$filters['to'] : '';

    $kpisData = isset($kpis) && is_array($kpis) ? $kpis : [];
    $totalEncuestas = isset($kpisData['total_encuestas']) ? (int)$kpisData['total_encuestas'] : 0;
    $promedioDiario = isset($kpisData['promedio_diario']) ? (float)$kpisData['promedio_diario'] : 0;
    $maxDia = isset($kpisData['max_dia']) ? (int)$kpisData['max_dia'] : 0;
    $diasRango = isset($kpisData['dias']) ? (int)$kpisData['dias'] : 0;

    $chartsData = isset($charts) && is_array($charts) ? $charts : [];
    $timeline = isset($chartsData['timeline']) && is_array($chartsData['timeline']) ? $chartsData['timeline'] : ['labels' => [], 'values' => []];
    $estratos = isset($chartsData['estratos']) && is_array($chartsData['estratos']) ? $chartsData['estratos'] : [];
    $carreras = isset($chartsData['carreras']) && is_array($chartsData['carreras']) ? $chartsData['carreras'] : [];

    $timelineLabels = isset($timeline['labels']) && is_array($timeline['labels']) ? $timeline['labels'] : [];
    $timelineValues = isset($timeline['values']) && is_array($timeline['values']) ? $timeline['values'] : [];

    $estratosLabels = array_keys($estratos);
    $estratosValues = array_values($estratos);

    $carrerasLabels = array_keys($carreras);
    $carrerasValues = array_values($carreras);

    $promedioFmt = number_format($promedioDiario, 1, ',', '.');
?>

<div class="bg-white rounded-lg shadow-sm border p-8 mb-8">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Estadísticas</h3>
            <p class="text-sm text-gray-500">Maquetación con datos mock (luego se conecta a API).</p>
        </div>

        <form method="GET" action="<?php echo BASE_URL; ?>/admin/estadisticas" class="flex flex-col sm:flex-row gap-3">
            <input type="hidden" name="vista" value="<?php echo htmlspecialchars($statsView); ?>" />
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Desde</label>
                <input type="date" name="from" value="<?php echo htmlspecialchars($from); ?>" class="w-full sm:w-44 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-200" />
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Hasta</label>
                <input type="date" name="to" value="<?php echo htmlspecialchars($to); ?>" class="w-full sm:w-44 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-200" />
            </div>
            <div class="sm:pb-0">
                <button type="submit" class="mt-5 sm:mt-0 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
                    Aplicar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8 mb-10">
    <div class="bg-white rounded-lg shadow-sm p-7 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full text-blue-500 mr-4">
                <i class="fas fa-file-invoice text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total encuestas (rango)</p>
                <h3 class="text-2xl font-bold text-gray-800"><?php echo number_format($totalEncuestas, 0, ',', '.'); ?></h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-7 border-l-4 border-green-500 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-full text-green-500 mr-4">
                <i class="fas fa-chart-line text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Promedio / día</p>
                <h3 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($promedioFmt); ?></h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-7 border-l-4 border-yellow-500 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-full text-yellow-500 mr-4">
                <i class="fas fa-bolt text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Máximo en un día</p>
                <h3 class="text-2xl font-bold text-gray-800"><?php echo number_format($maxDia, 0, ',', '.'); ?></h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-7 border-l-4 border-gray-400 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="p-3 bg-gray-100 rounded-full text-gray-600 mr-4">
                <i class="fas fa-calendar-days text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Días en el rango</p>
                <h3 class="text-2xl font-bold text-gray-800"><?php echo number_format($diasRango, 0, ',', '.'); ?></h3>
            </div>
        </div>
    </div>

</div>

<?php if ($statsView === 'resumen'): ?>
    <div class="bg-white rounded-lg shadow-sm border p-7">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Encuestas creadas por día</h3>
            <div class="text-xs text-gray-500">Línea</div>
        </div>
        <div class="w-full" style="height: 380px;">
            <canvas id="chartTimeline"></canvas>
        </div>
    </div>
<?php elseif ($statsView === 'estratos'): ?>
    <div class="bg-white rounded-lg shadow-sm border p-7">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Estratos (1–5)</h3>
            <div class="text-xs text-gray-500">% y totales</div>
        </div>
        <div class="w-full" style="height: 360px;">
            <canvas id="chartEstratos"></canvas>
        </div>
    </div>
<?php elseif ($statsView === 'carreras'): ?>
    <div class="bg-white rounded-lg shadow-sm border p-7">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Encuestas por carreras</h3>
            <div class="text-xs text-gray-500">% y totales</div>
        </div>
        <div class="w-full" style="height: 360px;">
            <canvas id="chartCarreras"></canvas>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const totalEncuestas = <?php echo (int)$totalEncuestas; ?>;

    const estratosLabels = <?php echo json_encode($estratosLabels, JSON_UNESCAPED_UNICODE); ?>;
    const estratosValues = <?php echo json_encode($estratosValues, JSON_UNESCAPED_UNICODE); ?>;

    const carrerasLabels = <?php echo json_encode($carrerasLabels, JSON_UNESCAPED_UNICODE); ?>;
    const carrerasValues = <?php echo json_encode($carrerasValues, JSON_UNESCAPED_UNICODE); ?>;

    const timelineLabels = <?php echo json_encode($timelineLabels, JSON_UNESCAPED_UNICODE); ?>;
    const timelineValues = <?php echo json_encode($timelineValues, JSON_UNESCAPED_UNICODE); ?>;

    const pct = (value, total) => {
        if (!total || total <= 0) return '0%';
        return ((value / total) * 100).toFixed(1).replace('.', ',') + '%';
    };

    // Colores basados en tokens Tailwind (ver tailwind.config.js)
    const colors = {
        primary500: '#3b82f6',
        primary600: '#2563eb',
        primary700: '#1d4ed8',
        red500: '#ef4444',
        amber500: '#f59e0b',
        gray600: '#4b5563'
    };

    // Estratos: Donut (mejor para %)
    const ctxEstratos = document.getElementById('chartEstratos');
    if (ctxEstratos) {
        new Chart(ctxEstratos, {
            type: 'doughnut',
            data: {
                labels: estratosLabels.map(e => 'Estrato ' + e),
                datasets: [{
                    data: estratosValues,
                    backgroundColor: [
                        colors.primary500,
                        colors.primary600,
                        colors.primary700,
                        colors.amber500,
                        colors.red500
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const v = context.parsed || 0;
                                return `${context.label}: ${v} (${pct(v, totalEncuestas)})`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Carreras: Barra horizontal (mejor para varias categorías)
    const ctxCarreras = document.getElementById('chartCarreras');
    if (ctxCarreras) {
        new Chart(ctxCarreras, {
            type: 'bar',
            data: {
                labels: carrerasLabels,
                datasets: [{
                    label: 'Encuestas',
                    data: carrerasValues,
                    backgroundColor: colors.primary500,
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: { color: colors.gray600 },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    y: {
                        ticks: { color: colors.gray600 },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const v = context.parsed.x || 0;
                                return `${v} (${pct(v, totalEncuestas)})`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Timeline: línea por día
    const ctxTimeline = document.getElementById('chartTimeline');
    if (ctxTimeline) {
        new Chart(ctxTimeline, {
            type: 'line',
            data: {
                labels: timelineLabels,
                datasets: [{
                    label: 'Encuestas',
                    data: timelineValues,
                    borderColor: colors.primary600,
                    backgroundColor: 'rgba(37, 99, 235, 0.12)',
                    fill: true,
                    tension: 0.25,
                    pointRadius: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: { maxTicksLimit: 8, color: colors.gray600 },
                        grid: { display: false }
                    },
                    y: {
                        ticks: { color: colors.gray600 },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
})();
</script>
