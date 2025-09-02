@extends('website.layouts.main')
@section('content')
<!-- ==================== Header End Here ==================== -->

    <!-- ==================== Breadcrumb Start Here ==================== -->
<section class="breadcrumb py-120 bg-main-25 position-relative z-1 overflow-hidden mb-0">
    <img src="assets/images/shapes/shape1.png" alt="" class="shape one animation-rotation d-md-block d-none">
    <img src="assets/images/shapes/shape2.png" alt="" class="shape two animation-scalation d-md-block d-none">
    <img src="assets/images/shapes/shape3.png" alt="" class="shape eight animation-walking d-md-block d-none">
    <img src="assets/images/shapes/shape5.png" alt="" class="shape six animation-walking d-md-block d-none">
    <img src="assets/images/shapes/shape4.png" alt="" class="shape four animation-scalation">
    <img src="assets/images/shapes/shape4.png" alt="" class="shape nine animation-scalation">
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="breadcrumb__wrapper">
                    <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Blog Details</h1>
                    <ul class="breadcrumb__list d-flex align-items-center justify-content-center gap-4">
                        <li class="breadcrumb__item">
                            <a href="index.html" class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium"> 
                                <i class="text-lg d-inline-flex ph-bold ph-house"></i> Home</a>
                         </li>
                        <li class="breadcrumb__item">
                            <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                        </li>
                        <li class="breadcrumb__item">
                            <a href="course.html" class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium"> </a> 
                        </li>
                        <li class="breadcrumb__item d-none">
                            <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                        </li>
                        <li class="breadcrumb__item"> 
                            <span class="text-main-two-600"> Blog Details </span> 
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==================== Breadcrumb End Here ==================== -->

    <!-- ================================ Blog Details Section Start =================================== -->
     <div class="blog-page-section py-120">
        <div class="container">
            <div class="flex-between gap-16 flex-wrap mb-40">
                <span class="text-neutral-500">Showing 9 of 600 Results </span>
                <div class="flex-align gap-16">
                    <div class="flex-align gap-8">
                        <span class="text-neutral-500 flex-shrink-0">Sort By :</span>
                        <select class="form-select ps-20 pe-28 py-8 fw-medium rounded-pill bg-main-25 border border-neutral-30 text-neutral-700">
                            <option value="1">Newest</option>
                            <option value="1">Trending</option>
                            <option value="1">Popular</option>
                        </select>
                    </div>
                    <button type="button" class="list-bar-btn text-xl w-40 h-40 bg-main-600 text-white rounded-8 flex-center d-lg-none">
                        <i class="ph-bold ph-funnel"></i>
                    </button>
                </div>
            </div>
            <div class="row gy-4">
                <div class="col-lg-8">
                    <div class="bg-main-25 rounded-16 p-12 border border-neutral-30">
                        <div class="rounded-12 overflow-hidden position-relative">
                            <img src="assets/images/thumbs/blog-details-img.png" alt="Course Image" class="rounded-12 cover-img transition-2">
                            <div class="position-absolute inset-inline-end-0 inset-block-end-0 me-16 mb-16 py-12 px-24 rounded-8 bg-main-two-600 text-white fw-medium">
                                <h3 class="mb-0 text-white fw-medium">21</h3>
                                DEC
                            </div>
                        </div>
                        <div class="pt-32 pb-24 px-16 position-relative">
                            <div class="flex-align gap-14 flex-wrap mb-20">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-user-circle"></i></span>
                                    <span class="text-neutral-500 text-lg">By Admin</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph-bold ph-eye"></i></span>
                                    <span class="text-neutral-500 text-lg">1.6k</span>
                                </div>
                                <span class="w-8 h-8 bg-neutral-100 rounded-circle"></span>
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 text-2xl d-flex"><i class="ph ph-chat-dots"></i></span>
                                    <span class="text-neutral-500 text-lg">24</span>
                                </div>
                            </div>
                            <h2 class="mb-24"> Top 10 Tips for Online Learning Success </h2>
                            <p class="text-neutral-500 mb-32">This blog post provides practical tips and strategies to help students succeed in their online learning journey. It covers aspects like time management, staying motivated, and effective study techniques.</p>

                            <h6 class="mb-16 text-neutral-500 text-lg">Key Points:</h6>
                            <ul class="list-dotted d-flex flex-column gap-8">
                                <li> Create a Dedicated Study Space: Ensure your study area is quiet, well-lit, and free from distractions.</li>
                                <li> Set a Routine: Develop a consistent study schedule to stay organized and on track.</li>
                                <li> Stay Organized: Use planners or digital tools to keep track of assignments and deadlines.</li>
                                <li> Participate Actively: Engage in online discussions, forums, and group projects to enhance learning.</li>
                                <li> Take Breaks: Avoid burnout by taking regular breaks and practicing self-care.</li>
                                <li> Stay Connected: Communicate regularly with instructors and classmates for support and collaboration.</li>
                                <li> Utilize Resources: Take advantage of available resources like online libraries, tutoring services, and study guides.</li>
                                <li> Practice Time Management: Prioritize tasks and break larger projects into manageable chunks.</li>
                                <li> Stay Motivated: Set personal goals and reward yourself for achieving milestones.</li>
                                <li> Seek Feedback: Actively seek and apply feedback to improve your understanding and performance.</li>
                            </ul>
                            <span class="d-block border border-neutral-30 my-32 border-dashed"></span>
                            <h3 class="mb-16 text-neutral-500 text-lg">Create a Dedicated Study Space</h3>
                            <ul class="list-dotted d-flex flex-column gap-8">
                                <li>Description: Set up a specific area in your home that is solely for studying. This helps create a clear boundary between study time and personal time.</li>
                                <li>Tips: Ensure your study space is quiet, well-lit, comfortable, and free from distractions. Keep all necessary materials within reach to minimize interruptions.</li>
                            </ul>
                            <span class="d-block border border-neutral-30 my-32 border-dashed"></span>
                            <h4 class="mb-16 text-neutral-500 text-lg">Set a Routine</h4>
                            <ul class="list-dotted d-flex flex-column gap-8">
                                <li>Description: Establishing a consistent study schedule can help you stay organized and manage your time effectively.</li>
                                <li>Tips: Plan your day around your classes and study sessions. Stick to this routine as closely as possible to build good habits and ensure you cover all your subjects.</li>
                            </ul>
                            <span class="d-block border border-neutral-30 my-32 border-dashed"></span>
                            <h5 class="mb-16 text-neutral-500 text-lg">Stay Organized</h5>
                            <ul class="list-dotted d-flex flex-column gap-8">
                                <li>Description: Organization is key to managing coursework, deadlines, and other responsibilities.</li>
                                <li> Tips: Use planners, calendars, or digital tools to keep track of assignments, exams, and important dates. Regularly review and update your schedule to stay on top of your tasks.</li>
                            </ul>
                            <span class="d-block border border-neutral-30 my-32 border-dashed"></span>
                            <h6 class="mb-16 text-neutral-500 text-lg">Utilize Resources</h6>
                            <ul class="list-dotted d-flex flex-column gap-8">
                                <li>Description: Take full advantage of the resources available to you, such as libraries, tutoring services, and online tools.</li>
                                <li>Tips: Access digital libraries, attend virtual tutoring sessions, and use educational apps and websites. Many institutions offer free resources to support online learners.</li>
                            </ul>
                            <p class="text-neutral-500 mt-24">Online learning can be a highly effective way to achieve your educational goals if approached with the right strategies. By creating a dedicated study space, setting a routine, staying organized, and actively participating, you can make the most of your online learning experience.</p>

                        </div>
                    </div>       

                     <!-- Review Start -->
                     <div class="border border-neutral-30 rounded-12 bg-main-25 p-32 mt-24">
                        <div class="flex-between gap-16 flex-wrap mb-24">
                            <h4 class="mb-0">All Comments</h4>
                            <div class="flex-align gap-16">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 flex-shrink-0">Sort By :</span>
                                    <select class="form-select ps-20 pe-28 py-8 fw-medium rounded-pill bg-main-25 border border-neutral-30 text-neutral-700">
                                        <option value="1">Newest</option>
                                        <option value="1">Trending</option>
                                        <option value="1">Popular</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Review Item -->
                        <div class="border border-neutral-30 rounded-12 bg-white p-32">
                            <div class="flex-align gap-8 mb-16">
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star-half"></i></span>
                            </div>
                            <p class="text-neutral-700">"This course was fantastic! The instructor's explanations were clear and concise, making it easy to understand even the more complex topics."</p>
                            <span class="d-block border border-neutral-30 my-24 border-dashed"></span>
                            <div class="flex-align gap-24">
                                <img src="assets/images/thumbs/reviewer-img1.png" alt="" class="w-60 h-60 rounded-circle cover-img">
                                <div class="">
                                    <h6 class="text-xl mb-8 fw-medium">Mary Johnson</h6>
                                    <span class="text-neutral-700 text-sm">Project Manager</span>
                                </div>
                            </div>
                            <span class="d-block border border-neutral-30 my-24 border-dashed"></span>

                            <div class="flex-align flex-wrap gap-40">
                                <button type="button" class="like-button flex-align gap-8 text-neutral-500 hover-text-main-600">
                                    <span class="like-button__icon text-xl d-flex"><i class="ph-bold ph-thumbs-up"></i></span>
                                    <span class="like-button__text">178</span>
                                </button>
                                <a href="#commentForm" class="flex-align gap-8 text-neutral-500 hover-text-main-600">
                                    <i class="text-xl d-flex ph-bold ph-chat-centered-text"></i>
                                    Reply
                                </a>
                            </div>
                            <div class="flex-align gap-20 mt-24">
                                <img src="assets/images/thumbs/reviewer-img2.png" alt="" class="w-60 h-60 rounded-circle cover-img">
                                <input type="text" class="common-input rounded-pill bg-main-25 border-neutral-30 py-16" placeholder="Join the discussion...">
                            </div>
                        </div>
                        <!-- Review Item -->

                        <!-- Review Item -->
                        <div class="border border-neutral-30 rounded-12 bg-white p-32 mt-24">
                            <div class="flex-align gap-8 mb-16">
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star-half"></i></span>
                            </div>
                            <p class="text-neutral-700">"Great course for beginners and advanced learners alike. The projects were particularly helpful in applying what I learned. Highly recommend!"</p>
                            <span class="d-block border border-neutral-30 my-24 border-dashed"></span>
                            <div class="flex-align gap-24">
                                <img src="assets/images/thumbs/reviewer-img2.png" alt="" class="w-60 h-60 rounded-circle cover-img">
                                <div class="">
                                    <h6 class="text-xl mb-8 fw-medium">Alice Brown</h6>
                                    <span class="text-neutral-700 text-sm">Software Developer</span>
                                </div>
                            </div>
                            <span class="d-block border border-neutral-30 my-24 border-dashed"></span>

                            <div class="flex-align flex-wrap gap-40">
                                <button type="button" class="like-button flex-align gap-8 text-neutral-500 hover-text-main-600">
                                    <span class="like-button__icon text-xl d-flex"><i class="ph-bold ph-thumbs-up"></i></span>
                                    <span class="like-button__text">178</span>
                                </button>
                                <a href="#commentForm" class="flex-align gap-8 text-neutral-500 hover-text-main-600">
                                    <i class="text-xl d-flex ph-bold ph-chat-centered-text"></i>
                                    Reply
                                </a>
                            </div>
                        </div>
                        <!-- Review Item -->

                        <!-- Review Item -->
                        <div class="border border-neutral-30 rounded-12 bg-white p-32 mt-24">
                            <div class="flex-align gap-8 mb-16">
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                <span class="text-xl fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star-half"></i></span>
                            </div>
                            <p class="text-neutral-700">"Dr. Smith is an amazing instructor. Her real-world experience and teaching style made this course one of the best I've ever taken."</p>
                            <span class="d-block border border-neutral-30 my-24 border-dashed"></span>
                            <div class="flex-align gap-24">
                                <img src="assets/images/thumbs/reviewer-img3.png" alt="" class="w-60 h-60 rounded-circle cover-img">
                                <div class="">
                                    <h6 class="text-xl mb-8 fw-medium">David Wilson</h6>
                                    <span class="text-neutral-700 text-sm">Ethical Hacker</span>
                                </div>
                            </div>
                            <span class="d-block border border-neutral-30 my-24 border-dashed"></span>

                            <div class="flex-align flex-wrap gap-40">
                                <button type="button" class="like-button flex-align gap-8 text-neutral-500 hover-text-main-600">
                                    <span class="like-button__icon text-xl d-flex"><i class="ph-bold ph-thumbs-up"></i></span>
                                    <span class="like-button__text">178</span>
                                </button>
                                <a href="#commentForm" class="flex-align gap-8 text-neutral-500 hover-text-main-600">
                                    <i class="text-xl d-flex ph-bold ph-chat-centered-text"></i>
                                    Reply
                                </a>
                            </div>
                        </div>
                        <!-- Review Item -->

                        <button type="button" class="btn btn-main rounded-pill flex-center gap-8 mt-40">
                            See All Comments
                            <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                        </button>
                    </div>
                    <!-- Review End -->

                    <!-- Review Form Start -->
                    <div class="border border-neutral-30 rounded-12 bg-main-25 p-32 mt-24">
                        <form action="#" id="commentForm">
                            <h5 class="mb-0">Write a Comment</h5>
                            <span class="d-block border border-neutral-30 my-32 border-dashed"></span>
                            <div class="mb-24">
                                <label for="name" class="text-neutral-700 text-lg fw-medium mb-12">Name </label>
                                <input type="text" class="common-input rounded-pill" id="name" placeholder="Enter Name...">
                            </div>
                            <div class="mb-24">
                                <label for="email" class="text-neutral-700 text-lg fw-medium mb-12">Email </label>
                                <input type="email" class="common-input rounded-pill" id="email" placeholder="Enter Email...">
                            </div>
                            <div class="mb-24">
                                <label class="text-neutral-700 text-lg fw-medium mb-12">Star Reviews </label>
                                
                                <div id="half-star-rating">
                                    <div class="rating-group">
                                        <input class="rating__input rating__input--none" checked name="rating2" id="rating2-0" value="0" type="radio">
                                        <label aria-label="0 stars" class="rating__label" for="rating2-0">&nbsp;</label>
                                        <label aria-label="0.5 stars" class="rating__label rating__label--half" for="rating2-05"><i class="rating__icon rating__icon--star ph-fill ph-star-half"></i></label>
                                        <input class="rating__input" name="rating2" id="rating2-05" value="0.5" type="radio">
                                        <label aria-label="1 star" class="rating__label" for="rating2-10"><i class="rating__icon rating__icon--star ph-fill ph-star"></i></label>
                                        <input class="rating__input" name="rating2" id="rating2-10" value="1" type="radio">
                                        <label aria-label="1.5 stars" class="rating__label rating__label--half" for="rating2-15"><i class="rating__icon rating__icon--star ph-fill ph-star-half"></i></label>
                                        <input class="rating__input" name="rating2" id="rating2-15" value="1.5" type="radio">
                                        <label aria-label="2 stars" class="rating__label" for="rating2-20"><i class="rating__icon rating__icon--star ph-fill ph-star"></i></label>
                                        <input class="rating__input" name="rating2" id="rating2-20" value="2" type="radio">
                                        <label aria-label="2.5 stars" class="rating__label rating__label--half" for="rating2-25"><i class="rating__icon rating__icon--star ph-fill ph-star-half"></i></label>
                                        <input class="rating__input" name="rating2" id="rating2-25" value="2.5" type="radio" checked>
                                        <label aria-label="3 stars" class="rating__label" for="rating2-30"><i class="rating__icon rating__icon--star ph-fill ph-star"></i></label>
                                        <input class="rating__input" name="rating2" id="rating2-30" value="3" type="radio">
                                        <label aria-label="3.5 stars" class="rating__label rating__label--half" for="rating2-35"><i class="rating__icon rating__icon--star ph-fill ph-star-half"></i></label>
                                        <input class="rating__input" name="rating2" id="rating2-35" value="3.5" type="radio">
                                        <label aria-label="4 stars" class="rating__label" for="rating2-40"><i class="rating__icon rating__icon--star ph-fill ph-star"></i></label>
                                        <input class="rating__input" name="rating2" id="rating2-40" value="4" type="radio">
                                        <label aria-label="4.5 stars" class="rating__label rating__label--half" for="rating2-45"><i class="rating__icon rating__icon--star ph-fill ph-star-half"></i></label>
                                        <input class="rating__input" name="rating2" id="rating2-45" value="4.5" type="radio">
                                        <label aria-label="5 stars" class="rating__label" for="rating2-50"><i class="rating__icon rating__icon--star ph-fill ph-star"></i></label>
                                        <input class="rating__input" name="rating2" id="rating2-50" value="5" type="radio">
                                    </div>
                                </div>

                            </div>
                            <div class="mb-24">
                                <label for="desc" class="text-neutral-700 text-lg fw-medium mb-12">Your Question </label>
                                <textarea id="desc" class="common-input rounded-24" placeholder="Write you question..."></textarea>
                            </div>
                            <div class="mb-0">
                                <button type="submit" class="btn btn-main rounded-pill flex-center gap-8 mt-40">
                                    Submit Comments
                                    <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Review Form End -->                   
                </div>
                <div class="col-lg-4">
                    <div class="border border-neutral-30 rounded-12 bg-main-25 p-32 bg-main-25">
                        <h4 class="mb-16">Search</h4> 
                        <span class="d-block border border-neutral-30 my-24 border-dashed"></span>
                        <form action="#" class="position-relative">
                            <input type="text" class="common-input rounded-pill py-18 pe-64" placeholder="Search...">
                            <button type="submit" class="flex-center w-44 h-44 rounded-circle bg-main-600 hover-bg-main-700 text-white text-2xl position-absolute inset-inline-end-0 top-50 translate-middle-y me-8"><i class="ph ph-magnifying-glass"></i></button>
                        </form>
                    </div>
                    <div class="border border-neutral-30 rounded-12 bg-main-25 p-32 bg-main-25 mt-24">
                        <h4 class="mb-16">More Related Blogs</h4> 
                        <span class="d-block border border-neutral-30 my-24 border-dashed"></span>
                        
                        <div class="flex-align gap-16">
                            <a href="blog.html" class="flex-shrink-0">
                                <img src="assets/images/thumbs/blog-two-img1.png" alt="" class="w-80 h-80 rounded-8 object-fit-cover">
                            </a>
                            <div class="flex-grow-1">
                                <h6 class="text-xl mb-10">
                                    <a href="blog.html" class="hover-text-main-600 text-line-2">The Role of Emotional Intelligence </a>
                                </h6>
                                <span class="text-neutral-500">23/10/24</span>
                            </div>
                        </div>
                        <span class="d-block border border-neutral-30 my-24 border-dashed"></span>
                        
                        <div class="flex-align gap-16">
                            <a href="blog.html" class="flex-shrink-0">
                                <img src="assets/images/thumbs/blog-two-img2.png" alt="" class="w-80 h-80 rounded-8 object-fit-cover">
                            </a>
                            <div class="flex-grow-1">
                                <h6 class="text-xl mb-10">
                                    <a href="blog.html" class="hover-text-main-600 text-line-2">The Psychology of Learning: How Cognitive </a>
                                </h6>
                                <span class="text-neutral-500">23/10/24</span>
                            </div>
                        </div>
                        <span class="d-block border border-neutral-30 my-24 border-dashed"></span>
                        
                        <div class="flex-align gap-16">
                            <a href="blog.html" class="flex-shrink-0">
                                <img src="assets/images/thumbs/blog-two-img3.png" alt="" class="w-80 h-80 rounded-8 object-fit-cover">
                            </a>
                            <div class="flex-grow-1">
                                <h6 class="text-xl mb-10">
                                    <a href="blog.html" class="hover-text-main-600 text-line-2">The Future of Remote Work: Trends </a>
                                </h6>
                                <span class="text-neutral-500">23/10/24</span>
                            </div>
                        </div>
                        <span class="d-block border border-neutral-30 my-24 border-dashed"></span>
                        
                        <div class="flex-align gap-16">
                            <a href="blog.html" class="flex-shrink-0">
                                <img src="assets/images/thumbs/blog-two-img4.png" alt="" class="w-80 h-80 rounded-8 object-fit-cover">
                            </a>
                            <div class="flex-grow-1">
                                <h6 class="text-xl mb-10">
                                    <a href="blog.html" class="hover-text-main-600 text-line-2">Crafting Compelling Presentations</a>
                                </h6>
                                <span class="text-neutral-500">23/10/24</span>
                            </div>
                        </div>
                        <span class="d-block border border-neutral-30 my-24 border-dashed"></span>
                        
                        <div class="flex-align gap-16">
                            <a href="blog.html" class="flex-shrink-0">
                                <img src="assets/images/thumbs/blog-two-img5.png" alt="" class="w-80 h-80 rounded-8 object-fit-cover">
                            </a>
                            <div class="flex-grow-1">
                                <h6 class="text-xl mb-10">
                                    <a href="blog.html" class="hover-text-main-600 text-line-2">Demystifying Data Science</a>
                                </h6>
                                <span class="text-neutral-500">23/10/24</span>
                            </div>
                        </div>
                        <span class="d-block border border-neutral-30 my-24 border-dashed"></span>
                        <a href="blog.html" class="h6 mb-0 text-main-600 fw-semibold hover-text-decoration-underline">
                            See All
                            <i class="ph-bold ph-arrow-right"></i>
                        </a>
                    </div>
                    <div class="border border-neutral-30 rounded-12 bg-main-25 p-32 bg-main-25 mt-24">
                        <h4 class="mb-16">Popular Tag</h4> 
                        <span class="d-block border border-neutral-30 my-24 border-dashed"></span>
                        <div class="flex-align flex-wrap gap-8">
                            <a href="blog.html" class="border border-neutral-30 px-20 py-12 rounded-pill text-sm bg-white text-neutral-500 hover-border-main-600 hover-text-main-600">Machine Learning</a>
                            <a href="blog.html" class="border border-neutral-30 px-20 py-12 rounded-pill text-sm bg-white text-neutral-500 hover-border-main-600 hover-text-main-600">Web Development</a>
                            <a href="blog.html" class="border border-neutral-30 px-20 py-12 rounded-pill text-sm bg-white text-neutral-500 hover-border-main-600 hover-text-main-600">Python</a>
                            <a href="blog.html" class="border border-neutral-30 px-20 py-12 rounded-pill text-sm bg-white text-neutral-500 hover-border-main-600 hover-text-main-600">Laravel</a>
                            <a href="blog.html" class="border border-neutral-30 px-20 py-12 rounded-pill text-sm bg-white text-neutral-500 hover-border-main-600 hover-text-main-600">Wordpress</a>
                            <a href="blog.html" class="border border-neutral-30 px-20 py-12 rounded-pill text-sm bg-white text-neutral-500 hover-border-main-600 hover-text-main-600">UI/UX Design</a>
                            <a href="blog.html" class="border border-neutral-30 px-20 py-12 rounded-pill text-sm bg-white text-neutral-500 hover-border-main-600 hover-text-main-600">AI</a>
                        </div>
                        <span class="d-block border border-neutral-30 my-24 border-dashed"></span>
                        <a href="blog.html" class="h6 mb-0 text-main-600 fw-semibold hover-text-decoration-underline">
                            See All
                            <i class="ph-bold ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
     </div>
    <!-- ================================ Blog Details Section End =================================== -->
    
    <!-- ================================= Certificate Section Start ================================= -->
<div class="certificate">
    <div class="container container--lg">
        <div class="certificate-box px-16 bg-main-600 rounded-16">
            <div class="container">
                <div class="position-relative py-80">
                    <div class="row align-items-center">
                        <div class="col-xl-6">
                            <div class="certificate__content">
                                <div class="flex-align gap-8 mb-16 wow bounceInDown">
                                    <span class="w-8 h-8 bg-white rounded-circle"></span>
                                    <h5 class="text-white mb-0">Get Certificate</h5>
                                </div>
                                <h2 class="text-white mb-40 fw-medium wow bounceIn">Get Quality Skills Certificate From the EduAll</h2>
                                <a href="" class="btn btn-white rounded-pill flex-align d-inline-flex gap-8 hover-bg-main-800 wow bounceInUp">
                                    Get Started Now
                                    <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-6 d-xl-block d-none">
                            <div class="certificate__thumb" data-aos="fade-up-left">    
                                <img src="assets/images/thumbs/certificate-img.png" alt="" data-tilt data-tilt-max="8" data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-full-page-listening>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>
<!-- ================================= Certificate Section End ================================= -->
    
    
<!-- ==================== Footer Start Here ==================== -->
@endsection