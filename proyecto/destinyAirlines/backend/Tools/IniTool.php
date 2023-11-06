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

        function getIfError()
        {
            return $this->ifError;
        }

        function getKey(string $section, string $key)
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

        function getKeysAndValues(string $section)
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

        public function getSection(string $section)
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
