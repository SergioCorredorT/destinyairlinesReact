<?php
    class IniTool
    {
//Ruta al archivo .ini
        private $rutaIni=null;
//Valor obtenido del archivo .ini
        private $iniContain=null;
//Indicador de si hay error
        private $ifError=false;

        public function __construct(string $rutaIni='cfg.ini')
        {
            if(is_string($rutaIni))
            {
                if(file_exists($rutaIni))
                {
                    $this->rutaIni=$rutaIni;
//Obtengo valores del archivo .ini
                    $this->iniContain=parse_ini_file($rutaIni,true);
//Compruebo si tengo valores del archivo .ini
                    if(!(is_array($this->iniContain)))
                    {
                        $this->ifError=true;
                    }
                }
                else
                {
                    $this->ifError=true;
                }
            }
        }

        public function __destruct()
        {
            $this->rutaIni=null;
            $this->iniContain=null;
            $this->ifError=null;
        }

        function getIfError(): bool
        {
            return $this->ifError;
        }

        function getValue(string $section, string $key): bool|string
        {
            if(isset($this->iniContain[$section][$key]))
            {
                return $this->iniContain[$section][$key];
            }
            else
            {
                return false;
            }
        }

        function getKeysAndValues(string $section): bool|string|array
        {
            if(isset($this->iniContain[$section]))
            {
                return $this->iniContain[$section];
            }
            else
            {
                return false;
            }
        }

        public function getSection(string $section): bool|string
        {
            if(isset($this->iniContain[$section]))
            {
                return $this->iniContain[$section];
            }
            else
            {
                return false;
            }
        }
    }
