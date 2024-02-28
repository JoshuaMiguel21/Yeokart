<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" >
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

    <input type="checkbox" id="nav-toggle">
    <div class="sidebar">
        <div class="sidebar-brand">
            <h2><span>Yeokart</span></h2>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="" class="active"><span class="las la-igloo"></span>
                    <span>Admin Dashboard</span></a>
                </li>
                <li>
                    <a href=""><span class="las la-users"></span>
                    <span>Customers</span></a>
                </li>
                <li>
                    <a href=""><span class="las la-shopping-basket"></span>
                    <span>Items</span></a>
                </li>
                <li>
                    <a href=""><span class="las la-shopping-bag"></span>
                    <span>Orders</span></a>
                </li>
                <li>
                    <a href=""><span class="las la-chart-line"></span>
                    <span>Report</span></a>
                </li>
                <li>
                    <a href="manage_employees.php"><span class="las la-user-circle"></span>
                    <span>Manage Employee</span></a>
                </li>
                <li>
                    <a href="logout.php"><span class="las la-sign-out-alt"></span>
                    <span>Logout</span></a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <header>
            <h3>
                <label for="nav-toggle">
                    <span class="las la-bars"></span>
                </label>

                Dashboard
            </h3>
            
            <div class="user-wrapper">
                <div>
                    <h3>Unknown</h3>
                    <small>Super admin</small>
                </div>       
            </div>
        </header>

        <main>
            
            <div class="cards">
                <div class="card-single">
                    <div>
                        <h1>54</h1>
                        <span>Customers</span>
                    </div>
                    <div>
                        <span class="las la-users"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1>3</h1>
                        <span>Employee</span>
                    </div>
                    <div>
                        <span class="las la-user-tie"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1>154</h1>
                        <span>Orders</span>
                    </div>
                    <div>
                        <span class="las la-shopping-bag"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1>54</h1>
                        <span>Income</span>
                    </div>
                    <div>
                        <span class="lab la-google-wallet"></span>
                    </div>
                </div>
            </div>

        </main>
    </div>
</body>
</html>