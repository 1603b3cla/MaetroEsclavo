<?php if (!empty($_POST["btnregistrar"]) && !$soloLectura) {
    if (
        !empty($_POST["nombre"]) &&
        !empty($_POST["apellidop"]) &&
        !empty($_POST["apellidom"]) &&
        !empty($_POST["edad"]) &&
        !empty($_POST["fecha_nacimiento"]) &&
        !empty($_POST["curp"]) &&
        !empty($_POST["correo"])
    ) {
        try {
            $nombre = $_POST["nombre"];
            $apellidop = $_POST["apellidop"];
            $apellidom = $_POST["apellidom"];
            $edad = (int)$_POST["edad"];
            $fecha_nacimiento = $_POST["fecha_nacimiento"];
            $curp = $_POST["curp"];
            $correo = $_POST["correo"];
            
            // Verificar si el CURP ya existe
            if ($modo === 'maestro') {
                // Código para MariaDB
                $stmt = $conexion->prepare("SELECT COUNT(*) FROM persona WHERE curp = ?");
                $stmt->execute([$curp]);
                $count = $stmt->fetchColumn();
                
                if ($count > 0) {
                    echo '<div class="alert alert-warning">EL CURP YA ESTÁ REGISTRADO</div>';
                } else {
                    // Insertar el nuevo registro en MariaDB
                    $stmt = $conexion->prepare("INSERT INTO persona (nombre, apellidop, apellidom, edad, fecha_nacimiento, curp, correo) 
                                          VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $resultado = $stmt->execute([$nombre, $apellidop, $apellidom, $edad, $fecha_nacimiento, $curp, $correo]);
                    
                    if ($resultado) {
                        echo '<div class="alert alert-success">PERSONA REGISTRADA CORRECTAMENTE</div>';
                        
                        // Sincronizar con PostgreSQL
                        try {
                            $pdo_pg = new PDO("pgsql:host=$host_pg;port=$port_pg;dbname=$db_pg", $user_pg, $pass_pg);
                            $pdo_pg->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                            // Usamos consulta directa para evitar problemas con los marcadores
                            $sql = "INSERT INTO persona (nombre, apellidop, apellidom, edad, fecha_nacimiento, curp, correo) 
                                    VALUES (" . 
                                    $pdo_pg->quote($nombre) . "," . 
                                    $pdo_pg->quote($apellidop) . "," . 
                                    $pdo_pg->quote($apellidom) . "," . 
                                    $edad . "," . 
                                    $pdo_pg->quote($fecha_nacimiento) . "," . 
                                    $pdo_pg->quote($curp) . "," . 
                                    $pdo_pg->quote($correo) . ")";
                            
                            $pdo_pg->exec($sql);
                        } catch (PDOException $e) {
                            error_log("Error al sincronizar con PostgreSQL: " . $e->getMessage());
                        }
                    } else {
                        echo '<div class="alert alert-danger">ERROR AL REGISTRAR PERSONA</div>';
                    }
                }
            } else {
                // Código para PostgreSQL (modo esclavo)
                // Verificamos si existe el CURP
                $stmt = $conexion->prepare("SELECT COUNT(*) FROM persona WHERE curp = :curp");
                $stmt->bindParam(':curp', $curp);
                $stmt->execute();
                $count = $stmt->fetchColumn();
                
                if ($count > 0) {
                    echo '<div class="alert alert-warning">EL CURP YA ESTÁ REGISTRADO</div>';
                } else {
                    // Usamos consulta directa para evitar problemas con sintaxis de marcadores
                    $sql = "INSERT INTO persona (nombre, apellidop, apellidom, edad, fecha_nacimiento, curp, correo) 
                            VALUES (" . 
                            $conexion->quote($nombre) . "," . 
                            $conexion->quote($apellidop) . "," . 
                            $conexion->quote($apellidom) . "," . 
                            $edad . "," . 
                            $conexion->quote($fecha_nacimiento) . "," . 
                            $conexion->quote($curp) . "," . 
                            $conexion->quote($correo) . ")";
                    
                    $resultado = $conexion->exec($sql);
                    
                    if ($resultado) {
                        echo '<div class="alert alert-success">PERSONA REGISTRADA CORRECTAMENTE EN POSTGRESQL</div>';
                    } else {
                        echo '<div class="alert alert-danger">ERROR AL REGISTRAR PERSONA: ' . implode(" ", $conexion->errorInfo()) . '</div>';
                    }
                }
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">ERROR: ' . $e->getMessage() . '</div>';
            echo '<div class="alert alert-info">CÓDIGO: ' . $e->getCode() . '</div>';
        }
    } else {
        echo '<div class="alert alert-warning">ALGUNOS DE LOS CAMPOS ESTÁN VACÍOS</div>';
    }
} ?>