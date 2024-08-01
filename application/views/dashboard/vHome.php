<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Dashboard - Sistema SIMAHG">
    <meta name="author" content="SIMAHG Team">

    <title><?php echo isset($page_title) ? $page_title : 'Dashboard - SIMAHG'; ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url();?>bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?php echo base_url();?>bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h3 {
            color: white;
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .sidebar-header.collapsed h3 {
            font-size: 1rem;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-group {
            margin-bottom: 30px;
        }
        
        .menu-group-title {
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            padding: 0 20px 10px;
            letter-spacing: 0.5px;
        }
        
        .menu-item {
            display: block;
            color: #e2e8f0;
            text-decoration: none;
            padding: 12px 20px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            text-decoration: none;
            border-left-color: #3b82f6;
        }
        
        .menu-item.active {
            background-color: rgba(59, 130, 246, 0.2);
            border-left-color: #3b82f6;
            color: white;
        }
        
        .menu-item i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: 70px;
        }
        
        /* Top Bar */
        .topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .topbar-left {
            display: flex;
            align-items: center;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #64748b;
            margin-right: 20px;
            cursor: pointer;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #64748b;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        /* Content Area */
        .content-area {
            padding: 30px;
        }
        
        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .stat-title {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            margin: 0;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        
        .stat-change {
            font-size: 0.875rem;
            margin-top: 5px;
        }
        
        .stat-change.positive {
            color: #10b981;
        }
        
        .stat-change.negative {
            color: #ef4444;
        }
        
        /* Charts and Tables */
        .dashboard-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        
        .card-header {
            margin-bottom: 20px;
        }
        
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        
        .card-subtitle {
            color: #64748b;
            font-size: 0.875rem;
            margin-top: 5px;
        }
        
        /* Activity Table */
        .activity-table {
            width: 100%;
            margin-top: 15px;
        }
        
        .activity-table th,
        .activity-table td {
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        
        .activity-table th {
            color: #64748b;
            font-weight: 500;
            font-size: 0.875rem;
        }
        
        .activity-table td {
            color: #1e293b;
        }
        
        .user-activity {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .activity-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .dashboard-row {
                grid-template-columns: 1fr;
            }
            
            .content-area {
                padding: 20px;
            }
        }
        
        /* Loading States */
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            color: #64748b;
        }
        
        /* Logout Button */
        .logout-btn {
            background: none;
            border: none;
            color: #64748b;
            font-size: 1.1rem;
            cursor: pointer;
            padding: 5px;
        }
        
        .logout-btn:hover {
            color: #ef4444;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fa fa-shield"></i> <span class="sidebar-text">SIMAHG</span></h3>
        </div>
        
        <div class="sidebar-menu">
            <div class="menu-group">
                <div class="menu-group-title">Principal</div>
                <a href="<?php echo base_url('home'); ?>" class="menu-item active">
                    <i class="fa fa-dashboard"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </div>
            
            <?php if (isset($menu_items) && !empty($menu_items)): ?>
                <?php foreach ($menu_items as $modulo => $items): ?>
                    <div class="menu-group">
                        <div class="menu-group-title"><?php echo $modulo; ?></div>
                        <?php foreach ($items as $item): ?>
                            <a href="<?php echo base_url($item->url); ?>" class="menu-item">
                                <i class="fa <?php echo $item->icono; ?>"></i>
                                <span class="sidebar-text"><?php echo $item->nombre; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Bar -->
        <div class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fa fa-bars"></i>
                </button>
                <h1 class="page-title">Dashboard</h1>
            </div>
            
            <div class="topbar-right">
                <div class="user-menu">
                    <div class="user-avatar">
                        <?php echo substr($user_name, 0, 1); ?>
                    </div>
                    <div>
                        <div style="font-weight: 500;"><?php echo $user_name; ?></div>
                        <div style="font-size: 0.75rem; color: #64748b;"><?php echo $perfil_nombre; ?></div>
                    </div>
                    <button class="logout-btn" onclick="logout()" title="Cerrar Sesión">
                        <i class="fa fa-sign-out"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
            <!-- Stats Cards -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <p class="stat-title">Total Usuarios</p>
                            <h2 class="stat-value"><?php echo isset($stats['total_usuarios']) ? $stats['total_usuarios'] : '0'; ?></h2>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-change positive">
                        <i class="fa fa-arrow-up"></i> +5% desde el mes pasado
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <p class="stat-title">Usuarios Activos</p>
                            <h2 class="stat-value"><?php echo isset($stats['usuarios_activos']) ? $stats['usuarios_activos'] : '0'; ?></h2>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fa fa-user-check"></i>
                        </div>
                    </div>
                    <div class="stat-change positive">
                        <i class="fa fa-arrow-up"></i> +12% desde ayer
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <p class="stat-title">Total Perfiles</p>
                            <h2 class="stat-value"><?php echo isset($stats['total_perfiles']) ? $stats['total_perfiles'] : '0'; ?></h2>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fa fa-user-circle"></i>
                        </div>
                    </div>
                    <div class="stat-change">
                        <i class="fa fa-minus"></i> Sin cambios
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <p class="stat-title">Módulos Activos</p>
                            <h2 class="stat-value"><?php echo isset($stats['total_modulos']) ? $stats['total_modulos'] : '0'; ?></h2>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                            <i class="fa fa-cubes"></i>
                        </div>
                    </div>
                    <div class="stat-change positive">
                        <i class="fa fa-arrow-up"></i> +1 este mes
                    </div>
                </div>
            </div>
            
            <!-- Charts and Activity -->
            <div class="dashboard-row">
                <!-- Activity Chart -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Actividad del Sistema</h3>
                        <p class="card-subtitle">Usuarios activos por mes</p>
                    </div>
                    <canvas id="activityChart" height="300"></canvas>
                </div>
                
                <!-- Users by Profile Chart -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Distribución de Usuarios</h3>
                        <p class="card-subtitle">Por tipo de perfil</p>
                    </div>
                    <canvas id="profileChart" height="300"></canvas>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">Actividad Reciente</h3>
                    <p class="card-subtitle">Últimos accesos al sistema</p>
                </div>
                
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Perfil</th>
                            <th>Último Acceso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($recent_activities) && !empty($recent_activities)): ?>
                            <?php foreach ($recent_activities as $activity): ?>
                                <tr>
                                    <td>
                                        <div class="user-activity">
                                            <div class="activity-avatar">
                                                <?php echo substr($activity->nombre, 0, 1); ?>
                                            </div>
                                            <span><?php echo $activity->nombre . ' ' . $activity->apellidos; ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo $activity->perfil; ?></td>
                                    <td><?php echo $activity->ultimo_acceso ? date('d/m/Y H:i', strtotime($activity->ultimo_acceso)) : 'Nunca'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: #64748b;">No hay actividades recientes</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="<?php echo base_url();?>bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url();?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    
    <script>
        var base_url = "<?php echo base_url(); ?>";
        
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
        
        // Logout function
        function logout() {
            if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
                window.location.href = base_url + 'login/logout';
            }
        }
        
        // Load chart data
        function loadChartData() {
            $.ajax({
                url: base_url + 'home/getChartData',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    createActivityChart(data.monthly_activity);
                    createProfileChart(data.users_by_profile);
                },
                error: function() {
                    console.error('Error loading chart data');
                }
            });
        }
        
        // Create Activity Chart
        function createActivityChart(data) {
            const ctx = document.getElementById('activityChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(item => item.month),
                    datasets: [{
                        label: 'Usuarios Activos',
                        data: data.map(item => item.count),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
        
        // Create Profile Chart
        function createProfileChart(data) {
            const ctx = document.getElementById('profileChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.map(item => item.nombre),
                    datasets: [{
                        data: data.map(item => item.total),
                        backgroundColor: [
                            '#3b82f6',
                            '#10b981',
                            '#f59e0b',
                            '#8b5cf6',
                            '#ef4444'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        
        // Initialize on page load
        $(document).ready(function() {
            loadChartData();
        });
    </script>
</body>
</html>
