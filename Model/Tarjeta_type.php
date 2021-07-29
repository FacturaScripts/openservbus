<?php

namespace FacturaScripts\Plugins\OpenServBus\Model; 

use FacturaScripts\Core\Model\Base;

class Tarjeta_type extends Base\ModelClass {
    use Base\ModelTrait;

    public $idtarjeta_type;
        
    public $user_fecha;
    public $user_nick;
    public $fechaalta;
    public $useralta;
    public $fechamodificacion;
    public $usermodificacion;
    public $activo;
    public $fechabaja;
    public $userbaja;

    public $de_pago;
    public $nombre;
    public $observaciones;
    
    // función que inicializa algunos valores antes de la vista del controlador
    public function clear() {
        parent::clear();
        
        $this->activo = true; // Por defecto estará activo
        $this->de_pago = false; // Por defecto será de no pago
    }
    
    // función que devuelve el id principal
    public static function primaryColumn(): string {
        return 'idtarjeta_type';
    }
    
    // función que devuelve el nombre de la tabla
    public static function tableName(): string {
        return 'tarjeta_types';
    }

    // Para realizar cambios en los datos antes de guardar por modificación
    protected function saveUpdate(array $values = [])
    {
        // Siendo un alta o una modificación, siempre guardamos los datos de modificación
        $this->usermodificacion = $this->user_nick; 
        $this->fechamodificacion = $this->user_fecha; 
        
        $this->comprobarSiActivo();
        
        return parent::saveUpdate($values);
    }

    // Para realizar cambios en los datos antes de guardar por alta
    protected function saveInsert(array $values = [])
    {
        // Creamos el nuevo id
        if (empty($this->idtarjeta_type)) {
            $this->idtarjeta_type = $this->newCode();
        }

        // Rellenamos los datos de alta
        $this->useralta = $this->user_nick; 
        $this->fechaalta = $this->user_fecha; 
        
        // Siendo un alta o una modificación, siempre guardamos los datos de modificación
        $this->usermodificacion = $this->user_nick; 
        $this->fechamodificacion = $this->user_fecha; 
        
        $this->comprobarSiActivo();
        
        return parent::saveInsert($values);
    }
    
    public function test()
    {
        // Evitar la inyección de SQL
        $utils = $this->toolBox()->utils();
        $this->nombre = $utils->noHtml($this->nombre);
        $this->observaciones = $utils->noHtml($this->observaciones);
        
        $this->actualizarEnTarjetas_DePago();

        return parent::test();
    }


    // ** ********************************** ** //
    // ** FUNCIONES CREADAS PARA ESTE MODELO ** //
    // ** ********************************** ** //
    protected function comprobarSiActivo()
    {
        if ($this->activo == false) {
            $this->fechabaja = $this->fechamodificacion;
            $this->userbaja = $this->usermodificacion;
        } else { // Por si se vuelve a poner Activo = true
            $this->fechabaja = null;
            $this->userbaja = null;
        }
    }
    
    private function actualizarEnTarjetas_DePago()
    {
        $de_pago = 1;
        if ($this->de_pago == false){
            $de_pago = 0;
        }
        
        // Rellenamos el de_pago de tabla tarjetas
        $sql = "UPDATE tarjetas SET tarjetas.de_pago = " . $de_pago . " WHERE tarjetas.idtarjeta_type = " . $this->idtarjeta_type . ";";
        self::$dataBase->exec($sql);
    }
      
}