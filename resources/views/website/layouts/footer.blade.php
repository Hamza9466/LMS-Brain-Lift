<footer class="footer bg-main-25 position-relative z-1">
    <img src="assets/images/shapes/shape2.png" alt="" class="shape five animation-scalation">
    <img src="assets/images/shapes/shape6.png" alt="" class="shape one animation-scalation">
    
    <div class="py-120 ">
        <div class="container container-two">
            <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-5">
                <div class="col" data-aos="fade-up" data-aos-duration="300" >
                    <div class="footer-item">
                        <div class="footer-item__logo">
                            <a href="{{ route('home') }}"> <img src="{{ asset('assets/website/images/logo/logo.png') }}" alt="Logo" style="height: 80px;"></a>
                        </div>
                        <p class="my-32">Welcome to Brain Lift, where learning meets opportunity. Whether you’re a student, freelancer, or professional, we help you gain practical skills and grow your career.</p>
                        <ul class="social-list flex-align gap-24">
                            <li class="social-list__item">
                                <a href="https://www.facebook.com" class="text-main-600 text-2xl hover-text-main-two-600"><i class="ph-bold ph-facebook-logo"></i></a>
                            </li>
                            <li class="social-list__item">
                                <a href="https://www.twitter.com" class="text-main-600 text-2xl hover-text-main-two-600"> <i class="ph-bold ph-twitter-logo"></i></a>
                            </li>
                            <li class="social-list__item">
                                <a href="https://www.linkedin.com" class="text-main-600 text-2xl hover-text-main-two-600"><i class="ph-bold ph-instagram-logo"></i></a>
                            </li>
                            <li class="social-list__item">
                                <a href="https://www.pinterest.com" class="text-main-600 text-2xl hover-text-main-two-600"><i class="ph-bold ph-pinterest-logo"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col" data-aos="fade-up" data-aos-duration="400" >
                    <div class="footer-item">
                        <h4 class="footer-item__title mb-32">Navigation</h4>
                        <ul class="footer-menu">
                            <li class="mb-16">
                                <a href="{{ route('about') }}" class="text-neutral-500 hover-text-main-600 hover-text-decoration-underline">About us</a>
                            </li>
                            <li class="mb-16">
                                <a href="{{ route('CourseGrid') }}" class="text-neutral-500 hover-text-main-600 hover-text-decoration-underline">Courses</a>
                            </li>
                          
                           
                            <li class="mb-0">
                                <a href="{{ route('Contact') }}" class="text-neutral-500 hover-text-main-600 hover-text-decoration-underline">Contact</a>
                            </li>
                        </ul>
                    </div>
                </div>

     <ul class="footer-menu">
    {{-- Top item --}}
    <li class="mb-16">
        <a href="{{ route('CourseGrid') }}"
           class="text-neutral-500 hover-text-main-600 hover-text-decoration-underline">
           <h5>Categories</h5>
        </a>
    </li>

    {{-- Dynamic items --}}
    @forelse($cats as $c)
        <li class="mb-16">
            <a href="{{ route('CourseGrid', ['category' => $c->slug ?? $c->id]) }}"
               class="text-neutral-500 hover-text-main-600 hover-text-decoration-underline">
                {{ $c->name ?? $c->title }}
            </a>
        </li>
    @empty
        <li class="mb-16 text-neutral-400">No categories yet.</li>
    @endforelse
</ul>


                <div class="col" data-aos="fade-up" data-aos-duration="800" >
                    <div class="footer-item">
                        <h4 class="footer-item__title mb-32">Contact Us</h4>
                        <div class="flex-align gap-20 mb-24">
                            <span class="icon d-flex text-32 text-main-600"><i class="ph ph-phone"></i></span>
                            <div class="">
                                <a href="tel:0309-7530000" class="text-neutral-500 d-block hover-text-main-600 mb-4"> 0309-7530000</a>
                                
                            </div>
                        </div>
                        <div class="flex-align gap-20 mb-24">
                            <span class="icon d-flex text-32 text-main-600"><i class="ph ph-envelope-open"></i></span>
                            <div class="">
                                <a href="mailto:dwallo@gmail.com" class="text-neutral-500 d-block hover-text-main-600 mb-4">Support@Brainlift.net</a>
                             
                            </div>
                        </div>
                        <div class="flex-align gap-20 mb-24">
                            <span class="icon d-flex text-32 text-main-600"><i class="ph ph-map-trifold"></i></span>
                            <div class="">
                                <span class="text-neutral-500 d-block mb-4">Location: Johar Town Lahore</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" data-aos="fade-up" data-aos-duration="1000" >
                    <div class="footer-item">
                        <h4 class="footer-item__title mb-32">Subscribe Here</h4>
                        <p class="text-neutral-500">Enter your email address to register to our newsletter subscription</p>
                        <form action="#" class="mt-24 position-relative">
                            <input type="email" class="form-control bg-white shadow-none border border-neutral-30 rounded-pill h-52 ps-24 pe-48 focus-border-main-600" placeholder="Email...">
                            <button type="submit" class="w-36 h-36 flex-center rounded-circle bg-main-600 text-white hover-bg-main-800 position-absolute top-50 translate-middle-y inset-inline-end-0 me-8">
                                <i class="ph ph-paper-plane-tilt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <!-- bottom Footer -->
        <div class="bottom-footer bg-main-25 border-top border-dashed border-main-100 border-0 py-32">
            <div class="container container-two">
                <div class="bottom-footer__inner flex-between gap-3 flex-wrap">
                    <p class="bottom-footer__text"> Copyright &copy; 2024 <span class="fw-semibold">Brain Lift</span> All Rights Reserved.</p>
                    <div class="footer-links">
                        <a href="#" class="text-neutral-500 hover-text-main-600 hover-text-decoration-underline">Privacy Policy</a>
                        <a href="#" class="text-neutral-500 hover-text-main-600 hover-text-decoration-underline">Terms & Conditions</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>