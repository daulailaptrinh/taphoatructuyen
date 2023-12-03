@extends('Layout')
@section('title')
{{ trans('home.about') }}
@endsection
@section('content-layout')
<!-- Breadcrumb Start -->
<div class="breadcrumb-area mt-30">
    <div class="container">
        <div class="breadcrumb">
            <ul class="d-flex align-items-center">
                <li><a href="index.html">Home</a></li>
                <li class="active"><a href="about.html">About Us</a></li>
            </ul>
        </div>
    </div>
    <!-- Container End -->
</div>
<!-- Breadcrumb End -->
<!-- About Us Start Here -->
<div class="about-us pt-100 pt-sm-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="sidebar-img mb-all-30">
                    <img src="{{asset('source/assets/frontend/img/blog/10.jpg')}}" alt="single-blog-img">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-desc">
                    <h3 class="mb-10 about-title">Giới thiệu chung về cửa hàng tạp hóa trực tuyến VinChoice</h3>
                    <p class="mb-20">VinChoice là  cửa hàng tạp hóa trực tuyến chuyên cung cấp các sản phẩm tiêu dùng đa dạng, từ thực phẩm khô, bánh kẹo, đồ uống đến hóa chất và nhu yếu phẩm hàng ngày.
                        Với sứ mệnh đáp ứng nhu cầu hàng ngày của khách hàng một cách nhanh chóng và dễ dàng, VinChoice cam kết mang đến trải nghiệm mua sắm trực tuyến thoải mái và đáng tin cậy.

                       </p>
                    <p class="mb-20">VinChoice là cửa hàng tạp hóa trực tuyến với những điểm nổi bật sau: Sản phẩm đa dạng: Cung cấp sản phẩm thuộc nhiều chủng loại như thực phẩm khô, đồ uống, thực phẩm tươi sống, hóa chất và nhu yếu phẩm hàng ngày.</p>

                    <p class="mb-20">Đảm bảo chất lượng: Sản phẩm được lựa chọn kỹ càng, đảm bảo chất lượng cao, đáp ứng nhu cầu chất lượng, an toàn  thực phẩm.</p>

                    <p class="mb-20">Ưu đãi và khuyến mãi: Thường xuyên chạy các chương trình ưu đãi, giảm giá, khuyến mãi nhằm khuyến khích mua hàng và tạo lợi ích cho khách hàng.</p>

                    <p class="mb-20"> Thanh toán linh hoạt: Chấp nhận nhiều phương thức thanh toán trực tuyến, tạo sự linh hoạt cho khách hàng.</p>

                    <a href="#" class="return-customer-btn read-more">Đọc thêm</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Container End -->
</div>
<!-- About Us End Here -->
<!-- About Us Team Start Here -->
<div class="about-team pt-100 pt-sm-60">
</div>
<!-- About Us Skills End Here -->
@endsection
