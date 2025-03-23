<!DOCTYPE html>
<html lang="es">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
     <script src="https://kit.fontawesome.com/67da38377f.js" crossorigin="anonymous"></script> 
     <title>Modificar Persona</title>
</head>
<body>
    <?php
    include "modelo/conexion.php";
    
    if ($soloLectura) {
        echo '<div class="container mt-5">';
        echo '<div class="alert alert-warning text-center">⚠️ Base de datos principal inactiva. Modo solo lectura (PostgreSQL).</div>';
        echo '<div class="alert alert-info">No se pueden realizar modificaciones en modo de solo lectura.</div>';
        echo '<a href="index.php" class="btn btn-primary">Volver al inicio</a>';
        echo '</div>';
        exit;
    }
    
    // Obtener datos de la persona
    $id = $_GET["id"] ?? 0;
    try {
        if ($modo === 'maestro') {
            $stmt = $conexion->prepare("SELECT * FROM persona WHERE id = ?");
            $stmt->execute([$id]);
            $datos = $stmt->fetch(PDO::FETCH_OBJ);
        } else {
            $stmt = $conexion->prepare("SELECT * FROM persona WHERE id = ?");
            $stmt->execute([$id]);
            $datos = $stmt->fetch(PDO::FETCH_OBJ);
        }
        
        if (!$datos) {
            echo '<div class="alert alert-danger">Persona no encontrada</div>';
            exit;
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">ERROR: ' . $e->getMessage() . '</div>';
        exit;
    }
    ?>

    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4">MODIFICAR PERSONA</h2>
        
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="<?= $datos->nombre ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="apellidop" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellidom" name="apellidop" 
                               value="<?= $modo === 'maestro' ? $datos->apellidop : $datos->apellidop ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="apellidom" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="apellidom" name="apellidom" 
                               value="<?= $modo === 'maestro' ? $datos->apellidom : $datos->apellidom ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="edad" class="form-label">Edad</label>
                        <input type="number" class="form-control" id="edad" name="edad" 
                               value="<?= $datos->edad ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" 
                               value="<?= $datos->fecha_nacimiento ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="curp" class="form-label">CURP</label>
                        <input type="text" class="form-control" id="curp" name="curp" 
                               value="<?= $datos->curp ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" 
                               value="<?= $datos->correo ?>">
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" name="btnregistrar" value="ok">ACTUALIZAR</button>
                        <a href="index.php" class="btn btn-secondary">CANCELAR</a>
                    </div>
                </form>
                
                <?php
                if (!empty($_POST["btnregistrar"])) {
                    if (!empty($_POST["nombre"]) && 
                        !empty($_POST["apellidop"]) && 
                        !empty($_POST["apellidom"]) && 
                        !empty($_POST["edad"]) &&
                        !empty($_POST["fecha_nacimiento"]) && 
                        !empty($_POST["curp"]) && 
                        !empty($_POST["correo"])) {
                        
                        $id = $_POST["id"];
                        $nombre = $_POST["nombre"];
                        $apellidop = $_POST["apellidop"];
                        $apellidom = $_POST["apellidom"];
                        $edad = $_POST["edad"];
                        $fecha_nacimiento = $_POST["fecha_nacimiento"];
                        $curp = $_POST["curp"];
                        $correo = $_POST["correo"];
                        
                        try {
                            if ($modo === 'maestro') {
                                $stmt = $conexion->prepare("UPDATE persona SET 
                                    nombre = ?,
                                    apellidop = ?,
                                    apellidom = ?,
                                    edad = ?,
                                    fecha_nacimiento = ?,
                                    curp = ?,
                                    correo = ? 
                                    WHERE id = ?");
                                $resultado = $stmt->execute([$nombre, $apellidop, $apellidom, $edad, $fecha_nacimiento, $curp, $correo, $id]);
                            } else {
                                $stmt = $conexion->prepare("UPDATE persona SET 
                                    nombre = ?,
                                    apellidop = ?,
                                    apellidom = ?,
                                    edad = ?,
                                    fecha_nacimiento = ?,
                                    curp = ?,
                                    correo = ? 
                                    WHERE id = ?");
                                $resultado = $stmt->execute([$nombre, $apellidop, $apellidom, $edad, $fecha_nacimiento, $curp, $correo, $id]);
                            }
                            
                            if ($resultado) {
                                echo "<script>window.location='index.php';</script>";
                            } else {
                                echo "<div class='alert alert-warning'>ERROR AL MODIFICAR</div>";
                            }
                        } catch (PDOException $e) {
                            echo "<div class='alert alert-danger'>ERROR: " . $e->getMessage() . "</div>";
                        }
                    } else {
                        echo "<div class='alert alert-warning'>CAMPOS VACÍOS</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
