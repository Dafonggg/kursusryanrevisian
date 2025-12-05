@extends('layouts.app')

@section('title', 'Kursus Ryan Komputer - Main Page')

@section('body-class', '')

@section('content')

{{-- HERO SECTION - From index.blade.php --}}
<section class="hero-section d-flex justify-content-center align-items-center" id="section_1">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12 mx-auto">
                <h1 class="text-white text-center">Discover. Learn. Enjoy</h1>
                <h6 class="text-center">platform for creatives around the world</h6>
                <form method="get" class="custom-form mt-4 pt-2 mb-lg-0 mb-5" role="search">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bi-search" id="basic-addon1"></span>
                        <input name="keyword" type="search" class="form-control" id="keyword" placeholder="Design, Code, Marketing, Finance ..." aria-label="Search">
                        <button type="submit" class="form-control">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- FEATURED SECTION - From index.blade.php --}}
<section class="featured-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-12 mb-4 mb-lg-0">
                <div class="custom-block bg-white shadow-lg">
                    <a href="{{ url('/topics-detail') }}">
                        <div class="d-flex">
                            <div>
                                <h5 class="mb-2">Web Design</h5>
                                <p class="mb-0">When you search for free CSS templates, you will notice that TemplateMo is one of the best websites.</p>
                            </div>
                            <span class="badge bg-design rounded-pill ms-auto">14</span>
                        </div>
                        <img src="{{ asset('images/topics/undraw_Remote_design_team_re_urdx.png') }}" class="custom-block-image img-fluid" alt="">
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="custom-block custom-block-overlay">
                    <div class="d-flex flex-column h-100">
                        <img src="{{ asset('images/businesswoman-using-tablet-analysis.jpg') }}" class="custom-block-image img-fluid" alt="">
                        <div class="custom-block-overlay-text d-flex">
                            <div>
                                <h5 class="text-white mb-2">Finance</h5>
                                <p class="text-white">Topic Listing Template includes homepage, listing page, detail page, and contact page. You can feel free to edit and adapt for your CMS requirements.</p>
                                <a href="{{ route('topics-detail') }}" class="btn custom-btn mt-2 mt-lg-3">Learn More</a>
                            </div>
                            <span class="badge bg-finance rounded-pill ms-auto">25</span>
                        </div>
                        <div class="section-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- BROWSE TOPICS SECTION - From index.blade.php --}}
<section class="explore-section section-padding" id="section_2">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="mb-4">Browse Topics</h2>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="design-tab" data-bs-toggle="tab" data-bs-target="#design-tab-pane" type="button" role="tab" aria-controls="design-tab-pane" aria-selected="true">Design</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="marketing-tab" data-bs-toggle="tab" data-bs-target="#marketing-tab-pane" type="button" role="tab" aria-controls="marketing-tab-pane" aria-selected="false">Marketing</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="finance-tab" data-bs-toggle="tab" data-bs-target="#finance-tab-pane" type="button" role="tab" aria-controls="finance-tab-pane" aria-selected="false">Finance</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="music-tab" data-bs-toggle="tab" data-bs-target="#music-tab-pane" type="button" role="tab" aria-controls="music-tab-pane" aria-selected="false">Music</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="education-tab" data-bs-toggle="tab" data-bs-target="#education-tab-pane" type="button" role="tab" aria-controls="education-tab-pane" aria-selected="false">Education</button>
                </li>
            </ul>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="design-tab-pane" role="tabpanel" aria-labelledby="design-tab" tabindex="0">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-0">
                                <div class="custom-block bg-white shadow-lg">
                                    <a href="{{ url('/topics-detail') }}">
                                        <div class="d-flex">
                                            <div>
                                                <h5 class="mb-2">Web Design</h5>
                                                <p class="mb-0">Topic Listing Template based on Bootstrap 5</p>
                                            </div>
                                            <span class="badge bg-design rounded-pill ms-auto">14</span>
                                        </div>
                                        <img src="{{ asset('images/topics/undraw_Remote_design_team_re_urdx.png') }}" class="custom-block-image img-fluid" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-0">
                                <div class="custom-block bg-white shadow-lg">
                                    <a href="{{ url('/topics-detail') }}">
                                        <div class="d-flex">
                                            <div>
                                                <h5 class="mb-2">Graphic</h5>
                                                <p class="mb-0">Lorem Ipsum dolor sit amet consectetur</p>
                                            </div>
                                            <span class="badge bg-design rounded-pill ms-auto">75</span>
                                        </div>
                                        <img src="{{ asset('images/topics/undraw_Redesign_feedback_re_jvm0.png') }}" class="custom-block-image img-fluid" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="custom-block bg-white shadow-lg">
                                    <a href="{{ url('/topics-detail') }}">
                                        <div class="d-flex">
                                            <div>
                                                <h5 class="mb-2">Logo Design</h5>
                                                <p class="mb-0">Lorem Ipsum dolor sit amet consectetur</p>
                                            </div>
                                            <span class="badge bg-design rounded-pill ms-auto">100</span>
                                        </div>
                                        <img src="{{ asset('images/topics/colleagues-working-cozy-office-medium-shot.png') }}" class="custom-block-image img-fluid" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="marketing-tab-pane" role="tabpanel" aria-labelledby="marketing-tab" tabindex="0">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-3">
                                <div class="custom-block bg-white shadow-lg">
                                    <a href="{{ url('/topics-detail') }}">
                                        <div class="d-flex">
                                            <div>
                                                <h5 class="mb-2">Advertising</h5>
                                                <p class="mb-0">Lorem Ipsum dolor sit amet consectetur</p>
                                            </div>
                                            <span class="badge bg-advertising rounded-pill ms-auto">30</span>
                                        </div>
                                        <img src="{{ asset('images/topics/undraw_online_ad_re_ol62.png') }}" class="custom-block-image img-fluid" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-3">
                                <div class="custom-block bg-white shadow-lg">
                                    <a href="{{ url('/topics-detail') }}">
                                        <div class="d-flex">
                                            <div>
                                                <h5 class="mb-2">Video Content</h5>
                                                <p class="mb-0">Lorem Ipsum dolor sit amet consectetur</p>
                                            </div>
                                            <span class="badge bg-advertising rounded-pill ms-auto">65</span>
                                        </div>
                                        <img src="{{ asset('images/topics/undraw_Group_video_re_btu7.png') }}" class="custom-block-image img-fluid" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="custom-block bg-white shadow-lg">
                                    <a href="{{ url('/topics-detail') }}">
                                        <div class="d-flex">
                                            <div>
                                                <h5 class="mb-2">Viral Tweet</h5>
                                                <p class="mb-0">Lorem Ipsum dolor sit amet consectetur</p>
                                            </div>
                                            <span class="badge bg-advertising rounded-pill ms-auto">50</span>
                                        </div>
                                        <img src="{{ asset('images/topics/undraw_viral_tweet_gndb.png') }}" class="custom-block-image img-fluid" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="finance-tab-pane" role="tabpanel" aria-labelledby="finance-tab" tabindex="0">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12 mb-4 mb-lg-0">
                                <div class="custom-block bg-white shadow-lg">
                                    <a href="{{ url('/topics-detail') }}">
                                        <div class="d-flex">
                                            <div>
                                                <h5 class="mb-2">Investment</h5>
                                                <p class="mb-0">Lorem Ipsum dolor sit amet consectetur</p>
                                            </div>
                                            <span class="badge bg-finance rounded-pill ms-auto">30</span>
                                        </div>
                                        <img src="{{ asset('images/topics/undraw_Finance_re_gnv2.png') }}" class="custom-block-image img-fluid" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="custom-block custom-block-overlay">
                                    <div class="d-flex flex-column h-100">
                                        <img src="{{ asset('images/businesswoman-using-tablet-analysis.jpg') }}" class="custom-block-image img-fluid" alt="">
                                        <div class="custom-block-overlay-text d-flex">
                                            <div>
                                                <h5 class="text-white mb-2">Finance</h5>
                                                <p class="text-white">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sint animi necessitatibus aperiam repudiandae nam omnis</p>
                                                <a href="{{ route('topics-detail') }}" class="btn custom-btn mt-2 mt-lg-3">Learn More</a>
                                            </div>
                                            <span class="badge bg-finance rounded-pill ms-auto">25</span>
                                        </div>
                                        <div class="section-overlay"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="music-tab-pane" role="tabpanel" aria-labelledby="music-tab" tabindex="0">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-3">
                                <div class="custom-block bg-white shadow-lg">
                                    <a href="{{ url('/topics-detail') }}">
                                        <div class="d-flex">
                                            <div>
                                                <h5 class="mb-2">Composing Song</h5>
                                                <p class="mb-0">Lorem Ipsum dolor sit amet consectetur</p>
                                            </div>
                                            <span class="badge bg-music rounded-pill ms-auto">45</span>
                                        </div>
                                        <img src="{{ asset('images/topics/undraw_Compose_music_re_wpiw.png') }}" class="custom-block-image img-fluid" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-3">
                                <div class="custom-block bg-white shadow-lg">
                                    <a href="{{ url('/topics-detail') }}">
                                        <div class="d-flex">
                                            <div>
                                                <h5 class="mb-2">Online Music</h5>
                                                <p class="mb-0">Lorem Ipsum dolor sit amet consectetur</p>
                                            </div>
                                            <span class="badge bg-music rounded-pill ms-auto">45</span>
                                        </div>
                                        <img src="{{ asset('images/topics/undraw_happy_music_g6wc.png') }}" class="custom-block-image img-fluid" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="custom-block bg-white shadow-lg">
                                    <a href="{{ url('/topics-detail') }}">
                                        <div class="d-flex">
                                            <div>
                                                <h5 class="mb-2">Podcast</h5>
                                                <p class="mb-0">Lorem Ipsum dolor sit amet consectetur</p>
                                            </div>
                                            <span class="badge bg-music rounded-pill ms-auto">20</span>
                                        </div>
                                        <img src="{{ asset('images/topics/undraw_Podcast_audience_re_4i5q.png') }}" class="custom-block-image img-fluid" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="education-tab-pane" role="tabpanel" aria-labelledby="education-tab" tabindex="0">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12 mb-4 mb-lg-3">
                                <div class="custom-block bg-white shadow-lg">
                                    <a href="{{ url('/topics-detail') }}">
                                        <div class="d-flex">
                                            <div>
                                                <h5 class="mb-2">Graduation</h5>
                                                <p class="mb-0">Lorem Ipsum dolor sit amet consectetur</p>
                                            </div>
                                            <span class="badge bg-education rounded-pill ms-auto">80</span>
                                        </div>
                                        <img src="{{ asset('images/topics/undraw_Graduation_re_gthn.png') }}" class="custom-block-image img-fluid" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="custom-block bg-white shadow-lg">
                                    <a href="{{ url('/topics-detail') }}">
                                        <div class="d-flex">
                                            <div>
                                                <h5 class="mb-2">Educator</h5>
                                                <p class="mb-0">Lorem Ipsum dolor sit amet consectetur</p>
                                            </div>
                                            <span class="badge bg-education rounded-pill ms-auto">75</span>
                                        </div>
                                        <img src="{{ asset('images/topics/undraw_Educator_re_ju47.png') }}" class="custom-block-image img-fluid" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- HOW IT WORKS SECTION - From index.blade.php --}}
<section class="timeline-section section-padding" id="section_3">
    <div class="section-overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="text-white mb-4">How does it work?</h2>
            </div>
            <div class="col-lg-10 col-12 mx-auto">
                <div class="timeline-container">
                    <ul class="vertical-scrollable-timeline" id="vertical-scrollable-timeline">
                        <div class="list-progress">
                            <div class="inner"></div>
                        </div>
                        <li>
                            <h4 class="text-white mb-3">Search your favourite topic</h4>
                            <p class="text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis, cumque magnam? Sequi, cupiditate quibusdam alias illum sed esse ad dignissimos libero sunt, quisquam numquam aliquam? Voluptas, accusamus omnis?</p>
                            <div class="icon-holder">
                                <i class="bi-search"></i>
                            </div>
                        </li>
                        <li>
                            <h4 class="text-white mb-3">Bookmark &amp; Keep it for yourself</h4>
                            <p class="text-white">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sint animi necessitatibus aperiam repudiandae nam omnis est vel quo, nihil repellat quia velit error modi earum similique odit labore. Doloremque, repudiandae?</p>
                            <div class="icon-holder">
                                <i class="bi-bookmark"></i>
                            </div>
                        </li>
                        <li>
                            <h4 class="text-white mb-3">Read &amp; Enjoy</h4>
                            <p class="text-white">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Animi vero quisquam, rem assumenda similique voluptas distinctio, iste est hic eveniet debitis ut ducimus beatae id? Quam culpa deleniti officiis autem?</p>
                            <div class="icon-holder">
                                <i class="bi-book"></i>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-12 text-center mt-5">
                <p class="text-white">
                    Want to learn more?
                    <a href="#" class="btn custom-btn custom-border-btn ms-3">Check out Youtube</a>
                </p>
            </div>
        </div>
    </div>
</section>

{{-- FAQ SECTION - From index.blade.php --}}
<section class="faq-section section-padding" id="section_4">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-12">
                <h2 class="mb-4">Frequently Asked Questions</h2>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-5 col-12">
                <img src="{{ asset('images/faq_graphic.jpg') }}" class="img-fluid" alt="FAQs">
            </div>
            <div class="col-lg-6 col-12 m-auto">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                What is Topic Listing?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Topic Listing is free Bootstrap 5 CSS template. <strong>You are not allowed to redistribute this template</strong> on any other template collection website without our permission. Please contact TemplateMo for more detail. Thank you.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                How to find a topic?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                You can search on Google with <strong>keywords</strong> such as templatemo portfolio, templatemo one-page layouts, photography, digital marketing, etc.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Does it need to paid?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- TOPICS LISTING SECTION - From topics-listing.blade.php --}}
<section class="section-padding" id="topics-listing-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center">
                <h3 class="mb-4">Popular Topics</h3>
            </div>
            <div class="col-lg-8 col-12 mt-3 mx-auto">
                <div class="custom-block custom-block-topics-listing bg-white shadow-lg mb-5">
                    <div class="d-flex">
                        <img src="{{ asset('images/topics/undraw_Remote_design_team_re_urdx.png') }}" class="custom-block-image img-fluid" alt="">
                        <div class="custom-block-topics-listing-info d-flex">
                            <div>
                                <h5 class="mb-2">Web Design</h5>
                                <p class="mb-0">Topic Listing includes home, listing, detail and contact pages. Feel free to modify this template for your custom websites.</p>
                                <a href="{{ url('/topics-detail') }}" class="btn custom-btn mt-3 mt-lg-4">Learn More</a>
                            </div>
                            <span class="badge bg-design rounded-pill ms-auto">14</span>
                        </div>
                    </div>
                </div>
                <div class="custom-block custom-block-topics-listing bg-white shadow-lg mb-5">
                    <div class="d-flex">
                        <img src="{{ asset('images/topics/undraw_online_ad_re_ol62.png') }}" class="custom-block-image img-fluid" alt="">
                        <div class="custom-block-topics-listing-info d-flex">
                            <div>
                                <h5 class="mb-2">Advertising</h5>
                                <p class="mb-0">Visit TemplateMo website to download free CSS templates. Lorem ipsum dolor, sit amet consectetur adipisicing elit animi necessitatibus</p>
                                <a href="{{ url('/topics-detail') }}" class="btn custom-btn mt-3 mt-lg-4">Learn More</a>
                            </div>
                            <span class="badge bg-advertising rounded-pill ms-auto">30</span>
                        </div>
                    </div>
                </div>
                <div class="custom-block custom-block-topics-listing bg-white shadow-lg mb-5">
                    <div class="d-flex">
                        <img src="{{ asset('images/topics/undraw_Podcast_audience_re_4i5q.png') }}" class="custom-block-image img-fluid" alt="">
                        <div class="custom-block-topics-listing-info d-flex">
                            <div>
                                <h5 class="mb-2">Podcast</h5>
                                <p class="mb-0">Lorem ipsum dolor, sit amet consectetur adipisicing elit animi necessitatibus</p>
                                <a href="{{ url('/topics-detail') }}" class="btn custom-btn mt-3 mt-lg-4">Learn More</a>
                            </div>
                            <span class="badge bg-music rounded-pill ms-auto">20</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- TRENDING TOPICS SECTION - From topics-listing.blade.php --}}
<section class="section-padding section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <h3 class="mb-4">Trending Topics</h3>
            </div>
            <div class="col-lg-6 col-md-6 col-12 mt-3 mb-4 mb-lg-0">
                <div class="custom-block bg-white shadow-lg">
                    <a href="{{ url('/topics-detail') }}">
                        <div class="d-flex">
                            <div>
                                <h5 class="mb-2">Investment</h5>
                                <p class="mb-0">Lorem Ipsum dolor sit amet consectetur</p>
                            </div>
                            <span class="badge bg-finance rounded-pill ms-auto">30</span>
                        </div>
                        <img src="{{ asset('images/topics/undraw_Finance_re_gnv2.png') }}" class="custom-block-image img-fluid" alt="">
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12 mt-lg-3">
                <div class="custom-block custom-block-overlay">
                    <div class="d-flex flex-column h-100">
                        <img src="{{ asset('images/businesswoman-using-tablet-analysis.jpg') }}" class="custom-block-image img-fluid" alt="">
                        <div class="custom-block-overlay-text d-flex">
                            <div>
                                <h5 class="text-white mb-2">Finance</h5>
                                <p class="text-white">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sint animi necessitatibus aperiam repudiandae nam omnis</p>
                                <a href="{{ route('topics-detail') }}" class="btn custom-btn mt-2 mt-lg-3">Learn More</a>
                            </div>
                            <span class="badge bg-finance rounded-pill ms-auto">25</span>
                        </div>
                        <div class="section-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- TOPICS DETAIL SECTION - From topics-detail.blade.php --}}
<section class="topics-detail-section section-padding" id="topics-detail-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12 m-auto">
                <h3 class="mb-4">Introduction to Web Design</h3>
                <p>So how can you stand out, do something unique and interesting, build an online business, and get paid enough to change life. Please visit TemplateMo website to download free website templates.</p>
                <p><strong>There are so many ways to make money online</strong>. Below are several platforms you can use to find success. Keep in mind that there is no one path everyone can take. If that were the case, everyone would have a million dollars.</p>
                <blockquote>
                    Freelancing your skills isn't going to make you a millionaire overnight.
                </blockquote>
                <div class="row my-4">
                    <div class="col-lg-6 col-md-6 col-12">
                        <img src="{{ asset('images/businesswoman-using-tablet-analysis.jpg') }}" class="topics-detail-block-image img-fluid">
                    </div>
                    <div class="col-lg-6 col-md-6 col-12 mt-4 mt-lg-0 mt-md-0">
                        <img src="{{ asset('images/colleagues-working-cozy-office-medium-shot.jpg') }}" class="topics-detail-block-image img-fluid">
                    </div>
                </div>
                <p>Most people start with freelancing skills they already have as a side hustle to build up income. This extra cash can be used for a vacation, to boost up savings, investing, build business.</p>
            </div>
        </div>
    </div>
</section>

{{-- NEWSLETTER SECTION - From topics-detail.blade.php --}}
<section class="section-padding section-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-12">
                <img src="{{ asset('images/rear-view-young-college-student.jpg') }}" class="newsletter-image img-fluid" alt="">
            </div>
            <div class="col-lg-5 col-12 subscribe-form-wrap d-flex justify-content-center align-items-center">
                <form class="custom-form subscribe-form" action="#" method="post" role="form">
                    <h4 class="mb-4 pb-2">Get Newsletter</h4>
                    <input type="email" name="subscribe-email" id="subscribe-email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Email Address" required="">
                    <div class="col-lg-12 col-12">
                        <button type="submit" class="form-control">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- CONTACT SECTION - From contact.blade.php --}}
<section class="section-padding section-bg" id="section_5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <h3 class="mb-4 pb-2">We'd love to hear from you</h3>
            </div>
            <div class="col-lg-6 col-12">
                <form action="#" method="post" class="custom-form contact-form" role="form">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-floating">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" required="">
                                <label for="floatingInput">Name</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-floating">
                                <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Email address" required="">
                                <label for="floatingInput">Email address</label>
                            </div>
                        </div>
                        <div class="col-lg-12 col-12">
                            <div class="form-floating">
                                <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject" required="">
                                <label for="floatingInput">Subject</label>
                            </div>
                            <div class="form-floating">
                                <textarea class="form-control" id="message" name="message" placeholder="Tell me about the project"></textarea>
                                <label for="floatingTextarea">Tell me about the project</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 ms-auto">
                            <button type="submit" class="form-control">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-5 col-12 mx-auto mt-5 mt-lg-0">
                <iframe class="google-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2595.065641062665!2d-122.4230416990949!3d37.80335401520422!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80858127459fabad%3A0x808ba520e5e9edb7!2sFrancisco%20Park!5e1!3m2!1sen!2sth!4v1684340239744!5m2!1sen!2sth" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <h5 class="mt-4 mb-2">Topic Listing Center</h5>
                <p>Bay St &amp;, Larkin St, San Francisco, CA 94109, United States</p>
            </div>
        </div>
    </div>
</section>

{{-- CONTACT INFO SECTION - From index.blade.php --}}
<section class="contact-section section-padding section-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center">
                <h2 class="mb-5">Get in touch</h2>
            </div>
            <div class="col-lg-5 col-12 mb-4 mb-lg-0">
                <iframe class="google-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2595.065641062665!2d-122.4230416990949!3d37.80335401520422!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80858127459fabad%3A0x808ba520e5e9edb7!2sFrancisco%20Park!5e1!3m2!1sen!2sth!4v1684340239744!5m2!1sen!2sth" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-3 mb-lg- mb-md-0 ms-auto">
                <h4 class="mb-3">Head office</h4>
                <p>Bay St &amp;, Larkin St, San Francisco, CA 94109, United States</p>
                <hr>
                <p class="d-flex align-items-center mb-1">
                    <span class="me-2">Phone</span>
                    <a href="tel: 305-240-9671" class="site-footer-link">305-240-9671</a>
                </p>
                <p class="d-flex align-items-center">
                    <span class="me-2">Email</span>
                    <a href="mailto:info@company.com" class="site-footer-link">info@company.com</a>
                </p>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mx-auto">
                <h4 class="mb-3">Dubai office</h4>
                <p>Burj Park, Downtown Dubai, United Arab Emirates</p>
                <hr>
                <p class="d-flex align-items-center mb-1">
                    <span class="me-2">Phone</span>
                    <a href="tel: 110-220-3400" class="site-footer-link">110-220-3400</a>
                </p>
                <p class="d-flex align-items-center">
                    <span class="me-2">Email</span>
                    <a href="mailto:info@company.com" class="site-footer-link">info@company.com</a>
                </p>
            </div>
        </div>
    </div>
</section>

@endsection


