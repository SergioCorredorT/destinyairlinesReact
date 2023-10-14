<?php
// Incluye el archivo de conexión a la base de datos
require_once '../Config/db.php';

try {
    // Obtiene la consulta SQL desde el parámetro POST
    $sql = $_POST['consulta'];

    // Prepara la consulta SQL
    $stmt = $conn->prepare($sql);

    // Determina el tipo de consulta SQL
    $tipo = strtolower(str_word_count($sql, 1)[0]);

    switch ($tipo) {
        case 'select':
            // Ejecuta la consulta
            $stmt->execute();

            // Obtiene los resultados
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Devuelve los resultados en formato JSON
            echo json_encode($resultados);
            break;
        case 'insert':
        case 'update':
        case 'delete':
            // Ejecuta la consulta
            $stmt->execute();

            // Devuelve el número de filas afectadas
            echo json_encode(['filas_afectadas' => $stmt->rowCount()]);
            break;
        default:
            throw new Exception('Tipo de consulta no permitida');
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
