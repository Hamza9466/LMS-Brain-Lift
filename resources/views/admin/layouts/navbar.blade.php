<!-- Dashboard Menu Start -->
<div class="dashboard-menu">

    <!-- Dashboard Menu Close Start -->
    <div class="dashboard-menu__close">
        <button class="close-btn"><i class="fas fa-times"></i></button>
    </div>
    <!-- Dashboard Menu Close End -->

    <!-- Dashboard Menu Content Start -->
    <div class="dashboard-menu__content">
        {{-- Image removed intentionally --}}
        <div class="dashboard-menu__main-menu">
            <ul class="dashboard-menu__menu-link">
                <li><a href="#">Home</a></li>
                <li><a href="#">Courses</a></li>
                <li><a href="#">Events</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Contact</a></li>
            </ul>

            <div class="dashboard-menu__search">
                <form action="#">
                    <input type="text" placeholder="Search…">
                    <button class="search-btn"><i class="far fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
    <!-- Dashboard Menu Content End -->

</div>
<!-- Dashboard Menu End -->


<!-- Dashboard Main Wrapper Start -->
<main class="dashboard-main-wrapper">

    <!-- Dashboard Header Start -->
    <div class="dashboard-header">
        <div class="container">
            <!-- Dashboard Header Wrapper Start -->
            <div class="dashboard-header__wrap">

                <div class="dashboard-header__toggle-menu d-xl-none">
                    <button class="toggle-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDashboard">
                        <svg width="20px" height="18px" viewBox="0 0 20 18" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.718,2.606H1.282C0.574,2.606,0,2.025,0,1.308S0.574,0.01,1.282,0.01h17.436C19.426,0.01,20,0.591,20,1.308s-0.574,1.298-1.282,1.298Z"></path>
                            <path d="M11.538,10.596H1.282C0.574,10.596,0,10.015,0,9.298S0.574,8,1.282,8h10.256c0.708,0,1.282,0.581,1.282,1.298S12.246,10.596,11.538,10.596Z"></path>
                            <path d="M18.718,17.6H1.282C0.574,17.6,0,17.063,0,16.4s0.574-1.2,1.282-1.2h17.436C19.426,15.2,20,15.737,20,16.4S19.426,17.6,18.718,17.6Z"></path>
                        </svg>
                    </button>
                </div>

                @php
                    use Illuminate\Support\Str;

                    $u    = auth()->user();
                    $role = $u?->role ?? 'guest';

                    // Preferred name from users table only
                    $name = trim(($u->first_name ?? '').' '.($u->last_name ?? ''));
                    if ($name === '' && !empty($u?->name)) {
                        $name = $u->name;
                    }
                    if ($name === '' && !empty($u?->email)) {
                        $name = Str::before($u->email, '@');
                    }
                    if ($name === '') {
                        $name = 'Guest';
                    }
                @endphp

                {{-- User block (no image) --}}
                <div class="dashboard-header__user">
                    <div class="dashboard-header__user-info">
                        <h4 class="dashboard-header__user-name">
                            <span class="welcome-text">Welcome,</span> {{ $name }}
                        </h4>
                        <p>Your role is: {{ ucfirst($role) }}</p>
                    </div>
                </div>

              

                {{-- Admin-only: Add course button --}}
                @auth
                    @if(auth()->user()->role === 'admin')
                        <div class="dashboard-header__btn">
                            <a class="btn btn-outline-primary" href="{{ route('courses.create') }}">
                                <i class="edumi edumi-content-writing"></i>
                                <span class="text">Add A New Course</span>
                            </a>
                        </div>
                    @endif
                @endauth

                <div class="dashboard-header__toggle">
                    <button class="btn btn-toggle"><i class="fas fa-bars"></i></button>
                </div>

            </div>
            <!-- Dashboard Header Wrapper End -->
        </div>
    </div>
    <!-- Dashboard Header End -->
</main>
<!-- Dashboard Main Wrapper End -->
