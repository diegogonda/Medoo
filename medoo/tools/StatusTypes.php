<?php

namespace Medoo\tools;

class StatusTypes {
    
    const OK = 0; // SE HA PROCESADO SIN ERRORES
    const DEBUG_FILE_NOT_WRITABLE = 1; //  Se indica un fichero de log pero no se puede escribir en él
    const DEBUG_MODE_CONFIG_ERROR = 2; //  Se indica una configuración del modo debug incorrecta
    const DEBUG_FILE_NOT_SET = 3; //  Se indica una configuración del modo debug a fichero pero no se indica un nombre de fichero
    const DEBUG_FILE_DIR_NOT_EXIST = 4; //  Se indica una configuración del modo debug a fichero pero la ruta no existe
    const DEBUG_FILE_CANNOT_CREATE = 5; //  Se indica una configuración del modo debug a fichero, si el fichero no existe y falla su creación
}
