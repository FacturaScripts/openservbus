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

class VehicleEquipament extends ModelClass
{
    use ModelTrait;
    use OpenServBusModelTrait;

    public $activo;

    public $fechaalta;

    public $fechabaja;

    public $fechamodificacion;

    public $idvehicle;

    public $idvehicle_equipament;

    public $idvehicle_equipament_type;

    public $motivobaja;

    public $observaciones;

    public $useralta;

    public $userbaja;

    public $usermodificacion;

    public function clear(): void
    {
        parent::clear();
        $this->activo = true;
        $this->fechaalta = Tools::date();
        $this->useralta = Session::get('user')->nick ?? null;
    }

    public function getVehicle(): Vehicle
    {
        $vehicle = new Vehicle();
        $vehicle->load($this->idvehicle);
        return $vehicle;
    }

    public function install(): string
    {
        new Vehicle();
        new VehicleEquipamentType();
        return parent::install();
    }

    public static function primaryColumn(): string
    {
        return 'idvehicle_equipament';
    }

    public static function tableName(): string
    {
        return 'vehicle_equipaments';
    }

    public function test(): bool
    {
        if ($this->comprobarSiActivo() === false) {
            return false;
        }

        $this->observaciones = Tools::noHtml($this->observaciones);
        $this->motivobaja = Tools::noHtml($this->motivobaja);
        return parent::test();
    }

    public function url(string $type = 'auto', string $list = 'ListVehicle'): string
    {
        return parent::url($type, $list . '?activetab=List');
    }

    protected function saveUpdate(array $values = []): bool
    {
        $this->usermodificacion = Session::get('user')->nick ?? null;
        $this->fechamodificacion = Tools::date();
        return parent::saveUpdate();
    }
}