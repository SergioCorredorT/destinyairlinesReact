<?php
    class IniController
    {
//Ruta al archivo .ini
        private $rutaIni=null;
//Valor obtenido del archivo .ini
        private $valorIni=null;
//Indicador de si hay error
        private $ifError=false;

        public function __construct($rutaIni="cfg.ini")
        {
            if(is_string($rutaIni))
            {
                if(file_exists($rutaIni))
                {
                    $this->rutaIni=$rutaIni;
//Obtengo valores del archivo .ini
                    $this->valorIni=parse_ini_file($rutaIni,true);
//Compruebo si tengo valores del archivo .ini
                    if(!(is_array($this->valorIni)))
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
            $this->valorIni=null;
            $this->ifError=null;
        }

        function getIfError()
        {
            return $this->ifError;
        }

        function getKey($section,$key)
        {
            if(isset($this->valorIni[$section][$key]))
            {
                return $this->valorIni[$section][$key];
            }
            else
            {
                return false;
            }
        }

        function getKeysAndValues($section)
        {
            if(isset($this->valorIni[$section]))
            {
                return $this->valorIni[$section];
            }
            else
            {
                return false;
            }
        }

        public function getSection($section)
        {
            if(isset($this->valorIni[$section]))
            {
                return $this->valorIni[$section];
            }
            else
            {
                return false;
            }
        }
    }
