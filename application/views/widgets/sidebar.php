<div class="navbar-default sidebar" role="navigation">
    <div class="logo"><img src="<?=base_url('assets/images/logo.jpg')?>" class="img img-responsive"/></div>
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
<!--                <a href="#"><i class="fa fa-bar-chart-o fa-flask"></i> Medicine <span class="fa arrow"></span></a>-->
                <a href="#"><i class="fa fa-bar-chart-o fa-flask"></i> Items <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="<?=site_url('item/purchase')?>">Purchase</a></li>
                    <li><a href="<?=site_url('item/sale')?>">Sales</a></li>
                    <li><a href="<?=site_url('item/stock')?>">Item Stock</a></li>
                    <li><a href="<?=site_url('item/importregister/insert')?>">Import Register</a></li>
                    <li><a href="<?=site_url('item/stockbysupplier')?>">Stock By Supplier</a></li>
                </ul>
            </li>
<!--
            <li>
                <a href="#"><i class="fa fa-tag"></i> Raw Material <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="<?//=site_url('raw/stock')?>">Stock Status</a></li>
                    <li><a href="<?//=site_url('raw/in')?>">Stock In</a></li>
                    <li><a href="<?//=site_url('raw/out')?>">Stock Out</a></li>
                </ul>
            </li>
-->
            <li>
                <a href="#"><i class="fa fa-dollar"></i> Accounts<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="<?=site_url('finance/receives/insert')?>">Customer</a></li>
                    <li><a href="<?=site_url('finance/payments/insert')?>">Supplier</a></li>
                    <li><a href="<?=site_url('finance/statement')?>">Statement</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-database"></i> Master Data<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="<?=site_url('item/index')?>">Items List</a></li>
                    <li><a href="<?=site_url('item/category')?>">Category List</a></li>
                    <!-- <li><a href="<?//=site_url('master/sales')?>">Sale Price Revision</a></li> -->
<!--                    <li><a href="<?//=site_url('raw/index')?>">Raw Materials</a></li>-->
                    <li><a href="<?=site_url('master/suppliers')?>">Suppliers List</a></li>
                    <li><a href="<?=site_url('master/customers')?>">Customers List</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> Configuration <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?=site_url('config/index')?>">Delivery Type</a></li>
                        <li><a href="<?=site_url('master/warehouse')?>">Warehouse</a></li>
                    </ul> 
            </li>
            <li>
                <a href="#"><i class="fa fa-list"></i> Reports <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href=""></a></li>
                    </ul> 
            </li>
            <li><a href="<?=site_url('auth/logout')?>"><i class="fa fa-sign-out"></i> Logout</a></li>
        </ul>
    </div>
</div>