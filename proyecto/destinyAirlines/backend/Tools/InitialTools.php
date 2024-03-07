<?php
class InitialTools
{
    public function RequestValidator()
    {
        require ROOT_PATH . '/vendor/autoload.php';
        try {
            $storage = new bandwidthThrottle\tokenBucket\storage\FileStorage(ROOT_PATH . "/api.bucket");
            $rate    = new bandwidthThrottle\tokenBucket\Rate(5, bandwidthThrottle\tokenBucket\Rate::SECOND);
            $bucket  = new bandwidthThrottle\tokenBucket\TokenBucket(10, $rate, $storage);
            $bucket->bootstrap(10);

            $seconds = 0;
            if (!$bucket->consume(1, $seconds)) {
                http_response_code(429);
                header(sprintf("Retry-After: %d", floor($seconds)));
                exit();
            }
        } catch (bandwidthThrottle\tokenBucket\storage\StorageException $e) {
            error_log("Error al escribir en el almacenamiento: " . $e->getMessage());
            exit();
        }
    }

    public function setCorsHeaders()
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $requestConfiguration = $iniTool->getKeysAndValues('requestConfiguration');
        //Para averiguar el dominio de la petición recibida
        //error_log('Access-Control-Allow-Origin needed: ' . $_SERVER['HTTP_ORIGIN']);
        // Configuración de la petición permitida
        // Establece la cabecera 'Access-Control-Allow-Origin' para permitir peticiones desde un origen específico
        header('Access-Control-Allow-Origin: ' . $requestConfiguration["accessControlAllowOrigin"]);

        // Opcional: Establece la cabecera 'Access-Control-Allow-Methods' para permitir métodos HTTP específicos como GET, POST, PUT, DELETE
        header('Access-Control-Allow-Methods: ' . $requestConfiguration["accessControlAllowMethods"]);

        // Opcional: Establece la cabecera 'Access-Control-Allow-Headers' para permitir cabeceras HTTP específicas
        header('Access-Control-Allow-Headers: ' . $requestConfiguration["accessControlAllowHeaders"]);
    }

    public function executeCommand(string $controllerName, string $methodName, array $params): array
    {
        try {
            $controller = new $controllerName();
            $response = $controller->$methodName($params);

            if (is_array($response) && array_keys($response) !== range(0, count($response) - 1)) {
                // Si $response es un array o array asociativo, fusionamos $response con el array que se pasa a json_encode
                return array_merge(['status' => true], $response);
            } else {
                return ['status' => true, 'response' => $response];
            }
        } catch (Exception $e) {
            error_log('Catched error: ' . $e);
            return ['status' => false, 'response' => false];
        }
    }

    public function getCommand()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $command = $data['command'] ?? $_POST['command'] ?? $_GET['command'] ?? '';
        return $command;
    }

    public function getInputData()
    {
        //El siguiente bloque de código es para discriminar el método HTTP, incluso si es JSON crudo por POST, y ejecutar la función correspondiente
        $params  = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $json = file_get_contents('php://input');
                $params = json_decode($json, true);
            } else {
                $params = $_POST;
            }
        } else {
            $params = $_GET;
        }
        return $params;
    }

    public function validateCommand($command, $controllers)
    {
        return array_key_exists(strtolower($command), $controllers);
    }

    public function loadController($controllerName)
    {
        require_once "./Controllers/{$controllerName}.php";
    }

    public function executeAndRespond($command, $controllers)
    {
        if ($this->validateCommand($command, $controllers)) {
            $controllerInfo = $controllers[strtolower($command)];
            $this->loadController($controllerInfo['controller']);
            require_once "./Controllers/{$controllerInfo['controller']}.php";
            $params = $this->getInputData();
            return json_encode($this->executeCommand($controllerInfo['controller'], $controllerInfo['method'], $params));
        } else {
            return json_encode(['status' => true, 'response' => false]);
        }
    }
}
