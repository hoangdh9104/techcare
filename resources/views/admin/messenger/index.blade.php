@extends('admin.layouts.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Messages</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Starter</a></div>
                <div class="breadcrumb-item">Messages</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-3">
                    <div class="card" style="height: 70vh;">
                        <div class="card-header">
                            <h4>Who's Online?</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border">
                                @foreach ($chatUsers as $chatUser)
                                    <li class="media chat-user-profile" data-id="{{ $chatUser->senderProfile->id }}">
                                        <img alt="image" style="height: 50px;
  object-fit: cover;" class="mr-3 rounded-circle" width="50"
                                            src="{{ asset($chatUser->senderProfile->image) }}">
                                        <div class="media-body">
                                            <div class="mt-0 mb-1 font-weight-bold">{{ $chatUser->senderProfile->name }}
                                            </div>
                                            {{-- <div class="text-success text-small font-600-bold"><i class="fas fa-circle"></i>
                                                Online</div> --}}
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card chat-box" id="mychatbox" style="height: 70vh;">
                        <div class="card-header">
                            <h4>Chat with Rizal</h4>
                        </div>
                        <div class="card-body chat-content">
                            {{-- <div class="chat-item chat-left" style=""><img src="../dist/img/avatar/avatar-1.png">
                                <div class="chat-details">
                                    <div class="chat-text">You wanna know?</div>
                                    <div class="chat-time">01:19</div>
                                </div>
                            </div> --}}
                            {{-- <div class="chat-item chat-right" style=""><img src="../dist/img/avatar/avatar-2.png">
                                <div class="chat-details">
                                    <div class="chat-text">Wat?</div>
                                    <div class="chat-time">01:19</div>
                                </div>
                            </div> --}}
                        </div>
                        <div class="card-footer chat-form">
                            <form id="message-form">
                                <input type="text" class="form-control message-box" placeholder="Type a message"
                                    name="message">
                                <input type="hidden" name="receiver_id" id="receiver_id" value="">
                                <button class="btn btn-primary">
                                    <i class="far fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        const mainChatInbox = $('.chat-content');

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
                let receiverImage = $(this).find('img').attr('src');


                $('#receiver_id').val(receiverId);
                $.ajax({
                    method: "GET",
                    url: "{{ route('admin.get-messages') }}",
                    data: {
                        receiver_id: receiverId,
                    },
                    beforeSend: function() {
                        mainChatInbox.html('');
                        // $('#chat-inbox-title').text(`Chat with ${chatUserName}`);
                    },
                    success: function(response) {
                        $.each(response, function(index, value) {

                            if (value.sender_id == USER.id) {
                                var message = `<div class="chat-item chat-right" style=""><img src="${USER.image}">
                                <div class="chat-details">
                                    <div class="chat-text">${value.message}</div>
                                    <div class="chat-time">${formatDateTime(value.created_at)}</div>
                                </div>
                            </div>
                                `
                            } else {
                                var message = `<div class="chat-item chat-left" style=""><img style="height: 50px;
                                                object-fit: cover;" src="${receiverImage}">
                                <div class="chat-details">
                                    <div class="chat-text">${value.message}</div>
                                    <div class="chat-time">${formatDateTime(value.created_at)}</div>
                                </div>
                            </div>
                                `
                            }


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
                if (formSubmitting || messageData === "") {
                    return;
                }

                // set message in inbox

                let message = `<div class="chat-item chat-right" style=""><img src="${USER.image}">
                                <div class="chat-details">
                                    <div class="chat-text">${messageData}</div>
                                    <div class="chat-time"></div>
                                </div>
                            </div>
                `

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
