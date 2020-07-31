<?php

namespace Medoo\tools;

use Medoo\MedooException;

trait DebugModeTrait {

    protected $debug_stdout = StdoutTypes::_default;
    protected $debug_newline = PHP_EOL;
    protected $debug_mode = false;
    /**
     * Insertar las querys que se ejecuten en un fichero dado
     *  # file : nombre del fichero
     *  # type : tipo de registro (todas, solo una, otros..)
     *  # backtrace : se almacenan también la ruta de ficheros
     */
    protected $debug_file = null;
    protected $debug_show_querys = null;
    protected $debug_file_backtrace = false;

    public function debugMode(array $config) {
        $this->debug_mode = true;
        $this->debug_stdout = StdoutTypes::PHP_ECHO; // Por defecto mostraremos los logs por pantalla
        if (isset($config['stdout'])) {
            $this->debug_stdout = $config['stdout'];
        }
        if ($this->debug_stdout === StdoutTypes::FILE) {
            if (!isset($config['filename'])) {
                throw MedooException::debugFileNotSet("Debes indicar un fichero de log");
            }
            if (!is_writable($config['filename'])) {
                throw MedooException::debugFileNotWritable("No permite la escritura en '" . $config['filename'] . "'");
            }
            $this->debug_file = $config['filename'];
        }
        $this->debug_show_querys = DebugShowQuerys::_DEFAULT;
        if (isset($config['show-querys'])) {
            $this->debug_show_querys = $config['show-querys'];
        }
        if (isset($config['with-backtrace'])) {
            $this->debug_file_backtrace = $config['with-backtrace'];
        }
        if (isset($config['purge-on-init']) && $config['purge-on-init'] === TRUE && is_file($this->debug_file)) {
            unlink($this->debug_file);
        }
        if (isset($config['newline'])) {
            $this->debug_newline = $config['newline'];
        }
    }

    public function print($query, $map = []) {
        $log = '';
        $date = date('Y-m-d H:i:s');
        $sql = $this->generate($query, $map);
        $log .= "[$date] $sql" . $this->debug_newline;
        if ($this->debug_file_backtrace) {
            $backtrace = debug_backtrace();
            foreach ($backtrace as $bt) {
                $data = json_encode($bt);
                $log .= "\t$data" . $this->debug_newline;
            }
        }
        if ($this->debug_stdout === StdoutTypes::PHP_ECHO) {
            echo $log;
        } else if ($this->debug_stdout === StdoutTypes::FILE) {
            file_put_contents($this->debug_file, $log, FILE_APPEND);
        }
        // Sólo queremos loguar la primera query
        if ($this->debug_show_querys === DebugShowQuerys::SINGLE) {
            $this->debug_mode = false;
        }
    }
}
