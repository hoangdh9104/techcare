@extends('admin.layouts.master')

@section('content')
    <style>
        /* Lớp CSS để highlight thẻ li */
        .highlight {
            background-color: #f8d7da;
            /* Màu nền hightlight */
            color: #721c24;
            /* Màu chữ khi chọn */
        }

        .card-custom {
            background-color: #fff6ed;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 140px;
            position: relative;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .card-custom2 {
            background-color: #bbecb1;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 140px;
            position: relative;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .card-custom3 {
            background-color: #a3d8f9;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 140px;
            position: relative;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .card-icon-circle {
            background-color: #f7941d;
            color: white;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-title {
            font-size: 14px;
            color: #888;
        }

        .card-value {
            font-size: 24px;
            font-weight: bold;
            color: #222;
        }

        .card-growth {
            font-size: 14px;
            color: #2ecc71;
            background-color: #e9fbe7;
            padding: 2px 6px;
            border-radius: 6px;
            font-weight: 600;
        }

        .card-menu {
            position: absolute;
            right: 12px;
            top: 12px;
            cursor: pointer;
        }
    </style>
    {{-- doanh thu --}}
    <section class="section">
        <div class="section-header">
            <h1>Thống kê doanh thu</h1>
        </div>
        <div class="row mb-3">
            <!-- Tổng doanh thu -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card-custom">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="card-icon-circle">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="card-menu dropdown">
                            <i class="fas fa-ellipsis-v dropdown-toggle" id="dropdownTime1" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false" style="cursor:pointer;"></i>
                            <div class="dropdown-menu dropdown-menu-right p-3" aria-labelledby="dropdownTime1"
                                style="min-width: 250px;">
                                <div class="dropdown-divider"></div>
                                <label for="selectTime" class="font-weight-semibold">Chọn ngày:</label>
                                <input type="date" id="selectTime" class="form-control mt-2" style="width: 100%;" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="card-title">Tổng doanh thu</div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <div class="card-value"> {{ $yearEarnings }}{{ $settings->currency_icon }}</div>


                        </div>
                    </div>
                </div>
            </div>

            <!-- Tổng đơn hàng -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card-custom2">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="card-icon-circle">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="card-menu">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.order.index') }}">
                            <div class="card-title">Tổng đơn hàng</div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <div class="card-value">{{ $totaltOrder }}</div>


                            </div>
                    </div>
                </div> </a>
            </div>

            <!-- Tổng sản phẩm bán được -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card-custom3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="card-icon-circle">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="card-menu">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="card-title">Tổng đơn hàng đã bán</div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <div class="card-value"> {{ $totalCompleteOrders }}</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-body">
            {{-- Biểu đồ doanh thu theo ngày --}}
            <div class="card">
                <div class="card-header">
                    <h4>Biểu đồ doanh thu theo ngày trong tháng</h4>
                    {{-- Form lọc theo tháng và năm --}}
                    <form id="filterForm3" class="form-inline">
                        <input type="month" name="month" class="form-control" id="monthPickerfilterForm3"
                            value="{{ date('Y-m') }}">
                        <button type="submit" class="btn btn-primary ml-2">Lọc</button>
                    </form>
                </div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="section-body">
            {{-- Biểu đồ doanh thu theo tháng --}}
            <div class="card">
                <div class="card-header">
                    <h4>Biểu đồ doanh thu theo tháng trong năm</h4>
                    {{-- Form lọc theo năm --}}
                    <form id="filterForm" class="form-inline">
                        <input type="number" class="form-control" id="year" name="year" value="{{ now()->year }}"
                            placeholder="Nhập năm">
                        <button type="submit" class="btn btn-primary ml-2">Lọc</button>
                    </form>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </section>

    {{-- end doanh thu --}}
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1>Thống kê đơn hàng theo trạng thái</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header card-stats-title">
                    <h4>Biểu đồ trạng thái đơn hàng theo tháng trong năm</h4>
                    <form id="filterForm2" class="form-inline">
                        <input type="month" name="month" class="form-control" id="monthPicker"
                            value="{{ date('Y-m') }}">
                        <button type="submit" class="btn btn-primary ml-2">Lọc</button>
                    </form>
                </div>
                <div class="card-body">
                    <canvas id="myChart3" width="670" height="335"
                        style="display: block; height: 268px; width: 536px;" class="chartjs-render-monitor"></canvas>
                    <div id="chartMessage" style="display: none;"></div>
                </div>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1>Thống kê người dùng và sản phẩm</h1>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Top 10 khách hàng mua nhiều nhất</h4>
                        <!-- Dropdown chọn thời gian -->
                        <div class="card-header-action dropdown">
                            <a href="#" data-toggle="dropdown" class="btn btn-danger dropdown-toggle"
                                id="timeFilterBtn">Chọn thời gian</a>
                            <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                <li><a href="#" class="dropdown-item" data-filter="1">Hôm nay</a></li>
                                <li><a href="#" class="dropdown-item" data-filter="2">Hôm qua</a></li>
                                <li><a href="#" class="dropdown-item" data-filter="3">Tuần này</a></li>
                                <li><a href="#" class="dropdown-item" data-filter="4">Tuần trước</a></li>
                                <li><a href="#" class="dropdown-item highlight" data-filter="5">Tháng này</a></li>
                                <li><a href="#" class="dropdown-item" data-filter="6">Tháng trước</a></li>
                                <li><a href="#" class="dropdown-item" data-filter="7">Năm nay</a></li>
                                <li><a href="#" class="dropdown-item" data-filter="8">Năm trước</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Thông báo nếu không có dữ liệu -->
                    <div id="noCustomerData" class="text-center text-muted mt-3" style="display: none;">
                        Không có dữ liệu khách hàng trong khoảng thời gian này.
                    </div>
                    <div class="card-body">
                        <canvas id="myChart4" height="390" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card gradient-bottom">
                    <div class="card-header">
                        <h4>Top 5 sản phẩm bán chạy</h4>

                    </div>

                    <div class="card-body" id="top-5-scroll" tabindex="2"
                        style="height: 315px; overflow: hidden; outline: none;">
                        <ul class="list-unstyled list-unstyled-border" id="top-selling-list">
                            {{-- dữ liệu động --}}
                        </ul>
                    </div>

                    <div class="card-footer pt-3 d-flex justify-content-center">
                        <div class="budget-price justify-content-center">
                            <div class="budget-price-square bg-primary" style="width: 20px;"></div>
                            <div class="budget-price-label">Giá đã bán</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        "use strict";

        // biểu đồ cột : xủ lý thống kê doanh thu theo tháng trong năm
        const ctx = document.getElementById("monthlyRevenueChart").getContext("2d");
        let chart; // Biến để giữ biểu đồ, cập nhật lại khi lọc
        function loadChart(year) {
            fetch(`{{ route('admin.revenue.chart') }}?year=${year}`)
                .then(response => {
                    if (!response.ok) throw new Error('Không lấy được dữ liệu');
                    return response.json();
                })
                .then(({
                    labels,
                    data
                }) => {
                    if (chart) chart.destroy(); // Xoá chart cũ nếu có
                    chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: `Doanh thu từ ${year}`,
                                data: data,
                                backgroundColor: '#6777ef',
                                borderColor: '#6777ef',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: value => value.toLocaleString('vi-VN') + ' ₫'
                                    },
                                    grid: {
                                        color: '#f2f2f2'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    if (error.response && error.response.error) {
                        // Hiển thị thông báo lỗi từ API
                        toastr.error(error.response.error);
                    } else {
                        // Xử lý trường hợp lỗi không có thông báo cụ thể từ API
                        console.log(error);

                        toastr.error('Có lỗi xảy ra, vui lòng thử lại.');
                    }
                });
        }
        // Tải mặc định theo năm hiện tại
        loadChart(document.getElementById("year").value);
        // Xử lý form lọc
        document.getElementById("filterForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const year = document.getElementById("year").value;
            loadChart(year);
        });

        // biểu đồ tròn: xử lý thống kê đơn hàng
        const ctx2 = document.getElementById("myChart3").getContext('2d');
        let myChart = new Chart(ctx2, {
            // type: 'doughnut',
            type: 'pie',
            data: {
                datasets: [{
                    data: [],
                    backgroundColor: [
                        '#191d21', '#63ed7a', '#ffa426', '#fc544b', '#6777ef',
                        '#47c363', '#e83e8c', '#343a40', '#20c997', '#6f42c1'
                    ],
                    label: 'Trạng thái đơn hàng'
                }],
                labels: []
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        function loadOrderStatistics(month, year) {
            fetch(`/admin/statistics/orders/data?month=${month}&year=${year}`)
                .then(response => response.json())
                .then(data => {
                    const statusLabels = {
                        'pending': 'Chờ xác nhận',
                        'processed_and_ready_to_ship': 'Đã xác nhận',
                        'dropped_off': 'Đang đóng gói',
                        'shipped': 'Đơn vị vận chuyển lấy hàng',
                        'delivered': 'Đã giao hàng',
                        'received': 'Hoàn thành',
                        'canceled': 'Đã hủy',
                    };

                    const labels = Object.keys(data).map(key => statusLabels[key] || key);
                    const values = Object.values(data);

                    const chartWrapper = document.getElementById('chartWrapper');
                    const chartCanvas = document.getElementById('myChart3');
                    const chartMessage = document.getElementById('chartMessage');

                    const isEmpty = !values || values.length === 0 || values.every(v => v === 0);

                    if (isEmpty) {
                        chartCanvas.style.display = "none";
                        chartMessage.style.display = "block";
                        chartMessage.innerHTML = `
                    <div class="text-center text-muted p-5">
                        <h5>Không có dữ liệu để hiển thị</h5>
                        <p>Vui lòng chọn tháng khác hoặc thử lại sau.</p>
                    </div>
                `;
                        return;
                    }

                    // Có dữ liệu, hiển thị lại canvas
                    chartMessage.style.display = "none";
                    chartCanvas.style.display = "block";

                    myChart.data.labels = labels;
                    myChart.data.datasets[0].data = values;
                    myChart.update();
                })
                .catch(error => {
                    const chartMessage = document.getElementById('chartMessage');
                    const chartCanvas = document.getElementById('myChart3');

                    chartCanvas.style.display = "none";
                    chartMessage.style.display = "block";
                    chartMessage.innerHTML = `
                <div class="text-center text-danger p-5">
                    <h5>Lỗi tải dữ liệu thống kê</h5>
                    <p>${error.message}</p>
                </div>
            `;
                    console.error("Lỗi khi tải thống kê đơn hàng:", error);
                });
        }
        // Tải dữ liệu khi trang vừa load
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const year = now.getFullYear();

            document.getElementById('monthPicker').value = `${year}-${month}`;
            loadOrderStatistics(month, year);
        });
        // Bắt sự kiện submit form lọc
        document.getElementById('filterForm2').addEventListener('submit', function(e) {
            e.preventDefault();

            const monthValue = document.getElementById('monthPicker').value;
            const [year, month] = monthValue.split('-');

            loadOrderStatistics(month, year);
        });
        // thống kê top 10 user mua hàng nhiều nhất
        // Lấy tất cả các phần tử dropdown-item
        var items = document.querySelectorAll('.dropdown-item');

        // Lặp qua từng phần tử
        items.forEach(function(item) {
            item.addEventListener('click', function() {
                // Xóa lớp highlight khỏi tất cả các mục
                items.forEach(function(innerItem) {
                    innerItem.classList.remove('highlight');
                });

                // Thêm lớp highlight vào mục đã chọn
                item.classList.add('highlight');
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            const filterItems = document.querySelectorAll('.dropdown-item[data-filter]');
            const dropdownBtn = document.getElementById('timeFilterBtn');

            filterItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Lấy filter và cập nhật button
                    const filterValue = this.getAttribute('data-filter');
                    const filterLabel = this.textContent.trim();
                    dropdownBtn.textContent = filterLabel;

                    // Highlight dropdown item
                    filterItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');

                    // Gọi hàm fetch dữ liệu và vẽ biểu đồ
                    loadTopCustomersChart(filterValue);
                });
            });

            // Tải dữ liệu mặc định ban đầu (tháng này)
            loadTopCustomersChart(5);
        });

        function loadTopCustomersChart(filterValue) {
            fetch(`/admin/statistics/top-customers?filter=${filterValue}`)
                .then(res => res.json())
                .then(data => {
                    const noDataDiv = document.getElementById('noCustomerData');

                    if (!data || data.length === 0) {
                        // Hiển thị thông báo HTML
                        noDataDiv.style.display = 'block';

                        // Xóa biểu đồ nếu đang hiển thị
                        if (window.topCustomerChart) {
                            window.topCustomerChart.destroy();
                            window.topCustomerChart = null;
                        }

                        return;
                    }

                    // Ẩn thông báo nếu có dữ liệu
                    noDataDiv.style.display = 'none';

                    const labels = data.map(customer => customer.name);
                    const values = data.map(customer => customer.total_products);

                    const ctx = document.getElementById("myChart4").getContext('2d');

                    if (window.topCustomerChart) {
                        window.topCustomerChart.destroy();
                    }

                    window.topCustomerChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Số lượng sản phẩm đã mua',
                                data: values,
                                fill: false,
                                borderColor: '#6777ef',
                                backgroundColor: '#6777ef',
                                tension: 0.4,
                                pointBackgroundColor: '#ffffff',
                                pointBorderColor: '#6777ef',
                                pointRadius: 5,
                                pointHoverRadius: 7,
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return `${context.dataset.label}: ${context.parsed.y} sản phẩm`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 100
                                    },
                                    grid: {
                                        color: '#f2f2f2',
                                        drawBorder: false
                                    }
                                },
                                x: {
                                    ticks: {
                                        display: true
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(err => {
                    console.error("Lỗi khi load top khách hàng:", err);
                });
        }
        // top 5 sản phẩm bán chạy
        fetch('/admin/top-selling?filter=5')
            .then(response => response.json())
            .then(data => {
                const listContainer = document.getElementById('top-selling-list');
                listContainer.innerHTML = '';

                // Lấy doanh thu cao nhất để tính phần trăm
                const maxRevenue = Math.max(...data.map(p => p.total_revenue));

                data.forEach(product => {
                    const revenuePercent = ((product.total_revenue / maxRevenue) * 100).toFixed(0);

                    const li = document.createElement('li');
                    li.className = 'media';

                    li.innerHTML = `
                <img class="mr-3 rounded" width="55" src="${product.image}" alt="product">
                <div class="media-body">
                    <div class="float-right">
                        <div class="font-weight-600 text-muted text-small">${product.total_sold} lượt bán</div>
                    </div>
                    <div class="media-title">${product.name}</div>
                    <div class="mt-1">
                        <div class="budget-price">
                            <div class="budget-price-square bg-primary" style="width: ${revenuePercent}%;"></div>
                            <div class="budget-price-label">${formatCurrency(product.total_revenue)}₫</div>
                        </div>
                    </div>
                </div>
            `;
                    listContainer.appendChild(li);
                });
            })
            .catch(error => {
                console.error('Lỗi lấy dữ liệu top selling:', error);
            });

        function formatCurrency(number) {
            return new Intl.NumberFormat('vi-VN').format(number);
        }
        // biểu đồ cột : xủ lý thống kê doanh thu theo ngày trong tháng
        const ctx3 = document.getElementById("dailyRevenueChart").getContext("2d");
        let chart3;

        function loadChart3(month, year) {
            fetch(`/admin/daily-revenue/data?month=${month}&year=${year}`)
                .then(response => {
                    if (!response.ok) throw new Error('Không lấy được dữ liệu');
                    return response.json();
                })
                .then(({
                    labels,
                    data
                }) => {
                    if (chart3) chart3.destroy(); // Xoá chart cũ nếu có

                    chart3 = new Chart(ctx3, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: `Doanh thu từ tháng ${month}`,
                                data: data,
                                backgroundColor: '#6777ef',
                                borderColor: '#6777ef',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: value => value.toLocaleString('vi-VN') + ' ₫'
                                    },
                                    grid: {
                                        color: '#f2f2f2'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    if (error.response && error.response.data.error) {
                        // Hiển thị thông báo lỗi từ API
                        toastr.error(error.response.data.error);
                    } else {
                        // Xử lý trường hợp lỗi không có thông báo cụ thể từ API
                        toastr.error('Có lỗi xảy ra, vui lòng thử lại.');
                    }
                });
        }
        // // Tải dữ liệu khi trang vừa load
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const year = now.getFullYear();

            document.getElementById('monthPickerfilterForm3').value = `${year}-${month}`;
            loadChart3(month, year);
        });
        // Xử lý form lọc
        document.getElementById("filterForm3").addEventListener("submit", function(e) {
            e.preventDefault();
            const monthValue = document.getElementById('monthPickerfilterForm3').value;
            const [year, month] = monthValue.split('-');
            console.log(monthValue, year, month);
            loadChart3(month, year);
        });
    </script>
@endpush
