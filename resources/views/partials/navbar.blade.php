<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3">

    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="/">

       <!--     <img src="{{ asset('images/HSBTEb2.png') }}"
                 width="60"
                 class="me-2"> -->

            <div>

                <h5 class="mb-0 fw-bold text-primary">
                    HSBTE Training Portal
                </h5>

           

            </div>

        </a>

        <!-- Mobile Toggle -->

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainMenu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- Menu -->

        <div class="collapse navbar-collapse" id="mainMenu">

            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="/">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#">Departments</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#">Courses</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#">Jobs</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#">Notifications</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#">Help</a>
                </li>

            </ul>

            <!-- Login Dropdown -->

            <ul class="navbar-nav">

                <li class="nav-item dropdown">

                    <a class="btn btn-primary dropdown-toggle"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown">

                        Login

                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a class="dropdown-item" href="/login">
                                👨‍🎓 Student Login
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="/trainer/login">
                                👨‍🏫 Trainer Login
                            </a>
                        </li>

                    </ul>

                </li>

            </ul>

        </div>

    </div>

</nav>