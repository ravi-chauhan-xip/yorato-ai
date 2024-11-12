@extends('website.layouts.master')

@section('title',':: Privacy Policy | '.settings('company_name').' ::')

@section('content')
    <section class="section_breadcrumb blue_light_bg" data-z-index="1" data-parallax="scroll">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="banner_text text-center">
                        <h1 class="animation" data-animation="fadeInUp" data-animation-delay="1.1s">Privacy Policy</h1>
                        <ul class="breadcrumb bg-transparent justify-content-center animation m-0 p-0" data-animation="fadeInUp" data-animation-delay="1.3s">
                            <li><a href="{{ route('website.home') }}">Home</a> </li>
                            <li><span>Privacy Policy</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="wrapper bg-white wrapper-border">
        <div class="container py-6 py-md-10">
            <div class="row gx-lg-8 gx-xl-12 gy-10 align-items-center mb-5">
                <div class="col-lg-12">
                    @if(settings('privacy_policy'))
                        <p>  {!! settings('privacy_policy') !!} </p>
                    @else
                        <p>
                            {{ settings('company_name') }} respects your privacy. This Privacy Policy provides
                            succinctly the manner
                            your data is collected and used by {{ settings('company_name') }}. You are advised to
                            please read the
                            Privacy Policy carefully. By accessing the services provided
                            by {{ settings('company_name') }} you agree to
                            the collection and use of your data by {{ settings('company_name') }} in the manner
                            provided in this Privacy
                            Policy.
                        </p>
                        <p><b>We want you to</b></p>
                        <ul>
                            <li>
                                Feel comfortable using our web sites
                            </li>
                            <li>
                                Feel secure submitting information to us
                            </li>
                            <li>
                                Contact us with your questions or concerns about privacy on this site
                            </li>
                            <li>
                                Know that by using our sites you are consenting to the collection of certain data
                            </li>
                        </ul>
                        <p>
                            <b>We may collect the following personally identifiable information about you:</b>
                        </p>
                        <ul>
                            <li>
                                Name including first and last name
                            </li>
                            <li>
                                Email Addresses
                            </li>
                            <li>
                                Mobile phone number and contact details
                            </li>
                            <li>
                                ZIP/Postal code
                            </li>
                            <li>
                                Demographic profile (like your age, gender, occupation, education, address and
                                durables owned);
                            </li>
                            <li>
                                Preferences and interests (such as news, sports, travel and so on);
                            </li>
                            <li>
                                Financial information (like account or card numbers); and
                            </li>
                            <li>
                                Opinions of features on our websites.
                            </li>
                        </ul>
                        <p></p>
                        <p>
                            <b>We may also collect the following information:</b>
                        </p>
                        <ul>
                            <li>
                                About the pages you visit/access
                            </li>
                            <li>
                                The links you click on our site
                            </li>
                            <li>
                                The number of times you access the page
                            </li>
                        </ul>
                        <p></p>
                        <p>
                            You can terminate your account at any time. However, your information may remain stored
                            in archive on our servers even after the deletion or the termination of your
                            account.</p>
                        <p><b>USE OF THE INFORMATION</b></p>
                        <p>
                            <b>We use your personal information to:</b>
                        </p>
                        <ul>
                            <li>
                                Help us provide personalized features
                            </li>
                            <li>
                                Tailor our sites to your interest
                            </li>
                            <li>
                                To get in touch with you when necessary
                            </li>
                            <li>
                                To provide the services requested by you
                            </li>
                            <li>
                                To preserve social history as governed by existing law or policy
                            </li>
                        </ul>
                        <p></p>
                        <p>
                            <b>We use contact information internally to:</b>
                        </p>
                        <ul>
                            <li>
                                Direct our efforts for product improvement
                            </li>
                            <li>
                                Contact you as a survey respondent
                            </li>
                            <li>
                                Notify you if you win any contest; and
                            </li>
                            <li>
                                Send you promotional materials from our contest sponsors or advertisers
                            </li>
                        </ul>
                        <p></p>
                        <p>
                            <b>Generally, we use anonymous traffic information to:</b>
                        </p>
                        <ul>
                            <li>
                                Remind us of who you are in order to deliver to you a better and more personalized
                                service from both an advertising and an editorial perspective;
                            </li>
                            <li>
                                Recognize your access privileges to our web sites
                            </li>
                            <li>
                                Track your entries in some of our promotions, sweepstakes and contests to indicate a
                                player's progress through the promotion and to track entries, submissions, and
                                status in prize drawings
                            </li>
                            <li>
                                Make sure that you don't see the same ad repeatedly
                            </li>
                            <li>
                                Help diagnose problems with our server
                            </li>
                            <li>
                                Administer our web sites
                            </li>
                            <li>
                                Track your session so that we can understand better how people use our sites
                            </li>
                        </ul>
                        <p></p>
                        <p>
                            <b>WITHWHOM WILL YOUR INFORMATION BE SHARED?</b>
                        </p>
                        <p>
                            We will not use your shared financial information for any purpose other than to complete
                            a transaction with you. We do not rent, sell or share your personal information and we
                            will not disclose any of your personally identifiable information to third parties
                            unless:</p>
                        <ul>
                            <li>
                                We have your permission
                            </li>
                            <li>
                                To provide products or services you've requested
                            </li>
                            <li>
                                To help investigate, prevent or take action regarding unlawful and illegal
                                activities, suspected fraud, potential threat to the safety or security of any
                                person, violations of {{ settings('company_name') }} terms of use or to defend against
                                legal claims;
                            </li>
                            <li>
                                Special circumstances such as compliance with subpoenas, court orders,
                                requests/order from legal authorities or law enforcement agencies requiring such
                                disclosure.
                            </li>
                            <li>
                                We share your information with advertisers on an aggregate basis only.
                            </li>
                        </ul>
                        <p></p>
                        <p>
                            <b>OUR COMMITMENT TO DATA SECURITY</b>
                        </p>
                        <p>
                            To prevent unauthorized access, maintain data accuracy, and ensure the correct use of
                            information, we have put in place appropriate physical, electronic, and managerial
                            procedures to safeguard and secure the information we collect online. Our internal
                            security and privacy policies are periodically reviewed and enhanced as necessary and
                            only authorized individuals have access to the information provided by our users.</p>
                        <p>
                            <b>CORRECTIONS TO YOUR INFORMATION</b>
                        </p>
                        <p>
                            You can access and update all your personally identifiable information that we collect
                            online by logging in to your account by providing user id and secure password. We use
                            this procedure to safeguard your information. You can correct factual errors in your
                            personally identifiable information, as we do take reasonable steps to verify your
                            identity before granting accessor making corrections.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection


