@php
$analyticsData = [
'monthly' => [
'labels' => $monthly->keys()->values()->all(),
'values' => $monthly->values()->values()->all(),
],

'clarity' => [
'labels' => collect($clarityDist)->keys()->values()->all(),
'values' => collect($clarityDist)->values()->values()->all(),
],

'origin' => [
'labels' => $origin->pluck('origin')->values()->all(),
'values' => $origin->pluck('count')->values()->all(),
],

'daily' => [
'labels' => $daily->keys()->values()->all(),
'values' => $daily->values()->values()->all(),
],
];
@endphp


<x-app-layout>

    {{-- KPI SUMMARY --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-slate-900 border border-slate-800 rounded-lg p-4">
            <p class="text-xs text-slate-400">Total Frequencies</p>
            <p class="text-2xl font-bold text-white">{{ $monthly->sum() }}</p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-lg p-4">
            <p class="text-xs text-slate-400">Low Clarity Signals</p>
            <p class="text-2xl font-bold text-red-400">
                {{ ($clarityDist['1x1'] ?? 0) + ($clarityDist['2x2'] ?? 0) }}
            </p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-lg p-4">
            <p class="text-xs text-slate-400">Active Origins</p>
            <p class="text-2xl font-bold text-blue-400">
                {{ $origin->count() }}
            </p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-lg p-4">
            <p class="text-xs text-slate-400">Peak Day</p>
            <p class="text-2xl font-bold text-yellow-400">
                {{ $daily->sortDesc()->keys()->first() }}
            </p>
        </div>
    </div>

    <h1 class="text-3xl font-bold text-slate-100 mb-6">
        📊 Frequency Analytics
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
            <h2 class="text-sm text-slate-300 mb-4">Monthly Activity</h2>
            <canvas id="monthlyChart" height="120"></canvas>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
            <h2 class="text-sm text-slate-300 mb-4">Clarity Distribution</h2>
            <canvas id="clarityChart" height="120"></canvas>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
            <h2 class="text-sm text-slate-300 mb-4">Top Origins</h2>
            <canvas id="originChart" height="120"></canvas>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
            <h2 class="text-sm text-slate-300 mb-4">Daily Activity</h2>
            <canvas id="dailyChart" height="120"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        console.log('Analytics JS loaded')

        // ✅ Blade → JS (DIRECT, NO JSON.parse)
        const analytics = @json($analyticsData)

        console.log('Analytics data:', analytics);

        const monthlyLabels = analytics.monthly.labels;
        const monthlyData = analytics.monthly.values;

        const clarityLabels = analytics.clarity.labels;
        const clarityData = analytics.clarity.values;

        const originLabels = analytics.origin.labels;
        const originData = analytics.origin.values;

        const dailyLabels = analytics.daily.labels;
        const dailyData = analytics.daily.values;

        Chart.defaults.color = '#cbd5f5';
        Chart.defaults.borderColor = '#334155';

        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Records',
                    data: monthlyData,
                    backgroundColor: 'rgba(59,130,246,0.6)'
                }]
            }
        });

        new Chart(document.getElementById('clarityChart'), {
            type: 'pie',
            data: {
                labels: clarityLabels,
                datasets: [{
                    data: clarityData,
                    backgroundColor: ['#ef4444', '#f97316', '#3b82f6', '#22c55e']
                }]
            }
        });

        new Chart(document.getElementById('originChart'), {
            type: 'bar',
            data: {
                labels: originLabels,
                datasets: [{
                    label: 'Count',
                    data: originData,
                    backgroundColor: 'rgba(14,165,233,0.6)'
                }]
            }
        });

        new Chart(document.getElementById('dailyChart'), {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Daily',
                    data: dailyData,
                    borderColor: '#38bdf8',
                    tension: 0.4
                }]
            }
        });
    </script>


</x-app-layout>