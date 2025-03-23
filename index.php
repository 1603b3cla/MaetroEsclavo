<!DOCTYPE html>
<html lang="es">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
     <script src="https://kit.fontawesome.com/67da38377f.js" crossorigin="anonymous"></script> 
     <title>CRUD Persona</title>
</head>
<body>

<script>
function eliminar(){
     var respuesta = confirm("¿ESTÁS SEGURO QUE DESEAS ELIMINAR?");
     return respuesta;
}
</script>

<h2 class="text-center text-primary m-5">CRUD PERSONA</h2>

<?php
include "modelo/conexion.php";
include "controlador/eliminar-persona.php";
?>

<?php if ($soloLectura): ?>
    <div class="alert alert-warning text-center" role="alert">
        ⚠️ Base de datos principal inactiva. Modo solo lectura (PostgreSQL).
    </div>
<?php else: ?>
    <div class="alert alert-success text-center" role="alert">
        ✅ Base de datos principal activa. Modo completo (MariaDB).
    </div>
<?php endif; ?>

<div class="container-fluid row">
    <?php if (!$soloLectura): ?>
        <form class="col-3 p-3" method="POST">
            <h3 class="text-center text-secondary">REGISTRO PERSONA</h3>

            <?php
            include "controlador/registro-persona.php";
            ?>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre">
            </div>

            <div class="mb-3">
                <label for="apellidoP" class="form-label">Apellido Paterno</label>
                <input type="text" class="form-control" id="apellidoP" name="apellidop">
            </div>

            <div class="mb-3">
                <label for="apellidoM" class="form-label">Apellido Materno</label>
                <input type="text" class="form-control" id="apellidoM" name="apellidom">
            </div>

            <div class="mb-3">
                <label for="edad" class="form-label">Edad</label>
                <input type="number" class="form-control" id="edad" name="edad">
            </div>

            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
            </div>

            <div class="mb-3">
                <label for="curp" class="form-label">CURP</label>
                <input type="text" class="form-control" id="curp" name="curp">
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo">
            </div>

            <button type="submit" class="btn btn-primary" name="btnregistrar" value="ok">REGISTRAR</button>
        </form>
    <?php else: ?>
        <div class="col-3 p-3">
            <div class="alert alert-info">
                <h4>Información</h4>
                <p>El formulario de registro no está disponible en modo de solo lectura.</p>
                <p>Se están mostrando los datos de la base de datos PostgreSQL.</p>
            </div>
        </div>
    <?php endif; ?>

    <div class="col-<?php echo $soloLectura ? '9' : '8'; ?> p-4">
        <table class="table table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido Paterno</th>
                    <th scope="col">Apellido Materno</th>
                    <th scope="col">Edad</th>
                    <th scope="col">Fecha de Nacimiento</th>
                    <th scope="col">CURP</th>
                    <th scope="col">Correo Electrónico</th>
                    <?php if (!$soloLectura): ?>
                        <th scope="col">Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    if ($modo === 'maestro') {
                        // Consulta para MariaDB
                        $sql = $conexion->query("SELECT * FROM persona");
                        while ($datos = $sql->fetch(PDO::FETCH_OBJ)) {
                ?>
                            <tr>
                                <td><?= $datos->id ?></td>
                                <td><?= $datos->nombre ?></td>
                                <td><?= $datos->apellidop ?></td>
                                <td><?= $datos->apellidom ?></td>
                                <td><?= $datos->edad ?></td>
                                <td><?= $datos->fecha_nacimiento ?></td>
                                <td><?= $datos->curp ?></td>
                                <td><?= $datos->correo ?></td>
                                <?php if (!$soloLectura): ?>
                                    <td>
                                        <a href="modificar-persona.php?id=<?= $datos->id ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-fancy"></i></a>
                                        <a onclick="return eliminar()" href="index.php?id=<?= $datos->id ?>" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                <?php
                        }
                    } else {
                        // Consulta para PostgreSQL
                        $sql = $conexion->query("SELECT * FROM persona");
                        while ($datos = $sql->fetch(PDO::FETCH_OBJ)) {
                ?>
                            <tr>
                                <td><?= $datos->id ?></td>
                                <td><?= $datos->nombre ?></td>
                                <td><?= $datos->apellidop ?></td>
                                <td><?= $datos->apellidom ?></td>
                                <td><?= $datos->edad ?></td>
                                <td><?= $datos->fecha_nacimiento ?></td>
                                <td><?= $datos->curp ?></td>
                                <td><?= $datos->correo ?></td>
                                <?php if (!$soloLectura): ?>
                                    <td>
                                        <a href="modificar-persona.php?id=<?= $datos->id ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-fancy"></i></a>
                                        <a onclick="return eliminar()" href="index.php?id=<?= $datos->id ?>" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                <?php
                        }
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='9' class='text-danger'>Error al consultar datos: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
