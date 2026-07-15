<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SingleStore Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; }

        /* HEADER */
        .header {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border-bottom: 1px solid #334155;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header h1 { font-size: 22px; font-weight: 700; color: #f8fafc; }
        .header h1 span { color: #6366f1; }
        .badge {
            background: #6366f1;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .live-dot {
            display: inline-block;
            width: 8px; height: 8px;
            background: #22c55e;
            border-radius: 50%;
            margin-right: 6px;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .container { padding: 28px 32px; }

        /* SECTION TITLE */
        .section-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #1e293b;
        }

        /* ANALYTICS CARDS */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }
        .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 20px;
            transition: transform 0.2s, border-color 0.2s;
        }
        .card:hover { transform: translateY(-2px); border-color: #6366f1; }
        .card-label { font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
        .card-value { font-size: 28px; font-weight: 700; color: #f8fafc; }
        .card-value.green { color: #22c55e; }
        .card-value.red { color: #ef4444; }
        .card-value.yellow { color: #f59e0b; }
        .card-value.purple { color: #a78bfa; }

        /* CHART + TABLE ROW */
        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 32px;
        }
        @media (max-width: 900px) { .row-2 { grid-template-columns: 1fr; } }

        .panel {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 20px;
        }
        .panel-title {
            font-size: 14px;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .icon { font-size: 16px; }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { text-align: left; padding: 8px 12px; color: #64748b; font-weight: 600; border-bottom: 1px solid #334155; }
        td { padding: 10px 12px; border-bottom: 1px solid #1e293b; color: #cbd5e1; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #0f172a; }

        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .status-active { background: #14532d; color: #4ade80; }
        .status-inactive { background: #450a0a; color: #f87171; }
        .status-online { background: #14532d; color: #4ade80; }
        .status-offline { background: #450a0a; color: #f87171; }

        /* CLUSTER HEALTH */
        .cluster-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 32px;
        }
        @media (max-width: 900px) { .cluster-grid { grid-template-columns: 1fr; } }

        /* TOPOLOGY */
        .topology-table { margin-bottom: 32px; }

        /* REFRESH BTN */
        .refresh-btn {
            background: #6366f1;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .refresh-btn:hover { background: #4f46e5; }

        .empty-state { color: #475569; font-size: 13px; text-align: center; padding: 20px; }

        .tag {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            background: #1e3a5f;
            color: #60a5fa;
        }
        .tag.ref { background: #1a3a2a; color: #4ade80; }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <h1>⚡ <span>SingleStore</span> Dashboard</h1>
    <div style="display:flex; align-items:center; gap:12px;">
        <span><span class="live-dot"></span>Live</span>
        <button class="refresh-btn" onclick="location.reload()">↻ Refresh</button>
        <span class="badge">Laravel 12</span>
    </div>
</div>

<div class="container">

    <!-- ===== 1. LIVE ANALYTICS ===== -->
    <div class="section-title">📊 Live Analytics Dashboard</div>
    <div class="cards-grid">
        <div class="card">
            <div class="card-label">Total Products</div>
            <div class="card-value purple">{{ $analytics['total_products'] }}</div>
        </div>
        <div class="card">
            <div class="card-label">Active</div>
            <div class="card-value green">{{ $analytics['active_products'] }}</div>
        </div>
        <div class="card">
            <div class="card-label">Inactive</div>
            <div class="card-value red">{{ $analytics['inactive_products'] }}</div>
        </div>
        <div class="card">
            <div class="card-label">Avg Price</div>
            <div class="card-value yellow">₹{{ number_format($analytics['average_price'], 0) }}</div>
        </div>
        <div class="card">
            <div class="card-label">Max Price</div>
            <div class="card-value">₹{{ number_format($analytics['max_price'] ?? 0, 0) }}</div>
        </div>
        <div class="card">
            <div class="card-label">Min Price</div>
            <div class="card-value">₹{{ number_format($analytics['min_price'] ?? 0, 0) }}</div>
        </div>
    </div>

    <!-- CHART + RECENT PRODUCTS -->
    <div class="row-2">
        <!-- Doughnut Chart -->
        <div class="panel">
            <div class="panel-title"><span class="icon">🍩</span> Product Status Distribution</div>
            <canvas id="statusChart" height="200"></canvas>
        </div>

        <!-- Recent Products Table -->
        <div class="panel">
            <div class="panel-title"><span class="icon">📦</span> Recent Products</div>
            @if($recentProducts->count())
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentProducts as $p)
                    <tr>
                        <td>#{{ $p->id }}</td>
                        <td>{{ $p->name }}</td>
                        <td>₹{{ number_format($p->price, 0) }}</td>
                        <td>
                            <span class="status-badge {{ $p->status ? 'status-active' : 'status-inactive' }}">
                                {{ $p->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">No products found. Add products via API.</div>
            @endif
        </div>
    </div>

    <!-- ===== 2. CLUSTER HEALTH MONITOR ===== -->
    <div class="section-title">🖥️ Multi-Node Cluster Health Monitor</div>
    <div class="cluster-grid">
        <!-- LEAVES -->
        <div class="panel">
            <div class="panel-title"><span class="icon">🌿</span> Leaf Nodes (SHOW LEAVES)</div>
            @if(count($leaves))
            <table>
                <thead>
                    <tr>
                        <th>Host</th>
                        <th>Port</th>
                        <th>State</th>
                        @if(isset($leaves[0]->Note)) <th>Note</th> @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaves as $leaf)
                    <tr>
                        <td>{{ $leaf->Host ?? ($leaf['Host'] ?? '-') }}</td>
                        <td>{{ $leaf->Port ?? ($leaf['Port'] ?? '-') }}</td>
                        <td>
                            <span class="status-badge status-online">
                                {{ $leaf->State ?? ($leaf['State'] ?? 'online') }}
                            </span>
                        </td>
                        @if(isset($leaves[0]->Note))
                        <td><span class="tag">{{ $leaf->Note ?? '-' }}</span></td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">No leaf nodes found.</div>
            @endif
        </div>

        <!-- AGGREGATORS -->
        <div class="panel">
            <div class="panel-title"><span class="icon">🔗</span> Aggregator Nodes (SHOW AGGREGATORS)</div>
            @if(count($aggregators))
            <table>
                <thead>
                    <tr>
                        <th>Host</th>
                        <th>Port</th>
                        <th>State</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aggregators as $agg)
                    <tr>
                        <td>{{ $agg->Host ?? ($agg['Host'] ?? '-') }}</td>
                        <td>{{ $agg->Port ?? ($agg['Port'] ?? '-') }}</td>
                        <td>
                            <span class="status-badge status-online">
                                {{ $agg->State ?? ($agg['State'] ?? 'online') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">No aggregator nodes found.</div>
            @endif
        </div>
    </div>

    <!-- ===== 3. SHARDING & TABLE TOPOLOGY ===== -->
    <div class="section-title">🗂️ Sharding &amp; Table Topology</div>
    <div class="panel topology-table">
        <div class="panel-title"><span class="icon">🔍</span> Database Tables (information_schema)</div>
        @if(count($topology))
        <table>
            <thead>
                <tr>
                    <th>Table Name</th>
                    <th>Engine</th>
                    <th>Rows (Est.)</th>
                    <th>Data Size</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topology as $t)
                <tr>
                    <td><strong>{{ $t->TABLE_NAME ?? '-' }}</strong></td>
                    <td>{{ $t->ENGINE ?? '-' }}</td>
                    <td>{{ number_format($t->TABLE_ROWS ?? 0) }}</td>
                    <td>{{ number_format(($t->DATA_LENGTH ?? 0) / 1024, 1) }} KB</td>
                    <td>
                        @php
                            $engine = strtolower($t->ENGINE ?? '');
                            $isRef = in_array($t->TABLE_NAME ?? '', ['migrations', 'cache', 'sessions', 'jobs']);
                        @endphp
                        <span class="tag {{ $isRef ? 'ref' : '' }}">
                            {{ $isRef ? 'Reference' : 'Sharded' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">No table topology data available.</div>
        @endif
    </div>

    <!-- ===== 4. QUERY PERFORMANCE ===== -->
    <div class="section-title">⚡ Query Performance Monitor</div>
    <div class="panel" style="margin-bottom: 32px;">
        <div class="panel-title"><span class="icon">📈</span> Price Distribution Chart</div>
        <canvas id="priceChart" height="100"></canvas>
    </div>

</div>

<script>
// Doughnut Chart - Product Status
const ctx1 = document.getElementById('statusChart').getContext('2d');
new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: ['Active', 'Inactive'],
        datasets: [{
            data: [{{ $analytics['active_products'] }}, {{ $analytics['inactive_products'] }}],
            backgroundColor: ['#22c55e', '#ef4444'],
            borderColor: ['#16a34a', '#dc2626'],
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { color: '#94a3b8', font: { size: 13 } } }
        }
    }
});

// Bar Chart - Price Stats
const ctx2 = document.getElementById('priceChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['Min Price', 'Avg Price', 'Max Price'],
        datasets: [{
            label: 'Price (₹)',
            data: [
                {{ $analytics['min_price'] ?? 0 }},
                {{ $analytics['average_price'] ?? 0 }},
                {{ $analytics['max_price'] ?? 0 }}
            ],
            backgroundColor: ['#6366f1', '#f59e0b', '#22c55e'],
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { color: '#94a3b8' } }
        },
        scales: {
            x: { ticks: { color: '#94a3b8' }, grid: { color: '#1e293b' } },
            y: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } }
        }
    }
});
</script>

</body>
</html>
