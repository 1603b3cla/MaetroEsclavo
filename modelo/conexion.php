este es mi archivo de conexion <?php
// Inicializar la variable de modo
$modo = 'maestro';  // Por defecto, asumimos que MariaDB está activo
$soloLectura = false;

// Configuración MariaDB (Maestro)
$host_maria = 'localhost'; 
$db_maria   = 'crud';    
$user_maria = 'root';      
$pass_maria = '';      
$port_maria = "3306";

// Configuración PostgreSQL (Esclavo)
$host_pg = 'localhost';  
$db_pg   = 'postgres';    
$user_pg = 'postgres';      
$pass_pg = '040823';        
$port_pg = "5432";   

// Intentar conectar a MariaDB
try {
    $pdo_maria = new PDO("mysql:host=$host_maria;port=$port_maria;dbname=$db_maria", $user_maria, $pass_maria);
    $pdo_maria->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Comprobar si MariaDB está disponible realizando una consulta simple
    $pdo_maria->query("SELECT 1");
    $conexion = $pdo_maria;
} catch (PDOException $e) {
    // Si ocurre un error, pasamos al modo esclavo
    $modo = 'esclavo';
    
    // Intentar conectar a PostgreSQL
    try {
        $pdo_pg = new PDO("pgsql:host=$host_pg;port=$port_pg;dbname=$db_pg", $user_pg, $pass_pg);
        $pdo_pg->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion = $pdo_pg;
        $soloLectura = true;
    } catch (PDOException $e) {
        die("Error de conexión a todas las bases de datos: " . $e->getMessage());
    }
}
?>
