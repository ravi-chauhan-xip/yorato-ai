@extends('website.layouts.master')

@section('title',''.settings('company_name'))

@section('content')
    <section class="hero-section" id="section_1">
        <div class="container d-flex justify-content-center align-items-center">
            <div class="row">
                <div class="col-12 mt-auto mb-5 text-center" style="
    position: absolute;
    top: 25%;
">
                    <small data-aos="fade-up" data-aos-delay="200">{{ settings('company_name') }} </small>
                    <h1 class="text-white mb-4" data-aos="fade-down" data-aos-delay="200">
                        The worldwide community creator opportunities
                    </h1>
                    <h5 class="mb-5" data-aos="fade-down" data-aos-delay="200"> Membership for your rich future</h5>
                    <div class="d-flex justify-content-center align-items-center " data-aos="fade-up"
                         data-aos-delay="200">
                        <a class="btn custom-btn smoothscroll"
                           href="{{ route('user.register.create') }}">
                            Connect Wallet
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-12 mt-auto d-flex flex-column flex-lg-row text-center"></div>
        </div>
        <div class="video-wrap">
            <video autoplay muted loop plays-inline class="custom-video" poster="">
                <source src="{{ asset('video/metavideo.mp4') }}" type="video/mp4">
            </video>
        </div>
        <div class="layar slidd">
            <img class="spinnerr" alt="Loading…" src="{{ asset('images/star.png') }}" width="100"/>
        </div>
        <div class="layar-1 slidd">
            <img class="spinnerr" alt="Loading…" src="{{ asset('images/star.png') }}" width="100"/>
        </div>
        <div class="layar-2 slidd">
            <img class="spinnerr" alt="Loading…" src="{{ asset('images/star.png') }}"/>
        </div>
    </section>
    <section class="about-section section-bg section-padding" id="About">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-12 mb-lg-0 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <h2>The Technology that gives Opportunity!</h2>
                    <p>
                        {{ settings('company_name') }} Token has limited supply, used by thousands people to trade,
                        shopping and general usage. Which delivers social impact and creates the new substandable
                        circular economy it is a future open source Blockchain based crypto currency with fast
                        transaction and low fee.
                    </p>
                    <a class="btn custom-btn smoothscroll mt-2" href="{{ route('user.register.create') }}">Connect Wallet</a>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-5 col-md-8 col-sm-8 col-12 mx-auto ">
                    <div class="about-text-wrap">
                        <img src="{{ asset('images/blockchain-about.png') }}" class="about-image img-fluid" data-aos="fade-up"
                             data-aos-delay="200" alt="">
                        <div class="about-text-info  ">
                            <ul class="goals">
                                <li data-aos="fade-right" data-aos-delay="200">
                                    <div class="icon">
                                        <i class="bi bi-magic"></i>
                                    </div>
                                    <div class="text">Freedom of investment</div>
                                </li>
                                <li data-aos="fade-left" data-aos-delay="200">
                                    <div class="icon">
                                        <i class="bi bi-shield-lock-fill"></i>
                                    </div>
                                    <div class="text">SECURE AND CONVEINIENT</div>
                                </li>
                                <li data-aos="fade-right" data-aos-delay="200">
                                    <div class="icon">
                                        <i class="bi bi-sliders"></i>
                                    </div>
                                    <div class="text">START WITH AN AMOUNT OF</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="ozone-section section-bg section-padding" id="section">
        <div class="heading  " data-aos="fade-down" data-aos-delay="200">
            <h2 class="text-white text-center mb-5">{{ settings('company_name') }} Upcomming projects</h2>
        </div>
        <div class="container">
            <div class="row gy-3">
                <div class="col-lg-4 col-md-6">
                    <div class="card card-ozone" data-aos="fade-right" data-aos-offset="230"
                         data-aos-easing="ease-in-sine">
                        <div class="card-body">
                            <div class="d-flex justify-content-center mb-2">
                                <div class="card-icon text-center">
                                    <span><i class="bi bi-coin"></i></span>
                                </div>
                            </div>
                            <h3>{{ settings('company_name') }} COIN (MGC) </h3>
                            <p>
                                {{ settings('company_name') }} Coin is a cutting-edge cryptocurrency that empowers
                                secure and borderless transactions on a decentralized blockchain network. Invest
                                responsibly and embark on a journey to financial freedom with MGC Coin.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card card-ozone" data-aos="fade-up" data-aos-offset="230"
                         data-aos-easing="ease-in-sine">
                        <div class="card-body">
                            <div class="d-flex justify-content-center mb-2">
                                <div class="card-icon text-center">
                                    <span><i class="bi bi-lock"></i></span>
                                </div>
                            </div>
                            <h3>{{ settings('company_name') }} EXCHANGE</h3>
                            <p>
                                {{ settings('company_name') }} Exchange is a platform or marketplace where individuals
                                and businesses can buy, sell, and trade various fiat and cryptocurrencies.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card card-ozone" data-aos="fade-left" data-aos-offset="230"
                         data-aos-easing="ease-in-sine">
                        <div class="card-body">
                            <div class="d-flex justify-content-center mb-2">
                                <div class="card-icon text-center">
                                    <span><i class="bi bi-magnet"></i></span>
                                </div>
                            </div>
                            <h3>{{ settings('company_name') }} BLOCKCHAIN</h3>
                            <p>
                                {{ settings('company_name') }} Blockchain is a decentralized and distributed
                                ledger technology that allows digital information to be recorded shared across
                                multiple participants in a secure and transparent manner.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-bg section-padding features" id="section ">
        <div class="container">

            <div class="row aign-items-center">
                <div class="col-lg-6 mt-2 col-12 mb-lg-0 order-2 order-lg-1">
                    <ul class="nav nav-tabs flex-column">
                        <li class="nav-item">
                            <a class="nav-link active show" data-bs-toggle="tab" href="#tab-1">
                                <h4>{{ settings('company_name') }}</h4>
                                <p>
                                    META GLOBE holders with the ability to access ease in transfer of
                                    payments, buying assets, trading, and many other benefits. Meta
                                    Boss aims to assemble a platform for the effortless and secure
                                    exchange of your digital assets throughout the globe.
                                </p>
                            </a>
                        </li>
                        <li class="nav-item mt-2" data-aos="fade-up" data-aos-delay="100">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-2">
                                <h4>Immutable conditions</h4>
                                <p>Blockchain secures the algorithm, therefore nobody, even the authors of the idea, can
                                    interfere, cancel, or alter your transactions.</p>
                            </a>
                        </li>
                        <li class="nav-item mt-2" data-aos="fade-up" data-aos-delay="200">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-3">
                                <h4>Decentralized</h4>
                                <p>
                                    There are no managers or admins at the head, the creators are the same platform
                                    participants like everyone else.
                                </p>
                            </a>
                        </li>
                        <li class="nav-item mt-2" data-aos="fade-up" data-aos-delay="300">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-4">
                                <h4>Fully automatic</h4>
                                <p>All transactions between the community members are executed directly from one
                                    personal wallet to another. There are no accounts to withdraw from,
                                    {{ settings('company_name') }} does not store your funds.</p>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6 col-12 mb-lg-0 order-1 order-lg-2" data-aos="zoom-in-up" data-aos-delay="200">
                    <div class="tab-content">

                        <img src="/images/robot2.png" alt="" class="img-fluid">


                    </div>
                </div>
            </div>

        </div>
    </section>
    <section class="accordia-section section-bg section-padding" id="Faq">
        <div class="heading" data-aos="fade-down" data-aos-delay="200">
            <h2 class="text-white text-center mb-5">Frequently Asked Questions</h2>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12 ">
                    <div class="about-text-wrap accordian-left-image" data-aos="fade-right" data-aos-offset="250"
                         data-aos-easing="ease-in-sine">
                        <img src="{{ asset('images/faq.png') }}" class="about-image img-fluid">
                    </div>
                </div>
                <div class="col-lg-6 col-12 mb-4 mb-lg-0 d-flex align-items-center ">
                    <div class="services-info">
                        <div class="accordion-list">
                            <ul>
                                <li data-aos="fade-left" data-aos-delay="200">
                                    <a data-bs-toggle="collapse" class="collapsed"
                                       data-bs-target="#accordion-list-1"><span>01</span> What is
                                        {{ settings('company_name') }}? <i class="bi bi-chevron-down icon-show"></i><i
                                            class="bi bi-chevron-up icon-close"></i></a>
                                    <div id="accordion-list-1" class="collapse" data-bs-parent=".accordion-list">
                                        <p>{{ settings('company_name') }} is a digital cryptocurrency that operates on a
                                            decentralized blockchain network. It is designed to facilitate secure, fast,
                                            and borderless transactions while</p>
                                    </div>
                                </li>
                                <li data-aos="fade-left" data-aos-delay="300">
                                    <a data-bs-toggle="collapse" data-bs-target="#accordion-list-2"
                                       class="collapsed"><span>02</span> Who is the behind
                                        {{ settings('company_name') }}? <i class="bi bi-chevron-down icon-show"></i><i
                                            class="bi bi-chevron-up icon-close"></i></a>
                                    <div id="accordion-list-2" class="collapse" data-bs-parent=".accordion-list">
                                        <p>AUTOMATION Technology introduces the {{ settings('company_name') }} network.
                                            It is a fintech company that provides all types of Digital solutions.</p>
                                    </div>
                                </li>
                                <li data-aos="fade-left" data-aos-delay="400">
                                    <a data-bs-toggle="collapse" data-bs-target="#accordion-list-3"
                                       class="collapsed"><span>03</span> Is Is the website available for
                                        {{ settings('company_name') }}? <i class="bi bi-chevron-down icon-show"></i><i
                                            class="bi bi-chevron-up icon-close"></i></a>
                                    <div id="accordion-list-3" class="collapse" data-bs-parent=".accordion-list">
                                        <p>Yes, available on its main website {{ env('APP_URL') }}</p>
                                    </div>
                                </li>
                                <li data-aos="fade-left" data-aos-delay="500">
                                    <a data-bs-toggle="collapse" data-bs-target="#accordion-list-4"
                                       class="collapsed"><span>04</span> Where can I go for help if I don't understand
                                        anything? <i class="bi bi-chevron-down icon-show"></i><i
                                            class="bi bi-chevron-up icon-close"></i></a>
                                    <div id="accordion-list-4" class="collapse" data-bs-parent=".accordion-list">
                                        <p>Go to <a href="#">Telegram</a></p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('page-javascript')

@endpush


