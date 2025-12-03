<?php
/**
 * Módulo de Gestión de Usuarios - SIMAHG
 * Solo accesi        <!-- Título -->
        <div class="row">
            <div class="col-md-9">
                <h2><i class="fa fa-users"></i> Gestión de Usuarios - SIMAHG</h2>
                <p class="text-muted">Administración completa de usuarios y perfiles del sistema</p>
            </div>
            <div class="col-md-3 text-right">
                <a href="dashboard.php" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left"></i> Volver al Dashboard
                </a>
            </div>
        </div>
        <hr>

        <!-- Tabla de usuarios -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5><i class="fa fa-list"></i> Lista de Usuarios del Sistema</h5>
            </div>nistradores
 */

session_start();

// Incluir configuración común
require_once 'includes/config_common.php';

// VERIFICAR PERMISO: Solo Administradores
if (!esAdmin()) {
    $_SESSION['error'] = 'No tienes permisos para acceder a esta sección. Solo Administradores.';
    header('Location: dashboard.php');
    exit();
}

// Conexión a la base de datos
$pdo = getDBConnection();

try {
    // Obtener todos los usuarios
    $stmt = $pdo->query("SELECT u.*, p.nombre as perfil_nombre 
                        FROM usuarios u 
                        JOIN perfiles p ON u.id_perfil = p.id 
                        ORDER BY u.id ASC");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener perfiles disponibles
    $stmt2 = $pdo->query("SELECT * FROM perfiles WHERE estado = 1 ORDER BY nombre");
    $perfiles = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "Error de base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Usuarios - SIMAHG</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <?php renderEstilosComunes(); ?>
    <style>
        .badge-activo { background-color: #28a745; }
        .badge-inactivo { background-color: #dc3545; }
    </style>
</head>
<body>

<?php renderNavbar('usuarios'); ?>

<div class="container-fluid" style="padding: 20px;">
    
    <?php if (isset($_SESSION['success'])): ?>
        <?php mostrarAlerta('success', $_SESSION['success']); unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <?php mostrarAlerta('error', $error); ?>
    <?php endif; ?>

    <div class="container mt-4">
        <!-- Título -->
        <div class="row">
            <div class="col-12">
                <h2><i class="fa fa-users"></i> Gestión de Usuarios</h2>
                <hr>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-list"></i> Lista de Usuarios</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Nombre Completo</th>
                                <th>Email</th>
                                <th>Perfil</th>
                                <th>Estado</th>
                                <th>Último Acceso</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><strong><?php echo $user['usuario']; ?></strong></td>
                                <td><?php echo $user['nombre'] . ' ' . $user['apellidos']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><span class="badge badge-info"><?php echo $user['perfil_nombre']; ?></span></td>
                                <td>
                                    <?php if ($user['estado'] == 1): ?>
                                        <span class="badge badge-activo">Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-inactivo">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                    echo $user['ultimo_acceso'] 
                                        ? date('d/m/Y H:i', strtotime($user['ultimo_acceso']))
                                        : 'Nunca';
                                    ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary btn-editar" 
                                            data-id="<?php echo $user['id']; ?>"
                                            data-usuario="<?php echo $user['usuario']; ?>"
                                            data-nombre="<?php echo $user['nombre']; ?>"
                                            data-apellidos="<?php echo $user['apellidos']; ?>"
                                            data-email="<?php echo $user['email']; ?>"
                                            data-telefono="<?php echo $user['telefono']; ?>"
                                            data-perfil="<?php echo $user['id_perfil']; ?>"
                                            title="Editar">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <?php if ($user['estado'] == 1): ?>
                                        <button class="btn btn-sm btn-warning btn-toggle-estado" 
                                                data-id="<?php echo $user['id']; ?>"
                                                data-estado="0"
                                                data-nombre="<?php echo $user['nombre'] . ' ' . $user['apellidos']; ?>"
                                                title="Desactivar">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-success btn-toggle-estado" 
                                                data-id="<?php echo $user['id']; ?>"
                                                data-estado="1"
                                                data-nombre="<?php echo $user['nombre'] . ' ' . $user['apellidos']; ?>"
                                                title="Activar">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-info btn-resetear" 
                                            data-id="<?php echo $user['id']; ?>"
                                            data-nombre="<?php echo $user['nombre'] . ' ' . $user['apellidos']; ?>"
                                            title="Resetear Contraseña">
                                        <i class="fa fa-key"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Editar Usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                </div>
                <form id="formEditarUsuario">
                    <div class="modal-body">
                        <input type="hidden" id="edit_user_id" name="user_id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Usuario <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_usuario" name="usuario" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Perfil <span class="text-danger">*</span></label>
                                    <select class="form-control" id="edit_perfil" name="id_perfil" required>
                                        <?php foreach ($perfiles as $perfil): ?>
                                            <option value="<?php echo $perfil['id']; ?>"><?php echo $perfil['nombre']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_apellidos" name="apellidos" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="edit_email" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="text" class="form-control" id="edit_telefono" name="telefono">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Guardar Cambios
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
    $(document).ready(function() {
        
        // Abrir modal de editar
        $('.btn-editar').click(function() {
            var id = $(this).data('id');
            var usuario = $(this).data('usuario');
            var nombre = $(this).data('nombre');
            var apellidos = $(this).data('apellidos');
            var email = $(this).data('email');
            var telefono = $(this).data('telefono');
            var perfil = $(this).data('perfil');
            
            $('#edit_user_id').val(id);
            $('#edit_usuario').val(usuario);
            $('#edit_nombre').val(nombre);
            $('#edit_apellidos').val(apellidos);
            $('#edit_email').val(email);
            $('#edit_telefono').val(telefono);
            $('#edit_perfil').val(perfil);
            
            $('#modalEditarUsuario').modal('show');
        });
        
        // Enviar formulario de edición
        $('#formEditarUsuario').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: 'usuarios_actions.php',
                type: 'POST',
                data: $(this).serialize() + '&action=editar',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('✅ Usuario actualizado correctamente');
                        location.reload();
                    } else {
                        alert('❌ Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('❌ Error al comunicarse con el servidor');
                }
            });
        });
        
        // Cambiar estado (activar/desactivar)
        $('.btn-toggle-estado').click(function() {
            var id = $(this).data('id');
            var estado = $(this).data('estado');
            var nombre = $(this).data('nombre');
            var accion = estado == 1 ? 'activar' : 'desactivar';
            
            if (confirm('¿Estás seguro de ' + accion + ' a ' + nombre + '?')) {
                $.ajax({
                    url: 'usuarios_actions.php',
                    type: 'POST',
                    data: {
                        action: 'cambiar_estado',
                        user_id: id,
                        estado: estado
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('✅ Estado cambiado correctamente');
                            location.reload();
                        } else {
                            alert('❌ Error: ' + response.message);
                        }
                    }
                });
            }
        });
        
        // Resetear contraseña
        $('.btn-resetear').click(function() {
            var id = $(this).data('id');
            var nombre = $(this).data('nombre');
            
            if (confirm('¿Resetear la contraseña de ' + nombre + ' a "123456"?')) {
                $.ajax({
                    url: 'usuarios_actions.php',
                    type: 'POST',
                    data: {
                        action: 'resetear_password',
                        user_id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('✅ Contraseña reseteada a: 123456');
                        } else {
                            alert('❌ Error: ' + response.message);
                        }
                    }
                });
            }
        });
        
    });
    </script>
</body>
</html>
