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
use FacturaScripts\Core\Where;

class ListHelper extends ListController
{
    public function getPageData(): array
    {
        $pageData = parent::getPageData();
        $pageData['menu'] = 'OpenServBus';
        $pageData['title'] = 'files';
        $pageData['icon'] = 'fa-solid fa-archive';
        return $pageData;
    }

    protected function createViews()
    {
        $this->createViewHelper();
        $this->createViewGarage();
        $this->createViewDepartment();
        $this->createViewCollaborator();
        $this->createViewIdentificationMean();
    }

    protected function createViewCollaborator($viewName = 'ListCollaborator'): void
    {
        $this->addView($viewName, 'Collaborator', 'collaborator', 'fa-solid fa-business-time');
        $this->addSearchFields($viewName, ['codproveedor', 'nombre']);
        $this->addOrderBy($viewName, ['codproveedor'], 'cod-supplier');
        $this->addOrderBy($viewName, ['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->addFilterSelect($viewName, 'soloActivos', 'active-all', 'activo', $activo);
    }

    protected function createViewDepartment($viewName = 'ListDepartment'): void
    {
        $this->addView($viewName, 'Department', 'departments', 'fa-solid fa-book-reader');
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

    protected function createViewGarage($viewName = 'ListGarage'): void
    {
        $this->addView($viewName, 'Garage', 'garages', 'fa-solid fa-warehouse');
        $this->addSearchFields($viewName, ['nombre', 'direccion']);
        $this->addOrderBy($viewName, ['nombre'], 'Nombre', 1);
        $this->addOrderBy($viewName, ['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->addFilterSelect($viewName, 'soloActivos', 'active-all', 'activo', $activo);

        $this->addFilterAutocomplete($viewName, 'xIdEmpresa', 'company', 'idempresa', 'empresas', 'idempresa', 'nombre');
    }

    protected function createViewHelper($viewName = 'ListHelper'): void
    {
        $this->addView($viewName, 'Helper', 'monitors', 'fa-solid fa-user-graduate');
        $this->addOrderBy($viewName, ['fechaalta', 'fechamodificacion'], 'fhigh-fmodiff');

        // Filtros
        $activo = [
            ['code' => '1', 'description' => 'active-yes'],
            ['code' => '0', 'description' => 'active-no'],
        ];
        $this->addFilterSelect($viewName, 'soloActivos', 'active-all', 'activo', $activo);

        $status = [
            ['label' => 'collaborators-employess-all', 'where' => []],
            ['label' => 'collaborators-only', 'where' => [Where::column('idcollaborator', '0', '>')]],
            ['label' => 'employees-only', 'where' => [Where::column('idemployee', '0', '>')]]
        ];
        $this->addFilterSelectWhere($viewName, 'status', $status);
    }

    protected function createViewIdentificationMean($viewName = 'ListIdentificationMean'): void
    {
        $this->addView($viewName, 'IdentificationMean', 'means-of-identification', 'fa-regular fa-hand-point-right');
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
}
