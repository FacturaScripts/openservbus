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

class Helper extends ModelClass
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

    /** @var int */
    public $idcollaborator;

    /** @var int */
    public $idemployee;

    /** @var int */
    public $idhelper;

    /** @var string */
    public $motivobaja;

    /** @var string */
    public $observaciones;

    /** @var string */
    public $useralta;

    /** @var string */
    public $userbaja;

    /** @var string */
    public $usermodificacion;

    public function __get($name)
    {
        if ($name === 'nombre') {
            if (false === empty($this->idcollaborator)) {
                $collaborator = $this->getCollaborator();
                return $collaborator->getProveedor()->nombre;
            } elseif (false === empty($this->idemployee)) {
                $employee = $this->getEmployee();
                return $employee->nombre;
            }
        }
        return null;
    }

    public function clear(): void
    {
        parent::clear();
        $this->activo = true;
        $this->fechaalta = Tools::date();
        $this->useralta = Session::get('user')->nick ?? null;
    }

    public function install(): string
    {
        new EmployeeOpen();
        new Collaborator();
        return parent::install();
    }

    public static function primaryColumn(): string
    {
        return 'idhelper';
    }

    public static function tableName(): string
    {
        return 'helpers';
    }

    public function test(): bool
    {
        // Exigimos que se introduzca idempresa o idcollaborator
        if ((empty($this->idemployee)) && (empty($this->idcollaborator))) {
            Tools::log()->error('confirm-employee-or-collaborating');
            return false;
        }

        // No debe de elegir empleado y colaborador a la vez
        if ((!empty($this->idemployee)) && (!empty($this->idcollaborator))) {
            Tools::log()->error('employee-or-collaborating-bat-not-both');
            return false;
        }

        if ($this->comprobarSiActivo() === false) {
            return false;
        }
        
        $this->observaciones = Tools::noHtml($this->observaciones);
        $this->motivobaja = Tools::noHtml($this->motivobaja);
        return parent::test();
    }

    protected function getCollaborator(): Collaborator
    {
        $collaborator = new Collaborator();
        $collaborator->load($this->idcollaborator);
        return $collaborator;
    }

    protected function getEmployee(): EmployeeOpen
    {
        $employee = new EmployeeOpen();
        $employee->load($this->idemployee);
        return $employee;
    }

    protected function saveUpdate(array $values = []): bool
    {
        $this->usermodificacion = Session::get('user')->nick ?? null;
        $this->fechamodificacion = Tools::date();
        return parent::saveUpdate();
    }
}