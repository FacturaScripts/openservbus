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

namespace FacturaScripts\Plugins\OpenServBus\Controller;

use FacturaScripts\Core\Lib\ExtendedController\PanelController;

/**
 * @author Daniel Fernández Giménez <hola@danielfg.es>
 */
class ConfigOpenServBus extends PanelController
{
    public function getPageData(): array
    {
        $pageData = parent::getPageData();
        $pageData['menu'] = 'admin';
        $pageData['title'] = 'OpenServBus';
        $pageData['icon'] = 'fa-solid fa-bus';
        return $pageData;
    }

    protected function createViews()
    {
        $this->setTemplate('EditSettings');
        $this->createViewDocumentationType();
        $this->createViewEmployeeContractType();
        $this->createViewFuelType();
        $this->createViewServiceType();
        $this->createViewTarjetaType();
        $this->createViewVehicleEquipamentType();
        $this->createViewVehicleType();
    }

    protected function createViewDocumentationType($viewName = 'ListDocumentationType'): void
    {
        $this->addListView($viewName, 'DocumentationType', 'documentation_type', 'fa-solid fa-address-card');
        $this->views[$viewName]->addSearchFields(['nombre']);
        $this->views[$viewName]->addOrderBy(['nombre'], 'name', 1);
        $this->views[$viewName]->addOrderBy(['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $this->views[$viewName]->addFilterCheckbox('fechacaducidad_obligarla', 'Obligar-F.Caducidad', 'fechacaducidad_obligarla');

        $fecha_caducidad = [
            ['code' => '1', 'description' => 'Obligar F.Caducidad = SI'],
            ['code' => '0', 'description' => 'Obligar F.Caducidad = NO'],
        ];
        $this->views[$viewName]->addFilterSelect('soloFechaCaducidad', 'Obligar F.Caducidad = TODOS', 'fechacaducidad_obligarla', $fecha_caducidad);

        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->views[$viewName]->addFilterSelect('soloActivos', 'active-all', 'activo', $activo);
    }

    protected function createViewEmployeeContractType($viewName = 'ListEmployeeContractType'): void
    {
        $this->addListView($viewName, 'EmployeeContractType', 'employee_contract_type', 'fa-solid fa-file-signature');
        $this->views[$viewName]->addSearchFields(['nombre']);
        $this->views[$viewName]->addOrderBy(['nombre'], 'name', 1);
        $this->views[$viewName]->addOrderBy(['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->views[$viewName]->addFilterSelect('soloActivos', 'active-all', 'activo', $activo);
    }

    protected function createViewFuelType($viewName = 'ListFuelType'): void
    {
        $this->addListView($viewName, 'FuelType', 'fuel_type', 'fa-solid fa-gas-pump');
        $this->views[$viewName]->addSearchFields(['nombre']);
        $this->views[$viewName]->addOrderBy(['nombre'], 'name', 1);
        $this->views[$viewName]->addOrderBy(['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->views[$viewName]->addFilterSelect('soloActivos', 'active-all', 'activo', $activo);
    }

    protected function createViewServiceType($viewName = 'ListServiceType'): void
    {
        $this->addListView($viewName, 'ServiceType', 'service-type', 'fa-solid fa-dolly');
        $this->views[$viewName]->addSearchFields(['nombre']);
        $this->views[$viewName]->addOrderBy(['nombre'], 'name', 1);
        $this->views[$viewName]->addOrderBy(['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->views[$viewName]->addFilterSelect('soloActivos', 'active-all', 'activo', $activo);
    }

    protected function createViewTarjetaType($viewName = 'ListTarjetaType'): void
    {
        $this->addListView($viewName, 'TarjetaType', 'tarjeta_type', 'fa-regular fa-credit-card');
        $this->views[$viewName]->addSearchFields(['nombre']);
        $this->views[$viewName]->addOrderBy(['nombre'], 'name', 1);
        $this->views[$viewName]->addOrderBy(['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->views[$viewName]->addFilterSelect('soloActivos', 'active-all', 'activo', $activo);

        $esDePago = [
            ['code' => '1', 'description' => 'De pago = SI'],
            ['code' => '0', 'description' => 'De pago = NO'],
        ];
        $this->views[$viewName]->addFilterSelect('esDepago', 'De pago = TODO', 'de_pago', $esDePago);
    }

    protected function createViewVehicleEquipamentType($viewName = 'ListVehicleEquipamentType'): void
    {
        $this->addListView($viewName, 'VehicleEquipamentType', 'vehicle_equipament_type', 'fa-solid fa-wheelchair');
        $this->views[$viewName]->addSearchFields(['nombre']);
        $this->views[$viewName]->addOrderBy(['nombre'], 'name', 1);
        $this->views[$viewName]->addOrderBy(['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->views[$viewName]->addFilterSelect('soloActivos', 'active-all', 'activo', $activo);
    }

    protected function createViewVehicleType($viewName = 'ListVehicleType'): void
    {
        $this->addListView($viewName, 'VehicleType', 'vehicle_type', 'fa-solid fa-tractor');
        $this->views[$viewName]->addSearchFields(['nombre']);
        $this->views[$viewName]->addOrderBy(['nombre'], 'name', 1);
        $this->views[$viewName]->addOrderBy(['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->views[$viewName]->addFilterSelect('soloActivos', 'active-all', 'activo', $activo);
    }

    protected function loadData($viewName, $view)
    {
        $this->hasData = true;

        switch ($viewName) {
            case 'ConfigOpenServBus':
                $view->loadData('openservbus');
                $view->model->name = 'openservbus';
                break;

            case 'EditEstadoReservaTour':
            case 'EditEstadoServicioTour':
            case 'ListDocumentationType':
            case 'ListEmployeeContractType':
            case 'ListFuelType':
            case 'ListServiceType':
            case 'ListTarjetaType':
            case 'ListVehicleEquipamentType':
            case 'ListVehicleType':
                $view->loadData();
                break;
        }
    }
}