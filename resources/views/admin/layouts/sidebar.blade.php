<body class="dashboard-page dashboard-nav-fixed">

    <!-- Dashboard Nav Start -->
    <div class="dashboard-nav offcanvas offcanvas-start" id="offcanvasDashboard">

        <!-- Dashboard Nav Wrapper Start -->
        <div class="dashboard-nav__wrapper">

            <!-- Dashboard Nav Header Start -->
            <div class="offcanvas-header dashboard-nav__header dashboard-nav-header">

                <div class="dashboard-nav__toggle d-xl-none">
                    <button class="toggle-close" data-bs-dismiss="offcanvas"><i class="fas fa-times"></i></button>
                </div>

                <div class="dashboard-nav__logo">
                    <a class="logo" href="{{ route('dashboard') }}">
                        <img src="{{ asset('assets/admin/images/dark-logo.png') }}" alt="Logo"  style="height: 100px;width: 100px;">
                    </a>
                </div>

            </div>
            <!-- Dashboard Nav Header End -->

            <!-- Dashboard Nav Content Start -->
            <div class="offcanvas-body dashboard-nav__content navScroll">

                <!-- Dashboard Nav Menu Start -->
                <div class="dashboard-nav__menu">

                    @auth
                    <ul class="dashboard-nav__menu-list">

                        <!-- Dashboard -->
                        <li>
                            <a href="{{ route('dashboard') }}">
                                <i class="edumi edumi-layers"></i>
                                <span class="text">Dashboard</span>
                            </a>
                        </li>

                        <!-- My Profile for all roles -->
                        @if(in_array(auth()->user()->role, ['admin', 'teacher', 'student']))
                        <li>
                            <a href="{{ route('admin.profile') }}">
                                <i class="edumi edumi-follower"></i>
                                <span class="text">My Profile</span>
                            </a>
                        </li>
                        @endif

                        @auth
                        @if(auth()->user()->role === 'admin')
                        <li>
                            <a href="{{ route('admin.transactions.index') }}">
                                <i class="edumi edumi-user"></i>
                                <span class="text">Transactions</span>
                            </a>
                        </li>
                        @endif
                        @endauth

                        <!-- Admin Only Menus -->
                        @if(auth()->user()->role === 'admin')

                        <!-- Teachers Dropdown -->
                        <li>
                            <a class="collapsed" data-bs-toggle="collapse" href="#adminTeachers">
                                <i class="edumi edumi-user"></i> <span class="text">Users</span>
                                <i class="fas fa-chevron-down float-end small"></i>
                            </a>
                            <div class="collapse" id="adminTeachers">
                                <ul class="ms-4">
                                    <li><a href="{{ route('admin.teachers.index') }}">All Users</a></li>
                                    <li><a href="{{ route('admin.teachers.create') }}">Add User</a></li>
                                </ul>
                            </div>
                        </li>
                        {{-- course category --}}
                        <li>
                            <a class="collapsed" data-bs-toggle="collapse" href="#coursecategories">
                                <i class="edumi edumi-books"></i> <span class="text">Course Categories</span>
                                <i class="fas fa-chevron-down float-end small"></i>
                            </a>
                            <div class="collapse" id="coursecategories">
                                <ul class="ms-4">
                                    <li><a href="{{ route('admin.course-categories.index') }}">All Categories</a></li>
                                    <li><a href="{{ route('admin.course-categories.create') }}">Add Category</a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Courses -->
                        <li>
                            <a class="collapsed" data-bs-toggle="collapse" href="#courses">
                                <i class="edumi edumi-books"></i> <span class="text">Courses</span>
                                <i class="fas fa-chevron-down float-end small"></i>
                            </a>
                            <div class="collapse" id="courses">
                                <ul class="ms-4">
                                    <li><a href="{{ route('courses.index') }}">All Courses</a></li>
                                    <li><a href="{{ route('courses.create') }}">Add Course</a></li>
                                </ul>
                            </div>
                        </li>

                        {{-- purchase history --}}
                        <li>
                            <a href="{{ route('purchase.history') }}">
                                <i class="edumi edumi-support"></i>
                                <span class="text">Purchase History</span>
                            </a>
                        </li>
                        {{-- add blog category --}}
                        {{-- <li>
                            <a class="collapsed" data-bs-toggle="collapse" href="#blogcategory">
                                <i class="edumi edumi-books"></i> <span class="text">Blog Category</span>
                                <i class="fas fa-chevron-down float-end small"></i>
                            </a>
                            <div class="collapse" id="blogcategory">
                                <ul class="ms-4">
                                    <li><a href="{{ route('admin.blog-categories.index') }}">All Blogs Category</a>
                                    </li>
                                    <li><a href="{{ route('admin.blog-categories.create') }}">Add Blogs Category</a>
                                    </li>
                                </ul>
                            </div>
                        </li> --}}
                        {{-- add blog --}}
                        {{-- <li>
                            <a class="collapsed" data-bs-toggle="collapse" href="#blogMenu">
                                <i class="edumi edumi-books"></i> <span class="text">Blog</span>
                                <i class="fas fa-chevron-down float-end small"></i>
                            </a>
                            <div class="collapse" id="blogMenu">
                                <ul class="ms-4">
                                    <li><a href="{{ route('admin.blog.index') }}">All Blogs</a></li>
                                    <li><a href="{{ route('admin.blog.create') }}">Add Blog</a></li>

                                </ul>
                            </div>
                        </li> --}}



                        {{-- Q&A & Discussions (Admin) --}}
                        <li class="{{ request()->routeIs('admin.qna.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.qna.index') }}"
                                class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="edumi edumi-question"></i>
                                    <span class="text">Q&A</span>
                                </span>
                                @if(($qnaModNewCount ?? 0) > 0)
                                <span class="badge rounded-pill bg-danger">{{ $qnaModNewCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.discussion.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.discussion.index') }}"
                                class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fas fa-chevron-right float-end small"></i>
                                    <span class="text">Discussions</span>
                                </span>
                                @if(($discModNewCount ?? 0) > 0)
                                <span class="badge rounded-pill bg-danger">{{ $discModNewCount }}</span>
                                @endif
                            </a>
                        </li>
                        {{-- announcements --}}

                        <li>
                            <a href="{{ route('admin.announcements.index') }}">
                                <i class="edumi edumi-announcement"></i>
                                <span class="text">Announcements</span>
                            </a>
                        </li>


                        {{-- quizzes --}}
                        <li class="{{ request()->routeIs('admin.quizzes.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.quizzes.index') }}">
                                <i class="fas fa-chevron-right float-end small"></i><span class="text">Quizzes</span>
                            </a>
                        </li>

                        {{-- reviews --}}
                        <li class="{{ request()->routeIs('coursereview.*') ? 'active' : '' }}">
                            <a href="{{ route('coursereview.index') }}">
                                <i class="fas fa-chevron-right float-end small"></i>
                                <span class="text">Reviews</span>
                            </a>
                        </li>
                        {{-- zoom meeting --}}
                        {{-- <li>
                            <a class="collapsed" data-bs-toggle="collapse" href="#zoom">
                                <i class="edumi edumi-books"></i> <span class="text">Zoom Meetings</span>
                                <i class="fas fa-chevron-down float-end small"></i>
                            </a>
                            <div class="collapse" id="zoom">
                                <ul class="ms-4">
                                    <li><a href="{{ route('zoom-meetings.index') }}">All Zoom Meting</a></li>
                                    <li><a href="{{ route('zoom-meetings.create') }}">Add Zoom Meting</a></li>
                                </ul>
                            </div>
                        </li> --}}
                        {{-- {{ About_Banner }} --}}
                        {{-- <li>
                            <a class="collapsed" data-bs-toggle="collapse" href="#adminAboutBanners">
                                <i class="edumi edumi-image"></i> <span class="text">About Banners</span>
                                <i class="fas fa-chevron-down float-end small"></i>
                            </a>
                            <div class="collapse" id="adminAboutBanners">
                                <ul class="ms-4">
                                    <li><a href="{{ route('admin.about-banners.index') }}">All Banners</a></li>
                                    <li><a href="{{ route('admin.about-banners.create') }}">Add Banner</a></li>
                                </ul>
                            </div>
                        </li> --}}

                        {{-- {{ About Icons }} --}}
                        {{-- <li>
                            <a class="collapsed" data-bs-toggle="collapse" href="#adminAboutIcons">
                                <i class="fas fa-icons"></i> <span class="text">About Icons</span>
                                <i class="fas fa-chevron-down float-end small"></i>
                            </a>
                            <div class="collapse" id="adminAboutIcons">
                                <ul class="ms-4">
                                    <li><a href="{{ route('admin.about-icons.index') }}">All Icons</a></li>
                                    <li><a href="{{ route('admin.about-icons.create') }}">Add Icon</a></li>
                                </ul>
                            </div>
                        </li> --}}

                        {{-- {{ About post }} --}}

                        {{-- <li>
                            <a class="collapsed" data-bs-toggle="collapse" href="#adminAboutPosts">
                                <i class="fas fa-file-alt"></i> <span class="text">About Posts</span>
                                <i class="fas fa-chevron-down float-end small"></i>
                            </a>
                            <div class="collapse" id="adminAboutPosts">
                                <ul class="ms-4">
                                    <li><a href="{{ route('about-posts.index') }}">All About Posts</a></li>
                                    <li><a href="{{ route('about-posts.create') }}">Add About Post</a></li>
                                </ul>
                            </div>
                        </li> --}}

                        {{-- {{ About Gallery Images }} --}}
                        {{-- <li>
                            <a class="collapsed" data-bs-toggle="collapse" href="#aboutGalleryImages">
                                <i class="edumi edumi-image"></i> <span class="text">About Gallery Images</span>
                                <i class="fas fa-chevron-down float-end small"></i>
                            </a>
                            <div class="collapse" id="aboutGalleryImages">
                                <ul class="ms-4">
                                    <li>
                                        <a href="{{ route('about-gallery-images.index') }}">All Gallery Images</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('about-gallery-images.create') }}">Add Gallery Image</a>
                                    </li>
                                </ul>
                            </div>
                        </li> --}}
                        {{-- contacts --}}
                        <li>
                            <a href="{{ route('admin.contacts.index') }}">
                                <i class="fas fa-chevron-right float-end small"></i>

                                <span class="text">All Contacts</span>
                            </a>
                        </li>
                        {{-- faq student question --}}
                        <li>
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqstudent">
                                <i class="fas fa-file-alt"></i> <span class="text">Faq Student Q.</span>
                                <i class="fas fa-chevron-down float-end small"></i>
                            </a>
                            <div class="collapse" id="faqstudent">
                                <ul class="ms-4">
                                    <li><a href="{{ route('admin.faq-students.index') }}">All FAQ Student Q.</a></li>
                                    <li><a href="{{ route('admin.faq-students.create') }}">Add FAQ Student Q.</a></li>
                                </ul>
                            </div>
                        </li>
                        {{-- Faq Teacher --}}
                        <li>
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqteacherMenu">
                                <i class="fas fa-question-circle"></i> <span class="text">Faq Teacher Q.</span>
                                <i class="fas fa-chevron-down float-end small"></i>
                            </a>
                            <div class="collapse" id="faqteacherMenu">
                                <ul class="ms-4">
                                    <li><a href="{{ route('admin.faq-teachers.index') }}">All Faq Teacher Q.</a></li>
                                    <li><a href="{{ route('admin.faq-teachers.create') }}">Add Faq Teacher Q.</a></li>
                                </ul>
                            </div>
                        </li>
                        <!-- Sections -->
                        <li>
                            <a href="{{ route('sections.index') }}">
                                <i class="fas fa-chevron-right float-end small"></i>

                                <span class="text">All Sections</span>
                            </a>
                        </li>

                        <!-- Lessons -->
                        <li>
                            <a href="{{ route('lessons.index') }}">
                                <i class="fas fa-chevron-right float-end small"></i>

                                <span class="text">All Lessons</span>
                            </a>
                        </li>

                        @endif

                    
                       


                        <!-- Student Only Menus -->
                        @if(auth()->user()->role === 'student')
                        <li>
                            <a href="{{ route('enrolled-courses') }}"><i class="edumi edumi-open-book"></i><span
                                    class="text">Enrolled Courses</span></a>
                        </li>

                        {{-- announcements --}}
                        <li>
                            <a href="{{ route('student.announcements.index') }}">
                                <i class="edumi edumi-announcement"></i>
                                <span class="text">Announcements</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.notifications.index') }}"
                                class="d-flex align-items-center justify-content-between">
                                <span>
                                    <i class="edumi edumi-support"></i>
                                    <span class="text">Notifications</span>
                                </span>

                                @if(($notifUnreadCount ?? 0) > 0)
                                <span class="badge bg-danger ms-2" style="min-width:22px; text-align:center;">
                                    {{ min($notifUnreadCount, 99) }}
                                </span>
                                @endif
                            </a>
                        </li>

                        {{-- end --}}
                        {{-- <li class="{{ request()->routeIs('student.reviews.*') ? 'active' : '' }}">
                            <a href="{{ route('student.reviews.index') }}">
                                <i class="edumi edumi-star"></i><span class="text">Reviews</span>
                            </a>
                        </li> --}}
                        <li>
                            <a href="{{ route('quiz.attempts') }}">
                                <i class="edumi edumi-support"></i>
                                <span class="text">My Quiz Attempts</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('purchase.history') }}">
                                <i class="edumi edumi-support"></i>
                                <span class="text">Purchase History</span>
                            </a>
                        </li>






                        {{-- <li>
                            <a href="dashboard-students.html"><i class="edumi edumi-users"></i><span class="text">My
                                    Students</span></a>
                        </li> --}}
                        @endif
                    </ul>

                    <!-- Logout -->
                    <ul class="dashboard-nav__menu-list">
                        <li>
                            <a href="{{ route('logout') }}">
                                <i class="edumi edumi-sign-out"></i>
                                <span class="text">Logout</span>
                            </a>
                        </li>
                    </ul>

                    @endauth

                </div>
                <!-- Dashboard Nav Menu End -->

            </div>
            <!-- Dashboard Nav Content End -->

            <div class="offcanvas-footer"></div>
        </div>
        <!-- Dashboard Nav Wrapper End -->

    </div>
    <!-- Dashboard Nav End -->