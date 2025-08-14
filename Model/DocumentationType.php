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


class DocumentationType extends ModelClass
{
    use ModelTrait;
    use OpenServBusModelTrait;

    /** @var bool */
    public $activo;

    /** @var string */
    public $fechaalta;

    /** @var string */
    public $fechabaja;

    /** @var string */
    public $fechamodificacion;

    /** @var string */
    public $fechacaducidad_obligarla;

    /** @var int */
    public $iddocumentation_type;

    /** @var string */
    public $motivobaja;

    /** @var string */
    public $nombre;

    /** @var string */
    public $observaciones;

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
        $this->fechacaducidad_obligarla = true;
        $this->useralta = Session::get('user')->nick ?? null;
    }

    public static function primaryColumn(): string
    {
        return 'iddocumentation_type';
    }

    public static function tableName(): string
    {
        return 'documentation_types';
    }

    public function test(): bool
    {
        if ($this->comprobarSiActivo() === false) {
            return false;
        }

        $this->nombre = Tools::noHtml($this->nombre);
        $this->observaciones = Tools::noHtml($this->observaciones);
        $this->motivobaja = Tools::noHtml($this->motivobaja);
        return parent::test();
    }

    public function url(string $type = 'auto', string $list = 'ConfigOpenServBus'): string
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