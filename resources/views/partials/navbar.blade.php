<style>
/* ===== Top Utility Strip ===== */
.top-strip {
    background: #0d2a5c;
    color: #dbe4f3;
    font-size: 13px;
    padding: 6px 0;
}
.top-strip a {
    color: #dbe4f3;
    text-decoration: none;
    margin-left: 18px;
    transition: color .2s;
}
.top-strip a:hover { color: #f0a500; }
.top-strip .ts-access a {
    border: 1px solid #3a548a;
    border-radius: 4px;
    padding: 0 6px;
    margin-left: 6px;
    font-weight: 600;
}

/* ===== Main Navbar ===== */
.main-navbar {
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
}
.brand-badge {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #0d2a5c;
    color: #f0a500;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
}
.brand-text {
    font-weight: 700;
    font-size: 18px;
    color: #0d2a5c;
}

/* Menu links */
.main-navbar .nav-link {
    color: #33415c;
    font-weight: 500;
    margin: 0 6px;
    padding-bottom: 6px !important;
    border-bottom: 2px solid transparent;
    transition: color .2s, border-color .2s;
}
.main-navbar .nav-link:hover { color: #0d2a5c; }
.main-navbar .nav-link.active {
    color: #0d2a5c;
    font-weight: 600;
    border-bottom: 2px solid #f0a500;
}

/* Login button */
.login-btn {
    background: #0d2a5c;
    color: #fff !important;
    font-weight: 500;
    padding: 8px 18px;
    border-radius: 6px;
}
.login-btn:hover { background: #09204a; color: #fff; }

/* Logout button inside dropdown — look like a normal item */
.dropdown-item-btn {
    display: block;
    width: 100%;
    text-align: left;
    background: none;
    border: none;
    padding: 4px 16px;
    color: #33415c;
    font-size: 14px;
    cursor: pointer;
}
.dropdown-item-btn:hover { background: #eef2fa; color: #0d2a5c; }

/* Mobile */
@media (max-width: 576px) {
    .top-strip .ts-left { font-size: 12px; }
    .top-strip a { margin-left: 10px; }
}
</style>

<!-- ===== Top Utility Strip ===== -->
<div class="top-strip">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="ts-left">
            <i class="bi bi-geo-alt-fill"></i> Government of Haryana
        </span>
        <span class="ts-right">
            <a href="#"><i class="bi bi-telephone-fill"></i> Helpline</a>
            <a href="#"><i class="bi bi-envelope-fill"></i> Contact</a>
            <span class="ts-access">
                <a href="#">A+</a>
                <a href="#">A-</a>
            </span>
        </span>
    </div>
</div>

<!-- ===== Main Navbar ===== -->
<nav class="navbar navbar-expand-lg main-navbar py-2">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="/">
            <div class="brand-badge me-2">H</div>
            <span class="brand-text">HSBTE Training Portal</span>
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
                <li class="nav-item"><a class="nav-link active" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Departments</a></li>
                <li class="nav-item"><a class="nav-link" href="/courses">Courses</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Jobs</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Notifications</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Help</a></li>
            </ul>

            <!-- Auth Dropdown -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">

                    @guest
                        {{-- Logged OUT: show Login options --}}
                        <a class="btn login-btn dropdown-toggle"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown">
                            Login
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('login') }}">👨‍🎓 Student Login</a></li>
                            <li><a class="dropdown-item" href="{{ route('trainer.login') }}">👨‍🏫 Trainer Login</a></li>
                        </ul>
                    @endguest

                    @auth
                        {{-- Logged IN: show name + dashboard + logout --}}
                        <a class="btn login-btn dropdown-toggle"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Admin Dashboard</a></li>
                            @elseif(auth()->user()->isTrainer())
                                <li><a class="dropdown-item" href="{{ route('trainer.dashboard') }}"><i class="bi bi-speedometer2"></i> Trainer Dashboard</a></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('student.dashboard') }}"><i class="bi bi-speedometer2"></i> My Dashboard</a></li>
                            @endif

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item-btn">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    @endauth

                </li>
            </ul>
        </div>
    </div>
</nav>