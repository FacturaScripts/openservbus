<?php
/**
 * This file is part of OpenServBus plugin for FacturaScripts
 * Copyright (C) 2022 Carlos Garcia Gomez <carlos@facturascripts.com>
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

use FacturaScripts\Core\Model\Base\ModelClass;
use FacturaScripts\Core\Model\Base\ModelTrait;
use FacturaScripts\Core\Session;

/**
 * @author Daniel Fernández Giménez <hola@danielfg.es>
 */
class ReservaTour extends ModelClass
{
    use ModelTrait;

    /** @var bool */
    public $closed;

    /** @var string */
    public $creationdate;

    /** @var int */
    public $id;

    /** @var int */
    public $idestado;

    /** @var int */
    public $idoperador;

    /** @var string */
    public $lastnick;

    /** @var string */
    public $lastupdate;

    /** @var string */
    public $nick;

    public function clear() 
    {
        parent::clear();
        $this->closed = false;
        $this->creationdate = date(self::DATETIME_STYLE);
        $this->nick = Session::get('user')->nick ?? null;
    }

    public function getEstado(): EstadoReservaTour
    {
        $estado = new EstadoReservaTour();
        $estado->loadFromCode($this->idestado);
        return $estado;
    }

    public function getOperador(): TourOperador
    {
        $operador = new TourOperador();
        $operador->loadFromCode($this->idoperador);
        return $operador;
    }

    public function install(): string
    {
        new TourOperador();
        new EstadoReservaTour();
        return parent::install();
    }

    public static function primaryColumn(): string
    {
        return "id";
    }

    public static function tableName(): string
    {
        return "tour_reservas";
    }

    public function url(string $type = 'auto', string $list = 'ListTourOperador'): string
    {
        return parent::url($type, $list . '?activetab=List');
    }

    protected function saveInsert(array $values = []): bool
    {
        $this->lastnick = null;
        $this->lastupdate = null;
        return parent::saveInsert($values);
    }

    protected function saveUpdate(array $values = []): bool
    {
        $this->lastnick = Session::get('user')->nick ?? null;
        $this->lastupdate = date(self::DATETIME_STYLE);
        return parent::saveUpdate($values);
    }
}