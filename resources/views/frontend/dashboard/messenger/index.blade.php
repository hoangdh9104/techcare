@extends('frontend.dashboard.layouts.master')
@section('content')
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('frontend.dashboard.layouts.sidebar')
            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <div class="dashboard_content mt-2 mt-md-0">
                        <h3><i class="far fa-star" aria-hidden="true"></i> Message</h3>
                        <div class="wsus__dashboard_review">
                            <div class="row">
                                <div class="col-xl-4 col-md-5">
                                    <div class="wsus__chatlist d-flex align-items-start">
                                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist"
                                            aria-orientation="vertical">
                                            <h2>Seller List</h2>
                                            <div class="wsus__chatlist_body">
                                                @foreach ($chatUsers as $chatUser)
                                                    <button class="nav-link chat-user-profile" data-bs-toggle="pill"
                                                        data-id="{{ $chatUser->receiverProfile->id }}"
                                                        data-bs-target="#v-pills-home" type="button" role="tab"
                                                        aria-controls="v-pills-home" aria-selected="true">
                                                        <div class="wsus_chat_list_img">
                                                            <img src="{{ asset($chatUser->receiverProfile->image) }}"
                                                                alt="user" class="img-fluid">
                                                            <span class="pending d-none" id="pending-6">0</span>
                                                        </div>
                                                        <div class="wsus_chat_list_text">
                                                            <h4>{{ $chatUser->receiverProfile->name }}</h4>
                                                        </div>
                                                    </button>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-8 col-md-7">
                                    <div class="wsus__chat_main_area">
                                        <div class="tab-content" id="v-pills-tabContent">
                                            <div class="tab-pane fade show" id="v-pills-home" role="tabpanel"
                                                aria-labelledby="v-pills-home-tab">
                                                <div id="chat_box">
                                                    <div class="wsus__chat_area" style="position: relative; height: 95paymentvh;">
                                                        <div class="wsus__chat_area_header">
                                                            <h2 id="chat-inbox-title">Chat with Daniel Paul</h2>
                                                        </div>
                                                        <div class="wsus__chat_area_body">

                                                            {{-- <div class="wsus__chat_single single_chat_2">
                                                                <div class="wsus__chat_single_img">
                                                                    <img src="http://127.0.0.1:8000/uploads/custom-images/john-doe-2022-08-15-01-14-20-3892.png"
                                                                        alt="user" class="img-fluid">
                                                                </div>
                                                                <div class="wsus__chat_single_text">
                                                                    <p>Hello Paul</p>
                                                                    <span>15 August, 2022, 12:57 PM</span>
                                                                </div>
                                                            </div> --}}

                                                            {{-- <div class="wsus__chat_single">
                                                                <div class="wsus__chat_single_img">
                                                                    <img src="http://127.0.0.1:8000/uploads/custom-images/daniel-paul-2022-08-15-01-16-48-4881.png"
                                                                        alt="user" class="img-fluid">
                                                                </div>
                                                                <div class="wsus__chat_single_text">
                                                                    <p>Hi Joe, Thanks for your contact</p>
                                                                    <span>15 August, 2022, 12:58 PM</span>
                                                                </div>
                                                            </div> --}}

                                                        </div>
                                                        <div class="wsus__chat_area_footer"
                                                            style="margin-top: 50px;
                                                         position: absolute;
                                                        width: 100%;
                                                        bottom: 0;">
                                                            <form id="message-form">
                                                                @csrf
                                                                <input type="text" placeholder="Type Message"
                                                                    class="message-box" autocomplete="off" name="message">
                                                                <input type="hidden" name="receiver_id" id="receiver_id"
                                                                    value="">
                                                                <button type="submit"><i class="fas fa-paper-plane send-button"
                                                                        aria-hidden="true"></i></button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const mainChatInbox = $('.wsus__chat_area_body');


        // Định dạng ngày tháng
        function formatDateTime(dateTimeString) {
            const options = {
                year: 'numeric',
                month: 'short',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
            }
            const formatedDateTime = new Intl.DateTimeFormat('en-US', options).format(new Date(dateTimeString));
            return formatedDateTime;
        }

        function scrollToBottom() {
            mainChatInbox.scrollTop(mainChatInbox.prop('scrollHeight'));
        }

        $(document).ready(function() {
            $('.chat-user-profile').on('click', function() {

                let receiverId = $(this).data('id');
                let chatUserName = $(this).find('h4').text(); // Lấy username của người chat cùng

                $('#receiver_id').val(receiverId);
                $.ajax({
                    method: "GET",
                    url: "{{ route('user.get-messages') }}",
                    data: {
                        receiver_id: receiverId,
                    },
                    beforeSend: function() {
                        mainChatInbox.html('');
                        $('#chat-inbox-title').text(`Chat with ${chatUserName}`);
                    },
                    success: function(response) {
                        $.each(response, function(index, value) {
                            let message = `
                            <div class="wsus__chat_single single_chat_2">
                                <div class="wsus__chat_single_img">
                                    <img src="${USER.image}"
                                                                        alt="user" class="img-fluid">
                                </div>
                                <div class="wsus__chat_single_text">
                                    <p>${value.message}</p>
                                    <span>${formatDateTime(value.created_at)}</span>
                                </div>
                            </div>
                            `
                            mainChatInbox.append(message);
                        })

                        // Tự động cuộn tin nhắn xuống mới nhất
                        scrollToBottom();
                    },
                    error: function(xhr, status, error) {

                    },
                    complete: function() {

                    }
                });
            })


            $('#message-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let messageData = $('.message-box').val();

                var formSubmitting = false;
                if(formSubmitting || messageData === "") {
                    return;
                }

                // set message in inbox

                let message = `<div class="wsus__chat_single single_chat_2">
                                <div class="wsus__chat_single_img">
                                    <img src="${USER.image}"
                                                                        alt="user" class="img-fluid">
                                </div>
                                <div class="wsus__chat_single_text">
                                    <p>${messageData}</p>
                                    <span></span>
                                </div>
                            </div>`;

                mainChatInbox.append(message);
                scrollToBottom();

                $.ajax({
                    method: 'POST',
                    url: '{{ route('user.send-message') }}',
                    data: formData,
                    beforeSend: function() {
                        $('.send-button').prop('disabled', true);
                        formSubmitting = true;
                    },
                    success: function(response) {
                        $('.message-box').val('');
                        
                    },
                    error: function(xhr, status, error) {
                        toastr.error(xhr.responseJSON.message);
                        $('.send-button').prop('disabled', false);
                        formSubmitting = false;
                    },
                    complete: function() {                     
                        $('.send-button').prop('disabled', false);
                        formSubmitting = false;
                    }
                })
            })
        })
    </script>
@endpush
