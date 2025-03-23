<?php
if (!empty($_GET["id"]) && !$soloLectura) {
    $id = $_GET["id"];
    try {
        if ($modo === 'maestro') {
            $sql = $conexion->prepare("DELETE FROM persona WHERE id = ?");
            $resultado = $sql->execute([$id]);

            if ($resultado) {
                echo '<div class="alert alert-success">Registro eliminado correctamente en MariaDB</div>';
            } else {
                echo '<div class="alert alert-danger">ERROR AL ELIMINAR EN MariaDB</div>';
            }
        } else {
            echo '<div class="alert alert-danger">No se puede eliminar en PostgreSQL porque está en modo solo lectura.</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">ERROR: ' . $e->getMessage() . '</div>';
    }
}

?>
