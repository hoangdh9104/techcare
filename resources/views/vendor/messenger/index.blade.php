@extends('vendor.layouts.master')
@section('content')
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('vendor.layouts.sidebar')
            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <div class="dashboard_content mt-2 mt-md-0">
                        <h3><i class="far fa-star" aria-hidden="true"></i> Tin nhắn</h3>
                        <div class="wsus__dashboard_review">
                            <div class="row">
                                <div class="col-xl-4 col-md-5">
                                    <div class="wsus__chatlist d-flex align-items-start">
                                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist"
                                            aria-orientation="vertical">
                                            <h2>Danh sách người bán</h2>
                                            <div class="wsus__chatlist_body">
                                                <button class="nav-link seller" id="seller-list-6" data-bs-toggle="pill"
                                                    data-bs-target="#v-pills-home" type="button" role="tab"
                                                    aria-controls="v-pills-home" aria-selected="true">
                                                    <div class="wsus_chat_list_img">
                                                        <img src="http://127.0.0.1:8000/uploads/custom-images/robert-james-2022-08-15-01-18-57-7752.png"
                                                            alt="user" class="img-fluid">
                                                        <span class="pending d-none" id="pending-6">0</span>
                                                    </div>
                                                    <div class="wsus_chat_list_text">
                                                        <h4>Robert James</h4>
                                                    </div>
                                                </button>

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
                                                    <div class="wsus__chat_area">
                                                        <div class="wsus__chat_area_header">
                                                            <h2>Nhắn tin cùng Daniel Paul</h2>
                                                        </div>
                                                        <div class="wsus__chat_area_body">
                                                            <div class="wsus__chat_single">
                                                                <div class="wsus__chat_single_img">
                                                                    <img src="http://127.0.0.1:8000/uploads/custom-images/daniel-paul-2022-08-15-01-16-48-4881.png"
                                                                        alt="user" class="img-fluid">
                                                                </div>
                                                                <div class="wsus__chat_single_text">
                                                                    <p>Chào mừng đến với Shop name 2!

                                                                        Lorem Ipsum chỉ đơn giản là văn bản giả của ngành in
                                                                        và sắp chữ. Lorem Ipsum đã là
                                                                        văn bản giả chuẩn của ngành kể từ
                                                                        những năm 1500, khi một thợ in vô danh lấy một galley
                                                                        chữ và xáo trộn nó để tạo ra một mẫu chữ
                                                                        sách.</p>
                                                                    <span>20 tháng 3 năm 2025, 12:56 chiều</span>
                                                                </div>
                                                            </div>
                                                            <div class="wsus__chat_single single_chat_2">
                                                                <div class="wsus__chat_single_img">
                                                                    <img src="http://127.0.0.1:8000/uploads/custom-images/john-doe-2022-08-15-01-14-20-3892.png"
                                                                        alt="user" class="img-fluid">
                                                                </div>
                                                                <div class="wsus__chat_single_text">
                                                                    <p>Chào Paul</p>
                                                                    <span>20 tháng 3 năm 2025, 12:57 chiều</span>
                                                                </div>
                                                            </div>
                                                            <div class="wsus__chat_single single_chat_2">
                                                                <div class="wsus__chat_single_img">
                                                                    <img src="http://127.0.0.1:8000/uploads/custom-images/john-doe-2022-08-15-01-14-20-3892.png"
                                                                        alt="user" class="img-fluid">
                                                                </div>
                                                                <div class="wsus__chat_single_text">
                                                                    <p>Tôi có 1 số thắc mắc</p>
                                                                    <span>20 tháng 3 năm 2025, 12:57 chiều</span>
                                                                </div>
                                                            </div>
                                                            <div class="wsus__chat_single">
                                                                <div class="wsus__chat_single_img">
                                                                    <img src="http://127.0.0.1:8000/uploads/custom-images/daniel-paul-2022-08-15-01-16-48-4881.png"
                                                                        alt="user" class="img-fluid">
                                                                </div>
                                                                <div class="wsus__chat_single_text">
                                                                    <p>Xin chào Joe, Cảm ơn bạn đã liên hệ</p>
                                                                    <span>20 tháng 3 năm 2025 12:58 chiều</span>
                                                                </div>
                                                            </div>
                                                            <div class="wsus__chat_single">
                                                                <div class="wsus__chat_single_img">
                                                                    <img src="http://127.0.0.1:8000/uploads/custom-images/daniel-paul-2022-08-15-01-16-48-4881.png"
                                                                        alt="user" class="img-fluid">
                                                                </div>
                                                                <div class="wsus__chat_single_text">
                                                                    <p>Xin vui lòng cho tôi biết bạn thắc mắc</p>
                                                                    <span>20 tháng 3 năm 2025, 12:58 chiều</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="wsus__chat_area_footer" style="margin-top: 50px;">
                                                            <form id="customerToSellerMsgForm">
                                                                <input type="text" placeholder="Type Message"
                                                                    id="seller_message" autocomplete="off">
                                                                <input type="hidden" name="seller_id" id="seller_id"
                                                                    value="5">
                                                                <button type="submit"><i class="fas fa-paper-plane"
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
