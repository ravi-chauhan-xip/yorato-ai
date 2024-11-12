<nav class="navbar navbar-expand-lg">
    <div class="indicator-scroll"></div>
    <div class="container">
        <a class="navbar-brand" href="{{ route('website.home') }}">
            <img src="{{ settings()->getFileUrl('logo', asset(env('LOGO'))) }}" alt="{{ settings('company_name') }}">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav align-items-lg-center ms-auto me-lg-5">

                <li class="nav-item">
                    <a class="nav-link click-scroll" href="#section_1">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link click-scroll" href="#About">About us</a>
                </li>

{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link click-scroll" href="">Business Plan</a>--}}
{{--                </li>--}}
                <li class="nav-item">
                    <a class="nav-link click-scroll" href="#Faq">FAQ's</a>
                </li>
                <li>
                    <a href="{{ route('user.register.create') }}" class="btn custom-btn d-lg-none mx-3 ">Connect Wallet</a>
                </li>
            </ul>
            <a href="{{ route('user.register.create') }}" class="btn custom-btn d-lg-block d-none mx-2">Connect Wallet</a>
        </div>
    </div>
</nav>
