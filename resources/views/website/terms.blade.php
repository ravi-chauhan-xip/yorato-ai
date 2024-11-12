@extends('website.layouts.master')

@section('title',':: Terms & Conditions | '.settings('company_name').' ::')

@section('content')
    <section class="section_breadcrumb blue_light_bg" data-z-index="1" data-parallax="scroll">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="banner_text text-center">
                        <h1 class="animation" data-animation="fadeInUp" data-animation-delay="1.1s">Terms &
                            Conditions</h1>
                        <ul class="breadcrumb bg-transparent justify-content-center animation m-0 p-0"
                            data-animation="fadeInUp" data-animation-delay="1.3s">
                            <li><a href="{{ route('website.home') }}">Home</a></li>
                            <li><span>Terms & Conditions</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="wrapper bg-light wrapper-border">
        <div class="container py-6 py-md-10">
            <div class="row gx-lg-8 gx-xl-12 gy-10 align-items-center mb-5">
                <div class="col-lg-12">
                    @if(settings('terms'))
                        {!! settings('terms') !!}
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection


