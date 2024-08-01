<?php
session_start();

// Incluir archivo común
require_once 'includes/config_common.php';

// Obtener conexión a BD
$pdo = getDBConnection();

$message = '';
$error = '';

// Procesar formularios
if ($_POST) {
    try {
        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'crear_mantenimiento') {
                $id_equipo = $_POST['id_equipo'];
                $id_tipo_mantenimiento = $_POST['id_tipo_mantenimiento'];
                $fecha_programada = $_POST['fecha_programada'];
                $descripcion_trabajo = trim($_POST['descripcion_trabajo']);
                $tecnico_responsable = trim($_POST['tecnico_responsable']);
                
                // Generar código único
                $codigo = 'MANT-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                
                $sql = "INSERT INTO mantenimientos (codigo, id_equipo, id_tipo_mantenimiento, fecha_programada, 
                        descripcion_trabajo, tecnico_responsable, creado_por) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $codigo, $id_equipo, $id_tipo_mantenimiento, $fecha_programada,
                    $descripcion_trabajo, $tecnico_responsable, $_SESSION['user_id']
                ]);
                
                $message = "Mantenimiento programado exitosamente. Código: $codigo";
            }
            
            if ($_POST['action'] == 'actualizar_estado') {
                $mantenimiento_id = $_POST['mantenimiento_id'];
                $nuevo_estado = $_POST['nuevo_estado'];
                $observaciones = trim($_POST['observaciones']);
                
                $campos_actualizacion = ['estado' => $nuevo_estado, 'actualizado_por' => $_SESSION['user_id']];
                
                if ($nuevo_estado == 'en_proceso') {
                    $campos_actualizacion['fecha_inicio'] = date('Y-m-d H:i:s');
                } elseif ($nuevo_estado == 'completado') {
                    $campos_actualizacion['fecha_fin'] = date('Y-m-d H:i:s');
                    $campos_actualizacion['trabajos_realizados'] = $observaciones;
                    
                    // Calcular próximo mantenimiento si es preventivo
                    if (isset($_POST['proximo_mantenimiento']) && $_POST['proximo_mantenimiento']) {
                        $campos_actualizacion['proximo_mantenimiento'] = $_POST['proximo_mantenimiento'];
                        
                        // Actualizar fecha próximo mantenimiento en el equipo
                        $stmt = $pdo->prepare("UPDATE equipos SET fecha_proximo_mantenimiento = ? WHERE id = (SELECT id_equipo FROM mantenimientos WHERE id = ?)");
                        $stmt->execute([$_POST['proximo_mantenimiento'], $mantenimiento_id]);
                    }
                }
                
                $set_clause = implode(', ', array_map(function($k) { return "$k = ?"; }, array_keys($campos_actualizacion)));
                $sql = "UPDATE mantenimientos SET $set_clause WHERE id = ?";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([...array_values($campos_actualizacion), $mantenimiento_id]);
                
                $message = "Estado del mantenimiento actualizado exitosamente";
            }
        }
        
    } catch (PDOException $e) {
        $error = "Error de base de datos: " . $e->getMessage();
    }
}

try {
    // Obtener equipos para el formulario
    $stmt = $pdo->query("SELECT id, codigo, nombre FROM equipos WHERE estado IN ('operativo', 'mantenimiento') ORDER BY codigo");
    $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener tipos de mantenimiento
    $stmt = $pdo->query("SELECT * FROM tipos_mantenimiento WHERE estado = 1 ORDER BY nombre");
    $tipos_mantenimiento = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener mantenimientos con filtros
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
    $estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : '';
    
    $sql = "SELECT m.*, e.codigo as equipo_codigo, e.nombre as equipo_nombre, 
            tm.nombre as tipo_mantenimiento_nombre 
            FROM mantenimientos m 
            LEFT JOIN equipos e ON m.id_equipo = e.id 
            LEFT JOIN tipos_mantenimiento tm ON m.id_tipo_mantenimiento = tm.id 
            WHERE 1=1";
    
    $params = [];
    
    if (!empty($filtro)) {
        $sql .= " AND (m.codigo LIKE ? OR e.codigo LIKE ? OR e.nombre LIKE ? OR m.tecnico_responsable LIKE ?)";
        $filtro_param = "%$filtro%";
        $params = array_merge($params, [$filtro_param, $filtro_param, $filtro_param, $filtro_param]);
    }
    
    if (!empty($estado_filtro)) {
        $sql .= " AND m.estado = ?";
        $params[] = $estado_filtro;
    }
    
    $sql .= " ORDER BY m.fecha_programada DESC, m.fecha_creacion DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $mantenimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Estadísticas de mantenimientos
    $stmt = $pdo->query("SELECT estado, COUNT(*) as total FROM mantenimientos GROUP BY estado");
    $estadisticas = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    // Mantenimientos próximos (próximos 30 días)
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM mantenimientos 
                        WHERE estado = 'programado' 
                        AND fecha_programada <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)");
    $proximos_30_dias = $stmt->fetch()['total'];
    
    // Mantenimientos vencidos
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM mantenimientos 
                        WHERE estado = 'programado' 
                        AND fecha_programada < CURDATE()");
    $vencidos = $stmt->fetch()['total'];
    
    // Equipos que necesitan mantenimiento
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM equipos 
                        WHERE fecha_proximo_mantenimiento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) 
                        AND estado = 'operativo'");
    $equipos_necesitan_mant = $stmt->fetch()['total'];
    
} catch (PDOException $e) {
    $error = "Error de base de datos: " . $e->getMessage();
    $equipos = [];
    $tipos_mantenimiento = [];
    $mantenimientos = [];
    $estadisticas = [];
    $proximos_30_dias = 0;
    $vencidos = 0;
    $equipos_necesitan_mant = 0;
}

function formatEstadoMantenimiento($estado) {
    switch($estado) {
        case 'programado': return 'Programado';
        case 'en_proceso': return 'En Proceso';
        case 'completado': return 'Completado';
        case 'cancelado': return 'Cancelado';
        default: return ucfirst($estado);
    }
}

function getEstadoColorMantenimiento($estado) {
    switch($estado) {
        case 'programado': return 'info';
        case 'en_proceso': return 'warning';
        case 'completado': return 'success';
        case 'cancelado': return 'danger';
        default: return 'secondary';
    }
}

function calcularProximoMantenimiento($fecha_actual, $frecuencia_dias) {
    if ($frecuencia_dias) {
        return date('Y-m-d', strtotime($fecha_actual . " + $frecuencia_dias days"));
    }
    return null;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Mantenimientos - SIMAHG</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <?php renderEstilosComunes(); ?>
    <style>
        .maintenance-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
        }
        .stat-card {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            text-align: center;
            padding: 20px;
        }
        .alert-card {
            background: linear-gradient(135deg, #ff7b7b 0%, #667eea 100%);
            color: white;
            text-align: center;
            padding: 20px;
        }
        .warning-card {
            background: linear-gradient(135deg, #ffa726 0%, #ffcc02 100%);
            color: white;
            text-align: center;
            padding: 20px;
        }
        .success-card {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php renderNavbar('mantenimientos'); ?>

    <div class="container mt-4">
        <!-- Header -->
        <div class="maintenance-header mb-4">
            <h2><i class="fa fa-wrench"></i> Gestión de Mantenimientos</h2>
            <p>Control y seguimiento de mantenimientos preventivos y correctivos</p>
        </div>

        <!-- Mensajes -->
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-check"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Estadísticas -->
        <div class="row">
            <div class="col-md-3">
                <div class="card <?php echo $vencidos > 0 ? 'alert-card' : 'stat-card'; ?>">
                    <h4><i class="fa fa-exclamation-triangle"></i></h4>
                    <h3><?php echo $vencidos; ?></h3>
                    <p>Vencidos</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card <?php echo $proximos_30_dias > 0 ? 'warning-card' : 'stat-card'; ?>">
                    <h4><i class="fa fa-clock-o"></i></h4>
                    <h3><?php echo $proximos_30_dias; ?></h3>
                    <p>Próximos 30 días</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <h4><i class="fa fa-play"></i></h4>
                    <h3><?php echo $estadisticas['en_proceso'] ?? 0; ?></h3>
                    <p>En Proceso</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card success-card">
                    <h4><i class="fa fa-check"></i></h4>
                    <h3><?php echo $estadisticas['completado'] ?? 0; ?></h3>
                    <p>Completados</p>
                </div>
            </div>
        </div>

        <!-- Equipos que necesitan mantenimiento -->
        <?php if ($equipos_necesitan_mant > 0): ?>
        <div class="alert alert-warning">
            <h5><i class="fa fa-exclamation-triangle"></i> Atención: <?php echo $equipos_necesitan_mant; ?> equipo(s) necesitan mantenimiento próximamente</h5>
            <p>Hay equipos que requieren mantenimiento en los próximos 30 días. Revise la programación.</p>
        </div>
        <?php endif; ?>

        <!-- Filtros y Acciones -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fa fa-search"></i> Filtros de Mantenimiento</h5>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrearMantenimiento">
                            <i class="fa fa-plus"></i> Programar Mantenimiento
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="form-inline">
                    <div class="form-group mr-3">
                        <input type="text" class="form-control" name="filtro" placeholder="Buscar por código, equipo o técnico..." 
                               value="<?php echo htmlspecialchars($filtro); ?>" style="width: 350px;">
                    </div>
                    <div class="form-group mr-3">
                        <select name="estado" class="form-control">
                            <option value="">Todos los estados</option>
                            <option value="programado" <?php echo $estado_filtro == 'programado' ? 'selected' : ''; ?>>Programado</option>
                            <option value="en_proceso" <?php echo $estado_filtro == 'en_proceso' ? 'selected' : ''; ?>>En Proceso</option>
                            <option value="completado" <?php echo $estado_filtro == 'completado' ? 'selected' : ''; ?>>Completado</option>
                            <option value="cancelado" <?php echo $estado_filtro == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info mr-2">
                        <i class="fa fa-search"></i> Filtrar
                    </button>
                    <a href="mantenimientos.php" class="btn btn-secondary">
                        <i class="fa fa-refresh"></i> Limpiar
                    </a>
                </form>
            </div>
        </div>

        <!-- Lista de Mantenimientos -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-list"></i> Lista de Mantenimientos</h5>
            </div>
            <div class="card-body">
                <?php if (count($mantenimientos) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Código</th>
                                <th>Equipo</th>
                                <th>Tipo</th>
                                <th>Fecha Programada</th>
                                <th>Técnico</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mantenimientos as $mantenimiento): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($mantenimiento['codigo']); ?></strong></td>
                                <td>
                                    <?php echo htmlspecialchars($mantenimiento['equipo_codigo']); ?>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($mantenimiento['equipo_nombre']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($mantenimiento['tipo_mantenimiento_nombre']); ?></td>
                                <td>
                                    <?php 
                                    $fecha_prog = strtotime($mantenimiento['fecha_programada']);
                                    $hoy = time();
                                    $diferencia_dias = ($fecha_prog - $hoy) / (24 * 60 * 60);
                                    
                                    if ($diferencia_dias < 0 && $mantenimiento['estado'] == 'programado'): ?>
                                        <span class="text-danger"><strong><?php echo date('d/m/Y', $fecha_prog); ?></strong></span>
                                        <br><small class="text-danger">Vencido</small>
                                    <?php elseif ($diferencia_dias <= 7 && $mantenimiento['estado'] == 'programado'): ?>
                                        <span class="text-warning"><strong><?php echo date('d/m/Y', $fecha_prog); ?></strong></span>
                                        <br><small class="text-warning">Próximo</small>
                                    <?php else: ?>
                                        <?php echo date('d/m/Y', $fecha_prog); ?>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($mantenimiento['tecnico_responsable']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo getEstadoColorMantenimiento($mantenimiento['estado']); ?>">
                                        <?php echo formatEstadoMantenimiento($mantenimiento['estado']); ?>
                                    </span>
                                    <?php if ($mantenimiento['fecha_inicio']): ?>
                                        <br><small class="text-muted">Inicio: <?php echo date('d/m/Y', strtotime($mantenimiento['fecha_inicio'])); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-info" onclick="verDetalle(<?php echo $mantenimiento['id']; ?>)" title="Ver Detalle">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <?php if ($mantenimiento['estado'] != 'completado' && $mantenimiento['estado'] != 'cancelado'): ?>
                                            <button class="btn btn-sm btn-warning" onclick="cambiarEstado(<?php echo $mantenimiento['id']; ?>, '<?php echo $mantenimiento['estado']; ?>')" title="Cambiar Estado">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-success" onclick="imprimirOrden(<?php echo $mantenimiento['id']; ?>)" title="Imprimir Orden">
                                            <i class="fa fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fa fa-wrench fa-3x text-muted"></i>
                    <h5 class="mt-3">No se encontraron mantenimientos</h5>
                    <p class="text-muted">No hay mantenimientos registrados con los filtros seleccionados.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Navegación rápida -->
        <div class="card">
            <div class="card-body text-center">
                <a href="dashboard.php" class="btn btn-primary">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
                <a href="equipos.php" class="btn btn-warning">
                    <i class="fa fa-cogs"></i> Gestión de Equipos
                </a>
                <a href="epp_gestion.php" class="btn btn-success">
                    <i class="fa fa-shield"></i> Gestión de EPPs
                </a>
                <a href="reportes.php" class="btn btn-info">
                    <i class="fa fa-chart-bar"></i> Reportes
                </a>
            </div>
        </div>
    </div>

    <!-- Modal Crear Mantenimiento -->
    <div class="modal fade" id="modalCrearMantenimiento" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-plus"></i> Programar Nuevo Mantenimiento</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="crear_mantenimiento">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Equipo *</label>
                                    <select name="id_equipo" class="form-control" required>
                                        <option value="">Seleccionar equipo...</option>
                                        <?php foreach ($equipos as $equipo): ?>
                                            <option value="<?php echo $equipo['id']; ?>"><?php echo htmlspecialchars($equipo['codigo'] . ' - ' . $equipo['nombre']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo de Mantenimiento *</label>
                                    <select name="id_tipo_mantenimiento" class="form-control" required onchange="calcularProximaFecha(this)">
                                        <option value="">Seleccionar tipo...</option>
                                        <?php foreach ($tipos_mantenimiento as $tipo): ?>
                                            <option value="<?php echo $tipo['id']; ?>" data-frecuencia="<?php echo $tipo['frecuencia_dias']; ?>">
                                                <?php echo htmlspecialchars($tipo['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha Programada *</label>
                                    <input type="date" class="form-control" name="fecha_programada" min="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Técnico Responsable *</label>
                                    <input type="text" class="form-control" name="tecnico_responsable" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Descripción del Trabajo</label>
                            <textarea class="form-control" name="descripcion_trabajo" rows="4" placeholder="Detalle de los trabajos a realizar..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-calendar-plus-o"></i> Programar Mantenimiento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script>
        function calcularProximaFecha(select) {
            // Esta función podría calcular automáticamente la próxima fecha basada en la frecuencia
            const option = select.selectedOptions[0];
            const frecuencia = option ? option.getAttribute('data-frecuencia') : null;
            
            if (frecuencia && frecuencia > 0) {
                const hoy = new Date();
                const proximaFecha = new Date(hoy.getTime() + (frecuencia * 24 * 60 * 60 * 1000));
                const fechaInput = document.querySelector('input[name="fecha_programada"]');
                fechaInput.value = proximaFecha.toISOString().split('T')[0];
            }
        }

        function verDetalle(id) {
            alert('Funcionalidad de ver detalle en desarrollo. ID: ' + id);
        }

        function cambiarEstado(id, estadoActual) {
            const estados = {
                'programado': ['en_proceso', 'cancelado'],
                'en_proceso': ['completado', 'cancelado'],
                'completado': [],
                'cancelado': []
            };
            
            const estadosDisponibles = estados[estadoActual] || [];
            
            if (estadosDisponibles.length === 0) {
                alert('No se puede cambiar el estado de este mantenimiento.');
                return;
            }
            
            let opciones = '<option value="">Seleccionar nuevo estado</option>';
            estadosDisponibles.forEach(estado => {
                const nombre = estado.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                opciones += '<option value="' + estado + '">' + nombre + '</option>';
            });
            
            const nuevoEstado = prompt('Estado actual: ' + estadoActual.replace('_', ' ') + '\n\nSeleccionar nuevo estado:\n' + 
                                    estadosDisponibles.map((e, i) => (i+1) + '. ' + e.replace('_', ' ')).join('\n'));
            
            if (nuevoEstado && estadosDisponibles.includes(nuevoEstado)) {
                const observaciones = prompt('Observaciones (opcional):');
                let proximoMant = null;
                
                if (nuevoEstado === 'completado') {
                    proximoMant = prompt('Fecha del próximo mantenimiento (YYYY-MM-DD) o dejar vacío:');
                }
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="action" value="actualizar_estado">' +
                               '<input type="hidden" name="mantenimiento_id" value="' + id + '">' +
                               '<input type="hidden" name="nuevo_estado" value="' + nuevoEstado + '">' +
                               '<input type="hidden" name="observaciones" value="' + (observaciones || '') + '">' +
                               (proximoMant ? '<input type="hidden" name="proximo_mantenimiento" value="' + proximoMant + '">' : '');
                document.body.appendChild(form);
                form.submit();
            }
        }

        function imprimirOrden(id) {
            alert('Funcionalidad de imprimir orden de trabajo en desarrollo. ID: ' + id);
        }
    </script>
</body>
</html>
