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

class Garage extends ModelClass
{
    use ModelTrait;
    use OpenServBusModelTrait;

    /** @var bool */
    public $activo;

    /** @var string */
    public $apartado;

    /** @var string */
    public $ciudad;

    /** @var string */
    public $codpais;

    /** @var string */
    public $codpostal;

    /** @var string */
    public $direccion;

    /** @var string */
    public $email;

    /** @var string */
    public $fax;

    /** @var string */
    public $fechaalta;

    /** @var string */
    public $fechabaja;

    /** @var string */
    public $fechamodificacion;

    /** @var int */
    public $idempresa;

    /** @var int */
    public $idgarage;

    /** @var string */
    public $motivobaja;

    /** @var string */
    public $nombre;

    /** @var string */
    public $observaciones;

    /** @var string */
    public $provincia;

    /** @var string */
    public $telefono1;

    /** @var string */
    public $telefono2;

    /** @var string */
    public $useralta;

    /** @var string */
    public $userbaja;

    /** @var string */
    public $usermodificacion;

    /** @var string */
    public $web;

    public function clear(): void
    {
        parent::clear();
        $this->activo = true;
        $this->codpais = Tools::settings('default', 'codpais');
        $this->fechaalta = Tools::date();
        $this->useralta = Session::get('user')->nick ?? null;
    }

    public static function primaryColumn(): string
    {
        return 'idgarage';
    }

    public static function tableName(): string
    {
        return 'garages';
    }

    public function test(): bool
    {
        if ($this->comprobarSiActivo() === false) {
            return false;
        }

        if (empty($this->idempresa)) {
            $this->idempresa = Tools::settings('default', 'idempresa');
        }

        $this->nombre = Tools::noHtml($this->nombre);
        $this->ciudad = Tools::noHtml($this->ciudad);
        $this->provincia = Tools::noHtml($this->provincia);
        $this->codpais = Tools::noHtml($this->codpais);
        $this->codpostal = Tools::noHtml($this->codpostal);
        $this->apartado = Tools::noHtml($this->apartado);
        $this->direccion = Tools::noHtml($this->direccion);
        $this->telefono1 = Tools::noHtml($this->telefono1);
        $this->telefono2 = Tools::noHtml($this->telefono2);
        $this->fax = Tools::noHtml($this->fax);
        $this->email = Tools::noHtml($this->email);
        $this->web = Tools::noHtml($this->web);
        $this->observaciones = Tools::noHtml($this->observaciones);
        $this->motivobaja = Tools::noHtml($this->motivobaja);
        return parent::test();
    }

    public function url(string $type = 'auto', string $list = 'ListHelper'): string
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