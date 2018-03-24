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
                    <a href="#"><i class="fa fa-home"></i> Home <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                    <li><a href="<?=site_url('item/purchase')?>">Purchase</a></li>
                    <!-- <li><a href="<?//=site_url('item/localpurchase/insert')?>">Local Purchase</a></li> -->
                    <li><a href="<?=site_url('item/sale')?>">Sales</a></li>
                    <li><a href="<?=site_url('item/stock')?>">Sylhet Stock</a></li>
                    <li><a href="<?=site_url('item/importregister/insert')?>">Import</a></li>
                    <li><a href="<?=site_url('item/refund/insert')?>">Refund</a></li>
                    <li><a href="<?=site_url('item/stockbysupplier')?>">Supplier Stock</a></li>
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
                    <li><a href="<?=site_url('finance/receives/insert')?>">Receives( Joma )</a></li>
                    <li><a href="<?=site_url('finance/payments/insert')?>">Payments( Khoroch )</a></li>
                    <li><a href="<?=site_url('finance/customer/statement')?>">Statement</a></li>
                    <!-- <a href="<?//=site_url('finance/customer/statement')?>" class="btn btn-info" role="button">Customer Statement</a> -->
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
                        <li><a href="<?=site_url('report/customertransaction')?>">Customer Transaction Report</a></li>
                        <li><a href="#">Supplier Transaction Report</a></li>
                        <li><a href="<?=site_url('report/paymentreport')?>">Daily Payment Report</a></li>
                    </ul> 
            </li>
            <li><a href="<?=site_url('auth/logout')?>"><i class="fa fa-sign-out"></i> Logout</a></li>
        </ul>
    </div>
</div>