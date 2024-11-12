@extends('member.layouts.master')

@section('title')
    Support Ticket
@endsection

@section('content')
    @include('member.breadcrumbs', [
    'crumbTitle' => function (){
          return 'Support Ticket';
        },
         'crumbs' => [
             'Support Ticket'
         ]
    ])
    <div class="row">
        <div class="col-lg-8">
            <div class="app-chat card overflow-hidden h-auto">
                <div class="app-chat-history h-auto">
                    <div class="chat-history-wrapper">
                        <div class="chat-history-header border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex overflow-hidden align-items-center">
                                    <div class="chat-contact-info flex-grow-1 ms-3">
                                        <h6 class="m-0">Chat</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-history-body">
                            <ul class="list-unstyled chat-history">
                                @foreach($supportTickets->message as $ticket)
                                    <li class="chat-message {{ $ticket->byAdmin() == true ? '' : 'chat-message-right' }}">
                                        <div class="d-flex overflow-hidden">
                                            <div class="chat-message-wrapper flex-grow-1">
                                                <div class="chat-message-text">
                                                    @if($ticket->byAdmin())
                                                        <small
                                                            class="text-primary">{{ settings('company_name') }}</small>
                                                    @else
                                                        <small class="text-primary d-flex justify-content-end">
                                                            {{ $ticket->member->user->name }}
                                                        </small>
                                                    @endif
                                                    @if($ticket->byAdmin())
                                                        <p class="mb-0 ">
                                                        {{ $ticket->body }}
                                                        <div class="d-flex my-3 justify-content-center">
                                                            @if($ticket->getFirstMediaUrl(\App\Models\SupportTicketMessage::MC_IMAGE))
                                                                <a href="{{ $ticket->getFirstMediaUrl(\App\Models\SupportTicketMessage::MC_IMAGE) }}"
                                                                   class="image-popup">
                                                                    <img
                                                                        src="{{ $ticket->getFirstMediaUrl(\App\Models\SupportTicketMessage::MC_IMAGE) }}"
                                                                        class="img-fluid avatar-md">
                                                                </a>
                                                            @endif
                                                        </div>
                                                        </p>
                                                    @else
                                                        <p class="mb-0 d-flex justify-content-end">
                                                        {{ $ticket->body }}
                                                        <div class="d-flex my-3 justify-content-center">
                                                            @if($ticket->getFirstMediaUrl(\App\Models\SupportTicketMessage::MC_IMAGE))
                                                                <a href="{{ $ticket->getFirstMediaUrl(\App\Models\SupportTicketMessage::MC_IMAGE) }}"
                                                                   class="image-popup">
                                                                    <img
                                                                        src="{{ $ticket->getFirstMediaUrl(\App\Models\SupportTicketMessage::MC_IMAGE) }}"
                                                                        class="img-fluid avatar-md">
                                                                </a>
                                                            @endif
                                                        </div>
                                                        </p>
                                                    @endif
                                                </div>
                                                <div
                                                    class="text-muted {{ $ticket->byAdmin() == true ? '' : 'text-end' }}">
                                                    <small>{{ $ticket->created_at->format('d,M h:i a') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Chat message form -->
                        <div class="chat-history-footer mb-4">
                            @if($supportTickets->status == \App\Models\SupportTicket::STATUS_CLOSE)
                                <div class="chat-app-form d-flex text-danger justify-content-center">
                                    Your last ticket closed by admin
                                </div>
                            @else
                                <form method="post" onsubmit="send.disabled = true; return true;"
                                      action="{{ route('user.support.ticketMessage',[$supportTickets]) }}"
                                      class="filePondForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row d-flex justify-content-between align-items-start">
                                        <div class="col-lg-6 mb-1">
                                            <input class="form-control message-input me-3 shadow-none" name="message"
                                                   placeholder="Type your message here">
                                            @foreach($errors->get('message') as $error)
                                                <div class="text-danger">{{ $error }}</div>
                                            @endforeach
                                        </div>
                                        <div class="col-lg-4 text-lg-center">
                                            <input type="file" class="filePondInput mt-2" name="image" accept="image/*">
                                        </div>
                                        <div class="col-lg-2 message-actions d-flex align-items-center">
                                            <button class="btn btn-primary d-flex send-msg-btn ms-4" name="send">
                                                <span class="align-middle">Send</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="app-overlay"></div>
            </div>
        </div>
    </div>
@endsection

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/css/app-chat.css') }}">
    <style>
        /*@media (max-width: 768px) {*/
        /*.app-chat {*/
        /*    position: relative;*/
        /*    height: calc(100vh - 4rem) !important;*/
        /*}*/

        /*}*/
    </style>
@endpush

@include('admin.layouts.filepond')

@push('page-javascript')
    <script>
        window.FILE_POND_CONFIG = {
            imagePreviewMaxHeight: 50
        }
    </script>
    <script src="{{ asset('js/filepond.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(".image-popup").magnificPopup({
                type: "image",
                closeOnContentClick: !1,
                closeBtnInside: !1,
                mainClass: "mfp-with-zoom mfp-img-mobile",
                image: {
                    verticalFit: !0, titleSrc: function (e) {
                        return e.el.attr("title")
                    }
                },
                gallery: {enabled: !0},
                zoom: {
                    enabled: !0, duration: 300, opener: function (e) {
                        return e.find("img")
                    }
                }
            })
        });

        $('#close_ticket').click(function () {
            $('#buttonName').val(2)
        })

        $('#send').click(function () {
            $('#buttonName').val(1)
        })
    </script>
@endpush
