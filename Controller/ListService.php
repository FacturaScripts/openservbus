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

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListService extends ListController
{
    public function getPageData(): array
    {
        $pageData = parent::getPageData();
        $pageData['menu'] = 'OpenServBus';
        $pageData['title'] = 'discretionary-services';
        $pageData['icon'] = 'fa-solid fa-book-reader';
        return $pageData;
    }

    protected function createViews()
    {
        $this->createViewService();
        $this->createViewServiceItinerary();
        $this->createViewServiceValuation();
        $this->createViewServiceValuationType();
        $this->createViewStop();
    }

    protected function createViewService($viewName = 'ListService'): void
    {
        $this->addView($viewName, 'Service', 'services', 'fa-solid fa-book-reader');
        $this->addSearchFields($viewName, ['idservicio', 'nombre']);
        $this->addOrderBy($viewName, ['nombre'], 'name', 1);
        $this->addOrderBy($viewName, ['idservicio'], 'code');
        $this->addOrderBy($viewName, ['fecha_desde', 'fecha_hasta'], 'fstart-fend');
        $this->addOrderBy($viewName, ['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $aceptados = [
            ['code' => '1', 'description' => 'accepted-yes'],
            ['code' => '0', 'description' => 'accepted-no'],
        ];
        $this->addFilterSelect($viewName, 'soloAceptados', 'accepted-all', 'aceptado', $aceptados);

        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->addFilterSelect($viewName, 'soloActivos', 'active-all', 'activo', $activo);

        $crearFtraSN = [
            ['code' => '1', 'description' => 'billable-yes'],
            ['code' => '0', 'description' => 'billable-no'],
        ];
        $this->addFilterSelect($viewName, 'crearFtra', 'billable-all', 'facturar_SN', $crearFtraSN);

        $this->addFilterAutocomplete($viewName, 'xCodCliente', 'client', 'codcliente', 'clientes', 'codcliente', 'nombre');
        $this->addFilterAutocomplete($viewName, 'xIdvehicle_type', 'vehicle_type', 'idvehicle_type', 'vehiculos', 'idvehicle_type', 'nombre');
        $this->addFilterAutocomplete($viewName, 'xIdservice_type', 'service-type', 'idservice_type', 'service_types', 'idservice_type', 'nombre');
        $this->addFilterAutocomplete($viewName, 'xIdempresa', 'company', 'idempresa', 'empresas', 'idempresa', 'nombre');
        $this->addFilterPeriod($viewName, 'porFechaInicio', 'date-start', 'fecha_desde');
        $this->addFilterPeriod($viewName, 'porFechaFin', 'date-end', 'fecha_hasta');
    }

    protected function createViewServiceItinerary($viewName = 'ListServiceItinerary'): void
    {
        $this->addView($viewName, 'ServiceItinerary', 'serv-discretionary-itinerary', 'fa-solid fa-road');
        $this->addSearchFields($viewName, ['nombre']);
        $this->addOrderBy($viewName, ['idservice', 'orden'], 'by-itinerary', 1);
        $this->addOrderBy($viewName, ['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->addFilterSelect($viewName, 'soloActivos', 'active-all', 'activo', $activo);

        $this->addFilterAutocomplete($viewName, 'xIdservice', 'service-discretionary', 'idservice', 'services', 'idservice', 'nombre');
    }

    protected function createViewServiceValuation($viewName = 'ListServiceValuation'): void
    {
        $this->addView($viewName, 'ServiceValuation', 'ratings', 'fa-solid fa-dollar-sign');
        $this->views[$viewName]->addOrderBy(['idservice', 'orden'], 'by-rating', 1);
        $this->views[$viewName]->addOrderBy(['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->views[$viewName]->addFilterSelect('soloActivos', 'active-all', 'activo', $activo);

        $this->views[$viewName]->addFilterAutocomplete('xIdservice', 'service-discretionary', 'idservice', 'services', 'idservice', 'nombre');
        $this->views[$viewName]->addFilterAutocomplete('xIdservice_valuation_type', 'concepts-valuation', 'idservice_valuation_type', 'service_valuation_types', 'idservice_valuation_type', 'nombre');
    }

    protected function createViewServiceValuationType($viewName = 'ListServiceValuationType'): void
    {
        $this->addView($viewName, 'ServiceValuationType', 'concepts-valuations', 'fa-solid fa-hand-holding-usd');
        $this->addSearchFields($viewName, ['nombre']);
        $this->addOrderBy($viewName, ['nombre'], 'name', 1);
        $this->addOrderBy($viewName, ['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->addFilterSelect($viewName, 'soloActivos', 'active-all', 'activo', $activo);
    }

    protected function createViewStop($viewName = 'ListStop'): void
    {
        $this->addView($viewName, 'Stop', 'stops', 'fa-solid fa-stopwatch');
        $this->addSearchFields($viewName, ['nombre', 'ciudad', 'provincia', 'codpostal', 'direccion']);
        $this->addOrderBy($viewName, ['nombre'], 'name', 1);
        $this->addOrderBy($viewName, ['provincia', 'ciudad', 'codpostal', 'direccion'], 'province-city-postal-code-address');
        $this->addOrderBy($viewName, ['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->addFilterSelect($viewName, 'soloActivos', 'active-all', 'activo', $activo);
    }
}