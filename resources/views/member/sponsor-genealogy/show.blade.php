@extends('member.layouts.master')

@section('title')Sponsor Genealogy Tree @endsection

@section('content')
    @include('member.breadcrumbs', [
    'crumbTitle' => function (){
          return 'Sponsor Genealogy Tree';
        },
          'crumbs' => [
              'Sponsor Genealogy Tree'
          ]
     ])
    <form>
        <div class="row">
            <div class="col-12">
                <div class="row mb-4 d-flex justify-content-center">
                    <div class="col-sm-4 col-12">
                        <div class="form-group mb-2">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="code" class="form-control" placeholder="Search By Wallet Address" required >
                                    <label for="code">Wallet Address</label>
                                </div>
                                <span class="input-group-text" >
                                    <button type="button" class="btn btn-sm btn-primary" onclick="goToMember()">Search</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5 d-flex justify-content-center text-center tree">
                    <div class="col">
                        <img src="{{ asset('images/free-id.svg') }}" class="rounded-circle avatar-md" alt="">
                        <p>Free</p>
                    </div>
{{--                    <div class="col">--}}
{{--                        <img src="{{ asset('images/paid.svg') }}" class="rounded-circle avatar-md" alt="">--}}
{{--                        <p>Paid</p>--}}
{{--                    </div>--}}
                    <div class="col">
                        <img src="{{ asset('images/active.svg') }}" class="rounded-circle avatar-md" alt="">
                        <p>Active</p>
                    </div>
{{--                    <div class="col">--}}
{{--                        <img src="{{ asset('images/block.svg') }}" class="rounded-circle avatar-md" alt="">--}}
{{--                        <p>Block</p>--}}
{{--                    </div>--}}
                </div>
{{--                <div class="row miniReports">--}}
{{--                    <div class="col-md-3 col-6">--}}
{{--                        <h5 class="text-blue font-weight-600">TOTAL DIRECT  : {{ $member->sponsored_count }}</h5>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="row miniReports">--}}
{{--                    <div class="col-md-3 col-6">--}}
{{--                        <h5 class="text-blue font-weight-600">TOTAL TEAM  : {{ App\Models\Member::where('sponsor_path', 'like', "{$member->sponsor_path}%")->count() }}</h5>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <section class="management-hierarchy mb-5">
                    <div class="hv-container mb-5">
                        <div class="hv-wrapper">
                            @include('member.sponsor-genealogy.member', ['member' => $member, 'level' => 0])
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </form>
@endsection
@push('page-css')
    <style>
        @media (max-width: 767.98px) {
            .hv-wrapper {
                display: flex;
            }

            .hv-wrapper .hv-item .hv-item-children {
                justify-content: flex-start !important;
            }
        }

        .hv-wrapper {
            display: block;
        }
        .hv-wrapper .hv-item .hv-item-children {
            justify-content: flex-start !important;
        }
    </style>
@endpush

@push('page-javascript')
    <script>
        function goToMember() {
            var trackingCode = $('#code').val();
            if (trackingCode.length) {
                window.location = '{{ route('user.sponsor-genealogy.show') }}/' + trackingCode;
            }
        }

        $('form').submit(function (e) {
            e.preventDefault();
            goToMember();
        });
    </script>
    <script>
        !function(o,e,p){"use strict";p('[data-toggle="popover"]').popover(),p("#show-popover").popover({title:"Popover Show Event",content:"Bonbon chocolate cake. Pudding halvah pie apple pie topping marzipan pastry marzipan cupcake.",trigger:"click",placement:"right"}).on("show.bs.popover",(function(){alert("Show event fired.")})),p("#shown-popover").popover({title:"Popover Shown Event",content:"Bonbon chocolate cake. Pudding halvah pie apple pie topping marzipan pastry marzipan cupcake.",trigger:"click",placement:"bottom"}).on("shown.bs.popover",(function(){alert("Shown event fired.")})),p("#hide-popover").popover({title:"Popover Hide Event",content:"Bonbon chocolate cake. Pudding halvah pie apple pie topping marzipan pastry marzipan cupcake.",trigger:"click",placement:"bottom"}).on("hide.bs.popover",(function(){alert("Hide event fired.")})),p("#hidden-popover").popover({title:"Popover Hidden Event",content:"Bonbon chocolate cake. Pudding halvah pie apple pie topping marzipan pastry marzipan cupcake.",trigger:"click",placement:"left"}).on("hidden.bs.popover",(function(){alert("Hidden event fired.")})),p("#show-method").on("click",(function(){p(this).popover("show")})),p("#hide-method").on("mouseenter",(function(){p(this).popover("show")})),p("#hide-method").on("click",(function(){p(this).popover("hide")})),p("#toggle-method").on("click",(function(){p(this).popover("toggle")})),p("#dispose").on("click",(function(){p("#dispose-method").popover("dispose")})),p(".manual").on("click",(function(){p(this).popover("show")})),p(".manual").on("mouseout",(function(){p(this).popover("hide")})),p("[data-popup=popover-color]").popover({template:'<div class="popover"><div class="bg-teal"><div class="popover-arrow"></div><div class="popover-inner"></div></div></div>'}),p("[data-popup=popover-border]").popover({template:'<div class="popover"><div class="border-orange"><div class="popover-arrow"></div><div class="popover-inner"></div></div></div>'})}(window,document,jQuery);
    </script>
@endpush
