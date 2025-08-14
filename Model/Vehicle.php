<?php
/**
 * This file is part of OpenServBus plugin for FacturaScripts
 * Copyright (C) 2021-2025 Carlos Garcia Gomez <carlos@facturascripts.com>
 * Copyright (C) 2021 Jerónimo Pedro Sánchez Manzano <socger@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see http://www.gnu.org/licenses/.
 */

namespace FacturaScripts\Plugins\OpenServBus\Model;

use FacturaScripts\Core\Session;
use FacturaScripts\Core\Template\ModelClass;
use FacturaScripts\Core\Template\ModelTrait;
use FacturaScripts\Core\Tools;

class Vehicle extends ModelClass
{
    use ModelTrait;
    use OpenServBusModelTrait;

    /** @var bool */
    public $activo;

    /** @var string */
    public $carroceria;

    /** @var string */
    public $cod_vehicle;

    /** @var string */
    public $configuraciones_especiales;

    /** @var string */
    public $fechaalta;

    /** @var string */
    public $fechabaja;

    /** @var string */
    public $fechamodificacion;

    /** @var string */
    public $fecha_km_actuales;

    /** @var string */
    public $fecha_matriculacion_actual;

    /** @var string */
    public $fecha_matriculacion_primera;

    /** @var int */
    public $idcollaborator;

    /** @var int */
    public $iddriver_usual;

    /** @var int */
    public $idempresa;

    /** @var int */
    public $idfuel_type;

    /** @var int */
    public $idgarage;

    /** @var int */
    public $idvehicle;

    /** @var int */
    public $idvehicle_type;

    /** @var int */
    public $km_actuales;

    /** @var string */
    public $matricula;

    /** @var string */
    public $motivobaja;

    /** @var string */
    public $motor_chasis;

    /** @var string */
    public $nombre;

    /** @var string */
    public $numero_bastidor;

    /** @var string */
    public $observaciones;

    /** @var string */
    public $numero_obra;

    /** @var int */
    public $plazas_ofertables;

    /** @var string */
    public $plazas_segun_ficha_tecnica;

    /** @var int */
    public $plazas_segun_permiso;

    /** @var string */
    public $useralta;

    /** @var string */
    public $userbaja;

    /** @var string */
    public $usermodificacion;

    public function clear(): void
    {
        parent::clear();
        $this->activo = true;
        $this->fechaalta = Tools::date();
        $this->km_actuales = 0;
        $this->useralta = Session::get('user')->nick ?? null;
    }

    public function install(): string
    {
        new Collaborator();
        new Garage();
        new FuelType();

        return parent::install();
    }

    public static function primaryColumn(): string
    {
        return 'idvehicle';
    }

    public static function tableName(): string
    {
        return 'vehicles';
    }

    public function test(): bool
    {
        if ($this->comprobarSiActivo() === false) {
            return false;
        }

        // Comprobamos que el código se ha introducido correctamente
        if (!empty($this->cod_vehicle) && 1 !== \preg_match('/^[A-Z0-9_\+\.\-]{1,10}$/i', $this->cod_vehicle)) {
            Tools::log()->error(
                'invalid-alphanumeric-code',
                ['%value%' => $this->cod_vehicle, '%column%' => 'cod_vehicle', '%min%' => '1', '%max%' => '10']
            );
            return false;
        }

        // Exigimos que se introduzca idempresa o idcollaborator
        if ((empty($this->idempresa)) && (empty($this->idcollaborator))) {
            Tools::log()->error('confirm-vehicle-is-collaboratin-or-our');
            return false;
        }

        if ((!empty($this->idempresa)) && (!empty($this->idcollaborator))) {
            Tools::log()->error('the-vehicle-is-collaboratin-or-our-bat-not-both');
            return false;
        }

        // Si Fecha Matriculación Actual está vacía, pero Fecha Matriculación Primera está rellena, pues
        // Fecha Matriculacion Actual = Fecha Matriculación Primera
        if (empty($this->fecha_matriculacion_actual)) {
            if (!empty($this->fecha_matriculacion_primera)) {
                Tools::log()->info('current-registration-date-is-empty');
                $this->fecha_matriculacion_actual = $this->fecha_matriculacion_primera;
            }
        }

        $this->cod_vehicle = Tools::noHtml($this->cod_vehicle);
        $this->nombre = Tools::noHtml($this->nombre);
        $this->matricula = Tools::noHtml($this->matricula);
        $this->motor_chasis = Tools::noHtml($this->motor_chasis);
        $this->numero_bastidor = Tools::noHtml($this->numero_bastidor);
        $this->carroceria = Tools::noHtml($this->carroceria);
        $this->numero_obra = Tools::noHtml($this->numero_obra);
        $this->plazas_segun_ficha_tecnica = Tools::noHtml($this->plazas_segun_ficha_tecnica);
        $this->configuraciones_especiales = Tools::noHtml($this->configuraciones_especiales);
        $this->observaciones = Tools::noHtml($this->observaciones);
        $this->motivobaja = Tools::noHtml($this->motivobaja);
        return parent::test();
    }

    protected function saveUpdate(array $values = []): bool
    {
        $this->usermodificacion = Session::get('user')->nick ?? null;
        $this->fechamodificacion = Tools::date();
        return parent::saveUpdate();
    }
}