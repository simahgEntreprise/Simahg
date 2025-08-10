<?php
/**
 * Módulo de Solicitudes de EPPs - SIMAHG
 * Versión alineada con sistema común
 */

session_start();

// Incluir archivo común
require_once 'includes/config_common.php';

// Obtener conexión a BD
$pdo = getDBConnection();

// Obtener datos de sesión
$userId = $_SESSION['user_id'];
$userName = $_SESSION['username'];
$userRole = isset($_SESSION['perfil_nombre']) ? trim($_SESSION['perfil_nombre']) : 'Sin Rol';

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    
    if ($accion === 'crear_solicitud') {
        try {
            $id_epp = $_POST['id_epp'];
            $cantidad = $_POST['cantidad'];
            $justificacion = sanitizar($_POST['justificacion']);
            
            $stmt = $pdo->prepare("
                INSERT INTO solicitudes_epp (id_usuario, id_epp, cantidad, justificacion, estado, fecha_solicitud)
                VALUES (?, ?, ?, ?, 'PENDIENTE', NOW())
            ");
            
            $stmt->execute([$userId, $id_epp, $cantidad, $justificacion]);
            
            $_SESSION['success'] = 'Solicitud creada exitosamente. En espera de aprobación.';
            header('Location: solicitudes_epp.php');
            exit;
            
        } catch (PDOException $e) {
            $error = "Error al crear solicitud: " . $e->getMessage();
        }
    }
    
    if ($accion === 'aprobar' && puedeGestionar()) {
        try {
            $id_solicitud = $_POST['id_solicitud'];
            
            $stmt = $pdo->prepare("
                UPDATE solicitudes_epp 
                SET estado = 'APROBADA', 
                    id_aprobador = ?,
                    fecha_aprobacion = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([$userId, $id_solicitud]);
            
            $_SESSION['success'] = 'Solicitud aprobada exitosamente';
            header('Location: solicitudes_epp.php');
            exit;
            
        } catch (PDOException $e) {
            $error = "Error al aprobar solicitud: " . $e->getMessage();
        }
    }
    
    if ($accion === 'rechazar' && puedeGestionar()) {
        try {
            $id_solicitud = $_POST['id_solicitud'];
            $motivo_rechazo = sanitizar($_POST['motivo_rechazo']);
            
            $stmt = $pdo->prepare("
                UPDATE solicitudes_epp 
                SET estado = 'RECHAZADA', 
                    id_aprobador = ?,
                    fecha_aprobacion = NOW(),
                    motivo_rechazo = ?
                WHERE id = ?
            ");
            
            $stmt->execute([$userId, $motivo_rechazo, $id_solicitud]);
            
            $_SESSION['success'] = 'Solicitud rechazada';
            header('Location: solicitudes_epp.php');
            exit;
            
        } catch (PDOException $e) {
            $error = "Error al rechazar solicitud: " . $e->getMessage();
        }
    }
    
    if ($accion === 'entregar' && puedeGestionar()) {
        try {
            $id_solicitud = $_POST['id_solicitud'];
            
            $pdo->beginTransaction();
            
            // Actualizar estado de solicitud
            $stmt = $pdo->prepare("
                UPDATE solicitudes_epp 
                SET estado = 'ENTREGADA', 
                    fecha_entrega = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$id_solicitud]);
            
            // Obtener información de la solicitud
            $stmt2 = $pdo->prepare("
                SELECT id_epp, cantidad FROM solicitudes_epp WHERE id = ?
            ");
            $stmt2->execute([$id_solicitud]);
            $solicitud = $stmt2->fetch();
            
            // Descontar del inventario
            $stmt3 = $pdo->prepare("
                UPDATE epp_items 
                SET stock_actual = stock_actual - ? 
                WHERE id = ?
            ");
            $stmt3->execute([$solicitud['cantidad'], $solicitud['id_epp']]);
            
            $pdo->commit();
            
            $_SESSION['success'] = 'EPP entregado y descontado del inventario';
            header('Location: solicitudes_epp.php');
            exit;
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Error al entregar EPP: " . $e->getMessage();
        }
    }
}

// Obtener solicitudes según el rol
if (esOperador()) {
    // Operadores/Usuarios solo ven sus propias solicitudes
    $stmt = $pdo->prepare("
        SELECT s.*, e.nombre as epp_nombre, e.codigo, c.nombre as categoria,
               u.nombre as solicitante_nombre, u.apellidos as solicitante_apellidos,
               a.nombre as aprobador_nombre
        FROM solicitudes_epp s
        INNER JOIN epp_items e ON s.id_epp = e.id
        LEFT JOIN categorias_epp c ON e.id_categoria = c.id
        INNER JOIN usuarios u ON s.id_usuario = u.id
        LEFT JOIN usuarios a ON s.id_aprobador = a.id
        WHERE s.id_usuario = ?
        ORDER BY s.fecha_solicitud DESC
    ");
    $stmt->execute([$userId]);
} else {
    // Supervisores y admins ven todas las solicitudes
    $stmt = $pdo->query("
        SELECT s.*, e.nombre as epp_nombre, e.codigo, c.nombre as categoria,
               u.nombre as solicitante_nombre, u.apellidos as solicitante_apellidos,
               a.nombre as aprobador_nombre
        FROM solicitudes_epp s
        INNER JOIN epp_items e ON s.id_epp = e.id
        LEFT JOIN categorias_epp c ON e.id_categoria = c.id
        INNER JOIN usuarios u ON s.id_usuario = u.id
        LEFT JOIN usuarios a ON s.id_aprobador = a.id
        ORDER BY s.fecha_solicitud DESC
    ");
}

$solicitudes = $stmt->fetchAll();

// Obtener catálogo de EPPs para el formulario
$stmtEPP = $pdo->query("SELECT e.*, c.nombre as categoria_nombre FROM epp_items e LEFT JOIN categorias_epp c ON e.id_categoria = c.id WHERE e.estado = 'activo' AND e.stock_actual > 0 ORDER BY c.nombre, e.nombre");
$epps = $stmtEPP->fetchAll();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitudes de EPPs - SIMAHG</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <?php renderEstilosComunes(); ?>
</head>
<body>

<?php renderNavbar('solicitudes'); ?>

<div class="container-fluid">
    
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fa fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="padding: 20px;">
                    <div class="row">
                        <div class="col-md-6">
                            <h3><i class="fa fa-shield"></i> Solicitudes de EPPs</h3>
                            <p class="text-muted">
                                <?php if (esOperador()): ?>
                                    Solicita los Equipos de Protección Personal que necesites
                                <?php else: ?>
                                    Gestiona las solicitudes de EPPs de los trabajadores
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalNuevaSolicitud">
                                <i class="fa fa-plus"></i> Nueva Solicitud
                            </button>
                            <a href="logout.php" class="btn btn-danger btn-lg">
                                <i class="fa fa-sign-out"></i> Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Estadísticas -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number text-warning"><?php echo count(array_filter($solicitudes, fn($s) => $s['estado'] == 'PENDIENTE')); ?></div>
                    <p>Pendientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number text-success"><?php echo count(array_filter($solicitudes, fn($s) => $s['estado'] == 'APROBADA')); ?></div>
                    <p>Aprobadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number text-info"><?php echo count(array_filter($solicitudes, fn($s) => $s['estado'] == 'ENTREGADA')); ?></div>
                    <p>Entregadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number text-danger"><?php echo count(array_filter($solicitudes, fn($s) => $s['estado'] == 'RECHAZADA')); ?></div>
                    <p>Rechazadas</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla de Solicitudes -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="padding: 20px;">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <?php if (!esOperador($userRole)): ?>
                                    <th>Solicitante</th>
                                    <?php endif; ?>
                                    <th>EPP</th>
                                    <th>Categoría</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($solicitudes as $solicitud): ?>
                                <tr>
                                    <td><?php echo $solicitud['id']; ?></td>
                                    <td><?php echo formatearFecha($solicitud['fecha_solicitud'], 'd/m/Y'); ?></td>
                                    <?php if (!esOperador($userRole)): ?>
                                    <td><?php echo $solicitud['solicitante_nombre'] . ' ' . $solicitud['solicitante_apellidos']; ?></td>
                                    <?php endif; ?>
                                    <td><?php echo $solicitud['epp_nombre']; ?></td>
                                    <td><?php echo $solicitud['categoria']; ?></td>
                                    <td><?php echo $solicitud['cantidad']; ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = 'badge-' . strtolower($solicitud['estado']);
                                        echo "<span class='badge $badgeClass'>" . $solicitud['estado'] . "</span>";
                                        ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick='verDetalle(<?php echo json_encode($solicitud); ?>)'>
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        
                                        <?php if ($solicitud['estado'] == 'PENDIENTE' && puedeGestionar($userRole)): ?>
                                        <button class="btn btn-sm btn-success" onclick="aprobarSolicitud(<?php echo $solicitud['id']; ?>)">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="rechazarSolicitud(<?php echo $solicitud['id']; ?>)">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <?php endif; ?>
                                        
                                        <?php if ($solicitud['estado'] == 'APROBADA' && puedeGestionar($userRole)): ?>
                                        <button class="btn btn-sm btn-primary" onclick="entregarEPP(<?php echo $solicitud['id']; ?>)">
                                            <i class="fa fa-hand-paper-o"></i> Entregar
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                
                                <?php if (empty($solicitudes)): ?>
                                <tr>
                                    <td colspan="<?php echo esOperador($userRole) ? '7' : '8'; ?>" class="text-center">No hay solicitudes registradas</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- Modal Nueva Solicitud -->
<div class="modal fade" id="modalNuevaSolicitud" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Nueva Solicitud de EPP</h4>
            </div>
            <form method="POST" id="formNuevaSolicitud" onsubmit="return validarFormulario()">
                <input type="hidden" name="accion" value="crear_solicitud">
                <div class="modal-body">
                    <div class="form-group">
                        <label>EPP * <small class="text-muted">(Campo obligatorio)</small></label>
                        <select name="id_epp" id="id_epp" class="form-control" required>
                            <option value="">-- Seleccione un EPP --</option>
                            <?php foreach ($epps as $epp): ?>
                            <option value="<?php echo $epp['id']; ?>" data-stock="<?php echo $epp['stock_actual']; ?>">
                                <?php echo $epp['nombre'] . ' - ' . $epp['categoria_nombre'] . ' (Disponible: ' . $epp['stock_actual'] . ')'; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-danger" id="error_epp" style="display:none;">Debe seleccionar un EPP</small>
                    </div>
                    <div class="form-group">
                        <label>Cantidad * <small class="text-muted">(Número entero positivo)</small></label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" max="999" required>
                        <small class="text-danger" id="error_cantidad" style="display:none;">Ingrese una cantidad válida</small>
                    </div>
                    <div class="form-group">
                        <label>Justificación * <small class="text-muted">(Mínimo 10 caracteres)</small></label>
                        <textarea name="justificacion" id="justificacion" class="form-control" rows="3" required placeholder="Explica por qué necesitas este EPP..." minlength="10" maxlength="500"></textarea>
                        <small class="text-muted"><span id="contador_chars">0</span>/500 caracteres</small>
                        <small class="text-danger" id="error_justificacion" style="display:none;">La justificación debe tener al menos 10 caracteres</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnEnviar">
                        <i class="fa fa-paper-plane"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detalle -->
<div class="modal fade" id="modalDetalle" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-file-text"></i> Detalle de Solicitud</h4>
            </div>
            <div class="modal-body" id="detalleContent">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Rechazar -->
<div class="modal fade" id="modalRechazar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-times"></i> Rechazar Solicitud</h4>
            </div>
            <form method="POST">
                <input type="hidden" name="accion" value="rechazar">
                <input type="hidden" name="id_solicitud" id="rechazar_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Motivo del rechazo *</label>
                        <textarea name="motivo_rechazo" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Rechazar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
function verDetalle(solicitud) {
    let html = `
        <table class="table">
            <tr><th>ID:</th><td>${solicitud.id}</td></tr>
            <tr><th>EPP:</th><td>${solicitud.epp_nombre}</td></tr>
            <tr><th>Código:</th><td>${solicitud.codigo}</td></tr>
            <tr><th>Categoría:</th><td>${solicitud.categoria}</td></tr>
            <tr><th>Cantidad:</th><td>${solicitud.cantidad}</td></tr>
            <tr><th>Estado:</th><td><span class="badge badge-${solicitud.estado.toLowerCase()}">${solicitud.estado}</span></td></tr>
            <tr><th>Solicitante:</th><td>${solicitud.solicitante_nombre} ${solicitud.solicitante_apellidos}</td></tr>
            <tr><th>Fecha Solicitud:</th><td>${solicitud.fecha_solicitud}</td></tr>
            <tr><th>Justificación:</th><td>${solicitud.justificacion}</td></tr>
    `;
    
    if (solicitud.aprobador_nombre) {
        html += `<tr><th>Aprobador:</th><td>${solicitud.aprobador_nombre}</td></tr>`;
    }
    
    if (solicitud.fecha_aprobacion) {
        html += `<tr><th>Fecha Aprobación:</th><td>${solicitud.fecha_aprobacion}</td></tr>`;
    }
    
    if (solicitud.fecha_entrega) {
        html += `<tr><th>Fecha Entrega:</th><td>${solicitud.fecha_entrega}</td></tr>`;
    }
    
    if (solicitud.motivo_rechazo) {
        html += `<tr><th>Motivo Rechazo:</th><td class="text-danger">${solicitud.motivo_rechazo}</td></tr>`;
    }
    
    html += `</table>`;
    
    $('#detalleContent').html(html);
    $('#modalDetalle').modal('show');
}

function aprobarSolicitud(id) {
    if (confirm('¿Está seguro de aprobar esta solicitud?')) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="accion" value="aprobar">
            <input type="hidden" name="id_solicitud" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function rechazarSolicitud(id) {
    $('#rechazar_id').val(id);
    $('#modalRechazar').modal('show');
}

function entregarEPP(id) {
    if (confirm('¿Confirmar entrega de EPP? Se descontará del inventario.')) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="accion" value="entregar">
            <input type="hidden" name="id_solicitud" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Validación del formulario
function validarFormulario() {
    let valido = true;
    
    // Ocultar todos los errores
    $('.text-danger').hide();
    
    // Validar EPP
    const epp = document.getElementById('id_epp').value;
    if (!epp) {
        document.getElementById('error_epp').style.display = 'block';
        valido = false;
    }
    
    // Validar cantidad
    const cantidad = document.getElementById('cantidad').value;
    if (!cantidad || cantidad < 1) {
        document.getElementById('error_cantidad').style.display = 'block';
        valido = false;
    }
    
    // Validar justificación
    const justificacion = document.getElementById('justificacion').value;
    if (!justificacion || justificacion.trim().length < 10) {
        document.getElementById('error_justificacion').style.display = 'block';
        valido = false;
    }
    
    // Validar que la cantidad no exceda el stock
    if (epp && cantidad) {
        const stockDisponible = $('#id_epp option:selected').data('stock');
        if (parseInt(cantidad) > parseInt(stockDisponible)) {
            alert('⚠️ La cantidad solicitada (' + cantidad + ') excede el stock disponible (' + stockDisponible + ')');
            valido = false;
        }
    }
    
    if (!valido) {
        alert('❌ Por favor, complete todos los campos obligatorios correctamente.');
    }
    
    return valido;
}

// Contador de caracteres para justificación
$(document).ready(function() {
    $('#justificacion').on('input', function() {
        const chars = $(this).val().length;
        $('#contador_chars').text(chars);
        
        if (chars < 10) {
            $('#contador_chars').parent().removeClass('text-success').addClass('text-danger');
        } else {
            $('#contador_chars').parent().removeClass('text-danger').addClass('text-success');
        }
    });
    
    // Validación en tiempo real
    $('#id_epp').on('change', function() {
        if ($(this).val()) {
            $('#error_epp').hide();
        }
    });
    
    $('#cantidad').on('input', function() {
        if ($(this).val() >= 1) {
            $('#error_cantidad').hide();
        }
    });
    
    $('#justificacion').on('input', function() {
        if ($(this).val().trim().length >= 10) {
            $('#error_justificacion').hide();
        }
    });
});
</script>

</body>
</html>
# Update 1764801942
# Update 1764801943
# Update 1764801944
# Update 1764801946
