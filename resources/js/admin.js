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

window.Echo.private('message.' + USER.id).listen(
    "MessageEvent", (e) => {

        console.log(e);
        let mainChatBox = $('.chat-content');
        // Kiểm tra xem người gửi có phải là người dùng hiện tại không
        if (mainChatBox.attr('data-inbox') == e.sender_id) {
            var message = `<div class="chat-item chat-left" style=""><img style="height: 50px;
                        object-fit: cover;" src="${e.sender_image}">
                            <div class="chat-details">
                            <div class="chat-text">${e.message}</div>
                            <div class="chat-time">${formatDateTime(e.date_time)}</div>
                            </div>
                            </div>
                            `;
        }
        mainChatBox.append(message);
        scrollToBottom()

        // Thông báo người dùng tin nhắn mới
        $('.chat-user-profile').each(function(){
            let profileUserId = $(this).data('id');
            if(profileUserId == e.sender_id){
                $(this).find('img').addClass('msg-notification');
            }
        })
    }
) 
