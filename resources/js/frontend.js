// Định dạng ngày tháng
function formatDateTime(dateTimeString) {
    const options = {
        year: 'numeric',
        month: 'long',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    };
    const formatedDateTime = new Intl.DateTimeFormat('vi-VN', options).format(new Date(dateTimeString));
    return formatedDateTime.replace('tháng ', '');
}

function scrollToBottom() {
    mainChatInbox.scrollTop(mainChatInbox.prop('scrollHeight'));
}

// Lắng nghe sự kiện gửi tin nhắn
window.Echo.private('message.' + USER.id).listen(
    "MessageEvent", (e) => {

        console.log(e);
        let mainChatBox = $('.wsus__chat_area_body');

        // Kiểm tra xem người gửi có phải là người dùng hiện tại không
        if (mainChatBox.attr('data-inbox') == e.sender_id) {
            var message = `
            <div class="wsus__chat_single">
                <div class="wsus__chat_single_img">
                    <img src="${e.sender_image}" alt="user" class="img-fluid">
                </div>
                <div class="wsus__chat_single_text">
                    <p>${e.message}</p>
                    <span>${formatDateTime(e.date_time)}</span>
                </div>
            </div>
            `
        }

        mainChatBox.append(message);
        scrollToBottom()

        // Thông báo người dùng tin nhắn mới
        $('.chat-user-profile').each(function(){
            let profileUserId = $(this).data('id');
            if(profileUserId == e.sender_id){
                $(this).find('.wsus_chat_list_img').addClass('msg-notification');
            }
        })
    }
) 
