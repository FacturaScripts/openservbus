<?php
    
// SI MODIFICAMOS ESTE CONTROLADOR TENEMOS QUE VER SI HAY QUE HACER LOS MISMOS CAMBIOS EN ListAdvertisment_user2.php
    
namespace FacturaScripts\Plugins\OpenServBus\Controller;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListAdvertismentUser extends ListController {
    
    // Para presentar la pantalla del controlador
    // Estará en el el menú principal bajo \\OpenServBus\Archivos\Empleados
    public function getPageData(): array {
        $pageData = parent::getPageData();
        $pageData['menu'] = 'OpenServBus';
        $pageData['title'] = 'Avisos';
        $pageData['icon'] = 'fas fa-exclamation-triangle';
        return $pageData;
    }
    
    protected function createViews() {
        $this->createAdvertismentUser();
        $this->createAdvertismentUserSession();
    }
    
    protected function createAdvertismentUser($viewName = 'ListAdvertismentUser')
    {
        $this->addView($viewName, 'AdvertismentUser', 'Avisos', 'fas fa-exclamation-triangle');
        $this->addSearchFields($viewName, ['nombre']);
        $this->addOrderBy($viewName, ['nombre', 'inicio', 'fin'], 'Aviso + Inicio + Fin', 1);
        $this->addOrderBy($viewName, ['nick', 'nombre', 'inicio', 'fin'], 'Usuario + Aviso + Inicio + Fin');
        $this->addOrderBy($viewName, ['codrole', 'nombre', 'inicio', 'fin'], 'Grupo Usuarios + Aviso + Inicio + Fin');
        $this->addOrderBy($viewName, ['fechaalta', 'fechamodificacion'], 'F.Alta+F.MOdif.');

        // Filtros
        $this->addFilterAutocomplete($viewName, 'xNick', 'Usuario', 'nick', 'users', 'nick', 'nick');
        $this->addFilterAutocomplete($viewName, 'xCodRole', 'Grupos de usuarios', 'codrole', 'roles', 'codrole', 'descripcion');
        $this->addFilterDatePicker($viewName, 'inicio', 'Avisos desde ...', 'inicio');
        $this->addFilterDatePicker($viewName, 'fin', 'Avisos hasta ...', 'fin');

        $activo = [
            ['code' => '1', 'description' => 'Activos = SI'],
            ['code' => '0', 'description' => 'Activos = NO'],
        ];
        $this->addFilterSelect($viewName, 'soloActivos', 'Activos = TODOS', 'activo', $activo);
    }

    protected function createAdvertismentUserSession($viewName = 'ListAdvertismentUser-2')
    {
        $this->addView($viewName, 'AdvertismentUser', 'Avisos Usuario/Sesión', 'fas fa-exclamation-triangle');
        $this->addSearchFields($viewName, ['nombre']);
        $this->addOrderBy($viewName, ['nombre', 'inicio', 'fin'], 'Aviso + Inicio + Fin', 1);
        $this->addOrderBy($viewName, ['codrole', 'nombre', 'inicio', 'fin'], 'Grupo Usuarios + Aviso + Inicio + Fin');
        $this->addOrderBy($viewName, ['fechaalta', 'fechamodificacion'], 'F.Alta+F.MOdif.');

        // Filtros
        $this->addFilterAutocomplete($viewName, 'xCodRole', 'Grupos de usuarios', 'codrole', 'roles', 'codrole', 'descripcion');
        $this->addFilterDatePicker($viewName, 'inicio', 'Avisos desde ...', 'inicio');
        $this->addFilterDatePicker($viewName, 'fin', 'Avisos hasta ...', 'fin');

        $activo = [
            ['code' => '1', 'description' => 'Activos = SI'],
            ['code' => '0', 'description' => 'Activos = NO'],
        ];
        $this->addFilterSelect($viewName, 'soloActivos', 'Activos = TODOS', 'activo', $activo);
    }

    protected function loadData($viewName, $view)
    {
        switch ($viewName) {
            case 'ListAdvertismentUser-2':
                $usuario = $this->user->nick;
                $where = [new DatabaseWhere('nick', $usuario)];
                $view->loadData('', $where);
                break;

            default:
                parent::loadData($viewName, $view);
                break;
        }
    }
}