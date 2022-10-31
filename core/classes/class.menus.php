<?php
class Menus extends Connection
{
    public function lists()
    {
        $this->menus = array(
            'master-data' => array(
                array('url' => 'customers', 'name' => 'Customers', 'class_name' => 'Customers', 'has_detail' => 0),
                array('url' => 'suppliers', 'name' => 'Suppliers', 'class_name' => 'Suppliers', 'has_detail' => 0),
                array('url' => 'products', 'name' => 'Products', 'class_name' => 'Products', 'has_detail' => 0),
                array('url' => 'services', 'name' => 'Services', 'class_name' => 'Services', 'has_detail' => 0),
                
            ),
            'transaction' => array(
                array('url' => 'purchase-order', 'name' => 'Purchase Order', 'class_name' => 'PurchaseOrder', 'has_detail' => 1),
            ),
            'report' => array(

                array('url' => 'inventory-report', 'name' => 'Inventory Report', 'class_name' => 'InventoryReport', 'has_detail' => 0),
            ),
            'admin' => array(
                array('url' => 'users', 'name' => 'User Account', 'class_name' => 'Users', 'has_detail' => 0),
                array('url' => 'settings', 'name' => 'Settings', 'class_name' => 'Settings', 'has_detail' => 0),
            ),
        );

        return $this->menus;
    }

    public function routes($page, $dir)
    {
        $this->lists();
        $levels = ['master-data', 'transaction', 'report', 'admin'];

        if ($page == 'homepage') {
            $this->dir = $dir;
            $this->route_settings = [];
        } else {
            $has_page = false;
            $main_column = '';
            foreach ($levels as $main_column_) {
                if (array_search($page, array_column($this->menus[$main_column_], 'url')) !== FALSE) {
                    $main_column = $main_column_;
                    $has_page = true;
                    break;
                }
            }
            if ($has_page) {
                $index = array_search($page, array_column($this->menus[$main_column], 'url'));
                $list_data = $this->menus[$main_column][$index];

                $UserPrivileges = new UserPrivileges();
                if ($UserPrivileges->check($page, $_SESSION['user']['id']) == 1) {
                    $this->dir = $dir;
                    $this->route_settings = [
                        'class_name' => $list_data['class_name'],
                        'has_detail' => $list_data['has_detail']
                    ];
                } else {
                    $this->dir = 'pages/restricted/index.php';
                    $this->route_settings = [];
                }
            } else {
                $this->dir = 'pages/404/index.php';
                $this->route_settings = [];
            }
        }
    }

    public function sidebar($name, $url, $ti)
    {
        $UserPrivileges = new UserPrivileges();
        if ($UserPrivileges->check($url, $_SESSION['user']['id']) == 1) {
            echo '<li class="nav-item">
            <a class="nav-link" href="./' . $url . '">
                <i class="ti ti-' . $ti . ' menu-icon"></i>
                <span class="menu-title">' . $name . '</span>
            </a>
        </li>';
        }
    }

    public function sidebar_parent($name, $ti, $child)
    {
        $UserPrivileges = new UserPrivileges();

        $ui = str_replace(' ', '', strtolower($name));
        $child_label = "";
        foreach ($child as $row) {
            if ($UserPrivileges->check($row[1], $_SESSION['user']['id']) == 1) {
                $child_label .= '<li class="nav-item"> <a class="nav-link" href="./' . $row[1] . '">' . $row[0] . '</a></li>';
            }
        }
        if ($child_label != '') {
            echo '<li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-' . $ui . '" aria-expanded="false" aria-controls="ui-' . $ui . '">
                <i class="ti ti-' . $ti . ' menu-icon"></i>
                <span class="menu-title">' . $name . '</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-' . $ui . '">
                <ul class="nav flex-column sub-menu">' . $child_label . '</ul>
            </div>
        </li>';
        }
    }
}
