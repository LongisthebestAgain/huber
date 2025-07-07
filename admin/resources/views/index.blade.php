@extends('layouts.app')
@section('title', 'Huber - Home Page')

@section('content')
<header id="home" class="hero">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="display-4 text-white fw-bold mb-4">
                    Your Journey, Our Priority
                </h1>
                <p class="lead text-white mb-4">
                    Experience the future of ride-sharing with Hubber. Enjoy
                    seamless travel, competitive prices, and a trusted community
                    of drivers at your fingertips.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-3">Get Started</a>
                    <a href="#how-it-works" class="btn btn-outline-light btn-lg">Learn More</a>
                </div>
                <div class="hero-stats mt-5">
                    <div class="row text-white">
                        <div class="col-4">
                            <h3>10K+</h3>
                            <p>Active Users</p>
                        </div>
                        <div class="col-4">
                            <h3>5K+</h3>
                            <p>Drivers</p>
                        </div>
                        <div class="col-4">
                            <h3>100K+</h3>
                            <p>Rides</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block" data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80"
                    alt="Hubber Car" class="img-fluid hero-image" />
            </div>
        </div>
    </div>
</header>

<!-- How It Works Section -->
<section id="how-it-works" class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">How Hubber Works</h2>
            <p class="section-subtitle">
                Get started with Hubber in three simple steps
            </p>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3>Create Account</h3>
                    <p>
                        Sign up as a passenger or driver in minutes. Join our
                        growing community of trusted users.
                    </p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Book or List Ride</h3>
                    <p>
                        Find available rides or create listings as a driver. Set
                        your own schedule and preferences.
                    </p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>Enjoy & Rate</h3>
                    <p>
                        Travel safely and share your experience. Help build a
                        trusted community through reviews.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="features py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Why Choose Hubber?</h2>
            <p class="section-subtitle">
                Experience the best in modern ride-sharing
            </p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-box">
                    <i class="fas fa-shield-alt"></i>
                    <h4>Safe & Secure</h4>
                    <p>
                        Verified drivers and secure payment system for
                        worry-free travel.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-box">
                    <i class="fas fa-dollar-sign"></i>
                    <h4>Best Prices</h4>
                    <p>
                        Competitive rates and transparent pricing with no hidden
                        fees.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-box">
                    <i class="fas fa-clock"></i>
                    <h4>24/7 Support</h4>
                    <p>
                        Round-the-clock customer assistance whenever you need
                        help.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-box">
                    <i class="fas fa-mobile-alt"></i>
                    <h4>Easy to Use</h4>
                    <p>
                        User-friendly interface for seamless booking and ride
                        management.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">What Our Users Say</h2>
            <p class="section-subtitle">Real experiences from our community</p>
        </div>
        <div class="row">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <i class="fas fa-quote-left"></i>
                        <p>
                            "Hubber has made my daily commute so much easier.
                            The drivers are professional and the app is super
                            easy to use!"
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-info">
                            <h5>Sarah M.</h5>
                            <p>Regular Passenger</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <i class="fas fa-quote-left"></i>
                        <p>
                            "As a driver, I love the flexibility Hubber offers.
                            I can set my own schedule and earn extra income on
                            my terms."
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-info">
                            <h5>John D.</h5>
                            <p>Driver Partner</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <i class="fas fa-quote-left"></i>
                        <p>
                            "The best ride-sharing platform I've used. Great
                            prices, friendly drivers, and excellent customer
                            support!"
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-info">
                            <h5>Mike R.</h5>
                            <p>Business Traveler</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mobile App Section -->

<!-- FAQ Section -->
<section id="faq" class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="section-subtitle">
                Find answers to common questions about Hubber
            </p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item" data-aos="fade-up">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq1">
                                How do I become a driver?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                To become a driver, simply sign up through our
                                app or website, submit required documentation,
                                and complete our verification process. Once
                                approved, you can start accepting rides!
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item" data-aos="fade-up">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq2">
                                How is the fare calculated?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Fares are calculated based on distance, time,
                                and current demand. All prices are shown upfront
                                before you confirm your ride.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item" data-aos="fade-up">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq3">
                                Is my payment information secure?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, we use industry-standard encryption to
                                protect your payment information. All
                                transactions are processed securely through our
                                trusted payment partners.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
