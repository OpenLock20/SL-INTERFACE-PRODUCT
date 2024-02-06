 <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img class="logo-img" src="img/Logos_OpenLock/Logo_inverso_No_Background.png" alt="Pi-hole logo">
                </div>
                <div class="pull-left info">
                    <p>Status</p>
                    <?php
                    $pistatus = piholeStatus();
                    if ($pistatus == 53) {
                        echo '<span id="status"><i class="fa fa-w fa-circle text-green-light"></i> Active</span>';
                    } elseif ($pistatus == 0) {
                        echo '<span id="status"><i class="fa fa-w fa-circle text-red"></i> Blocking disabled</span>';
                    } elseif ($pistatus == -1) {
                        echo '<span id="status"><i class="fa fa-w fa-circle text-red"></i> DNS service not running</span>';
                    } elseif ($pistatus == -2) {
                        echo '<span id="status"><i class="fa fa-w fa-circle text-red"></i> Unknown</span>';
                    } else {
                        echo '<span id="status"><i class="fa fa-w fa-circle text-orange"></i> DNS service on port '.$pistatus.'</span>';
                    }
                    ?>
                    <br/>
                    <?php
                    echo '<span title="Detected '.$nproc.' cores"><i class="fa fa-w fa-circle ';
                    if ($loaddata[0] > $nproc) {
                        echo 'text-red';
                    } else {
                        echo 'text-green-light';
                    }
                    echo '"></i> Load:&nbsp;&nbsp;'.$loaddata[0].'&nbsp;&nbsp;'.$loaddata[1].'&nbsp;&nbsp;'.$loaddata[2].'</span>';
                    ?>
                    <br/>
                    <?php
                    echo '<span><i class="fa fa-w fa-circle ';
                    if ($memory_usage > 0.75 || $memory_usage < 0.0) {
                        echo 'text-red';
                    } else {
                        echo 'text-green-light';
                    }
                    if ($memory_usage > 0.0) {
                        echo '"></i> Memory usage:&nbsp;&nbsp;'.sprintf('%.1f', 100.0 * $memory_usage).'&thinsp;%</span>';
                    } else {
                        echo '"></i> Memory usage:&nbsp;&nbsp; N/A</span>';
                    }
                    ?>
                    <br/>
                    <?php
                    if ($celsius >= -273.15) {
                        // Only show temp info if any data is available -->
                        $tempcolor = 'text-vivid-blue';
                        if (isset($temperaturelimit) && $celsius > $temperaturelimit) {
                            $tempcolor = 'text-red';
                        }
                        echo '<span id="temperature"><i class="fa fa-w fa-fire '.$tempcolor.'" style="width: 1em !important"></i> ';
                        echo 'Temp:&nbsp;<span id="rawtemp" hidden>'.$celsius.'</span>';
                        echo '<span id="tempunit" hidden>'.$temperatureunit.'</span>';
                        echo '<span id="tempdisplay"></span></span>';
                    }
                    ?>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header text-uppercase">Main</li>
                <!-- Home Page -->
                <li class="menu-main<?php if ($scriptname === 'index.php') { ?> active<?php } ?>">
                    <a href="index.php">
                        <i class="fa fa-fw menu-icon fa-home"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li class="header text-uppercase">Analysis</li>
                <!-- Query Log -->
                <li class="menu-analysis<?php if ($scriptname === 'queries.php') { ?> active<?php } ?>">
                    <a href="queries.php">
                        <i class="fa fa-fw menu-icon fa-file-alt"></i> <span>Query Log</span>
                    </a>
                </li>
                <!-- Long-term database -->
                <li class="menu-analysis treeview<?php if ($scriptname === 'db_queries.php' || $scriptname === 'db_lists.php' || $scriptname === 'db_graph.php') { ?> active<?php } ?>">
                    <a href="#">
                        <i class="fa fa-fw menu-icon fa-history"></i> <span>Long-term Data</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php if ($scriptname === 'db_graph.php') { ?> active<?php } ?>">
                            <a href="db_graph.php">
                                <i class="fa fa-fw menu-icon fa-chart-bar"></i> Graphics
                            </a>
                        </li>
                        <li class="<?php if ($scriptname === 'db_queries.php') { ?> active<?php } ?>">
                            <a href="db_queries.php">
                                <i class="fa fa-fw menu-icon fa-file-alt"></i> Query Log
                            </a>
                        </li>
                        <li class="<?php if ($scriptname === 'db_lists.php') { ?> active<?php } ?>">
                            <a href="db_lists.php">
                                <i class="fa fa-fw menu-icon fa-list"></i> Top Lists
                            </a>
                        </li>
                    </ul>
                </li>

		<div class="switch-container">
                    <h1>Control Parental</h1>
                    <label class="switch">
                        <input type="checkbox" id="parentalControlSwitch">
                        <span class="slider round"></span>
                    </label>
                </div> 

            </ul>

        </section>
        <!-- /.sidebar -->
    </aside>
