<?php
$request = $_SERVER['REQUEST_URI'];
$page = str_replace("/jesm/", "", $request);
?>
<div class="sidebar-menu">
    <ul class="menu">
        <li class="sidebar-title">Menu</li>

        <?php

            $Menus = new Menus();

            $Menus->sidebar('Dashboard', 'homepage', 'bi bi-grid-fill');
        
        ?>
        <li class="sidebar-title">Master Data</li>
        <?php
            // MASTER DATA
            $Menus->sidebar('Customers', 'customers', 'bi bi-person-lines-fill');
            $Menus->sidebar('Suppliers', 'suppliers', 'bi bi-people-fill');
            $Menus->sidebar('Products', 'products', 'bi bi-boxes');
            $Menus->sidebar('Services', 'services', 'bi bi-wrench-adjustable-circle');
        ?>
        <li class="sidebar-title">Transactions</li>
        <?php
            // TRANSACTION
            $Menus->sidebar('Job Order', 'job-order', 'bi bi-list-columns-reverse');
            $Menus->sidebar('Purchase Order', 'purchase-order', 'bi bi-box2-fill');
        ?>
        <li class="sidebar-title">Reports</li>
        <?php
            // REPORTS
            $Menus->sidebar('Inventory Report', 'inventory-report', 'bi bi-list-columns-reverse');
            $Menus->sidebar('Sales Report', 'sales-report', 'bi bi-graph-up');
            $Menus->sidebar('Audit Trails', 'logs', 'bi bi-person-lines-fill');
        ?>
        <li class="sidebar-title">Security</li>
        <?php
            // ADMIN
            $Menus->sidebar('User Accounts', 'users', 'bi bi-shield-fill-plus');
        ?>
    </ul>
</div>