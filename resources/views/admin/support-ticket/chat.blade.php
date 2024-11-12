@extends('admin.layouts.master')

@section('title')
    Support Ticket
@endsection

@section('content')
    @include('admin.breadcrumbs', [
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
                                @if($supportTickets)
                                    @foreach($supportTickets as $ticket)
                                        <li class="chat-message {{ $ticket->byAdmin() == true ? 'chat-message-right' : '' }}">
                                            <div class="d-flex overflow-hidden">
                                                <div class="chat-message-wrapper flex-grow-1">
                                                    <div class="chat-message-text">
                                                        @if($ticket->byAdmin())
                                                            <small
                                                                class="text-primary d-flex justify-content-end">{{ settings('company_name') }}</small>
                                                        @else
                                                            <small class="text-primary ">
                                                                {{ $ticket->member->user->name }}
                                                            </small>
                                                        @endif
                                                        @if($ticket->byAdmin())
                                                            <p class="mb-0 d-flex justify-content-end">
                                                                {{ $ticket->body }}
                                                            </p>
                                                            @if($ticket->getFirstMediaUrl(\App\Models\SupportTicketMessage::MC_IMAGE))
                                                                <div class="d-flex my-3 justify-content-center">
                                                                    <a href="{{ $ticket->getFirstMediaUrl(\App\Models\SupportTicketMessage::MC_IMAGE) }}"
                                                                       class="image-popup">
                                                                        <img
                                                                            src="{{ $ticket->getFirstMediaUrl(\App\Models\SupportTicketMessage::MC_IMAGE) }}"
                                                                            class="img-fluid avatar-md">
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <p class="mb-0">
                                                                {{ $ticket->body }}
                                                            </p>
                                                            @if($ticket->getFirstMediaUrl(\App\Models\SupportTicketMessage::MC_IMAGE))
                                                                <div class="d-flex my-3 justify-content-center">
                                                                    <a href="{{ $ticket->getFirstMediaUrl(\App\Models\SupportTicketMessage::MC_IMAGE) }}"
                                                                       class="image-popup">
                                                                        <img
                                                                            src="{{ $ticket->getFirstMediaUrl(\App\Models\SupportTicketMessage::MC_IMAGE) }}"
                                                                            class="img-fluid avatar-md">
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div
                                                        class="text-muted {{ $ticket->byAdmin() == true ? 'text-end' : '' }}">
                                                        <small>{{ $ticket->created_at->format('d,M h:i a') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <!-- Chat message form -->
                        <div class="chat-history-footer mb-4">
                            @if($ticket->supportTicket->status==\App\Models\SupportTicket::STATUS_OPEN)
                                <form method="post" action="{{ route('admin.support-ticket.send') }}"
                                      id="supportTicketForm" class="filePondForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-lg-6 mb-1">
                                            <input class="form-control message-input me-3 shadow-none" name="message"
                                                   placeholder="Type your message here">
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <input type="hidden" name="buttonName" value="" id="buttonName">
                                        </div>
                                        <div class="col-lg-3 text-lg-center">
                                            <input type="file" class="filePondInput" name="image" accept="image/*">
                                        </div>
                                        <div class="col-lg-3 text-lg-end text-center">
                                            <div class="d-flex">
                                                <button type="submit" class="btn btn-primary d-flex send-msg-btn"
                                                        name="send" id="send"
                                                        value="1">
                                                    <span class="align-middle">Send</span>
                                                </button>
                                                <button type="submit" name="close_ticket" value="2" id="close_ticket"
                                                        class="btn btn-danger waves-effect waves-light ms-2"
                                                        form="supportTicketForm">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            @foreach($errors->get('message') as $error)
                                                <span class="text-danger">{{ $error }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="chat-app-form text-danger text-center">
                                    Ticket closed
                                </div>
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
@endpush

@include('admin.layouts.filepond')

@push('page-javascript')
    <script src="{{ asset('assets/js/app-chat.js') }}"></script>
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
