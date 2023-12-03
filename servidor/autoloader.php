<?php
    class Autoloader
    {
        public static function load()
        {            
            spl_autoload_register(function ($clase) {
                //Establece la ruta base del proyecto
                $rutaBase = $_SERVER['DOCUMENT_ROOT'] . '/erasmus/servidor';

                //Indica los directorios existentes
                $directorios = 
                [
                    'entities',
                    'api',
                    'repository',
                    'helpers',
                    'config',
                    'components'
                   
                ];

                //Recorre los directorios hasta que encuentre el necesitado
                foreach ($directorios as $directorio) 
                {
                    //Crea la ruta del archivo que se busca
                    $archivo = $rutaBase . '/' . $directorio . '/' . $clase . '.php';

                    //Si existe, se usa.
                    if (file_exists($archivo)) 
                    {
                        require_once $archivo;
                        return;
                    }
                }

                // Si no se encuentra la clase, lanza una excepción indicando cúal es la no encontrada.
                throw new Exception("No se puede encontrar la clase: $clase");
            });
        }
    }

    Autoloader::load();
?>