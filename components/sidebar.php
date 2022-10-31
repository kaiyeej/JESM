<?php
$request = $_SERVER['REQUEST_URI'];
$page = str_replace("/jesm/", "", $request);
?>
<div class="sidebar-menu">
    <ul class="menu">
        <li class="sidebar-title">Menu</li>
        
        <li
            class="sidebar-item <?= $page == "" || $page == "homepage" || $page == null ? 'active' : '' ?> ">
            <a href="./homepage" class='sidebar-link'>
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>
        

        <li class="sidebar-title">Master Data</li>
        
        <li class="sidebar-item <?= $page == 'customers' ? 'active' : '' ?>">
            <a href="./customers" class='sidebar-link'>
                <i class="bi bi-person-lines-fill"></i>
                <span>Customers</span>
            </a>
        </li>

        <li class="sidebar-item <?= $page == 'suppliers' ? 'active' : '' ?>">
            <a href="./suppliers" class='sidebar-link'>
                <i class="bi bi-people-fill"></i>
                <span>Suppliers</span>
            </a>
        </li>

        <li class="sidebar-item <?= $page == 'products' ? 'active' : '' ?>">
            <a href="./products" class='sidebar-link'>
                <i class="bi bi-boxes"></i>
                <span>Products</span>
            </a>
        </li>

        <li class="sidebar-item <?= $page == 'services' ? 'active' : '' ?>">
            <a href="./services" class='sidebar-link'>
                <i class="bi bi-wrench-adjustable-circle"></i>
                <span>Services</span>
            </a>
        </li>

        
        <li class="sidebar-title">Transactions</li>


        <li class="sidebar-item <?= $page == 'job-order' ? 'active' : '' ?>">
            <a href="./job-order" class='sidebar-link'>
                <i class="bi bi-list-columns-reverse"></i>
                <span>Job-order</span>
            </a>
        </li>


        <li class="sidebar-item <?= $page == 'purchase-order' ? 'active' : '' ?>">
            <a href="./purchase-order" class='sidebar-link'>
                <i class="bi bi-box2-fill"></i>
                <span>Purchase Order</span>
            </a>
        </li>


        <li class="sidebar-title">Reports</li>
        
        <li class="sidebar-item <?= $page == 'inventory-report' ? 'active' : '' ?>">
            <a href="./inventory-report2" class='sidebar-link'>
                <i class="bi bi-bar-chart-steps"></i>
                <span>Inventory Report</span>
            </a>
        </li>

        <li class="sidebar-item <?= $page == 'sales-order' ? 'active' : '' ?>">
            <a href="./sales-report2" class='sidebar-link'>
                <i class="bi bi-bar-chart-line-fill"></i>
                <span>Sales Report</span>
            </a>
        </li>

        
    </ul>
</div>