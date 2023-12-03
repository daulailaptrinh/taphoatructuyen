<!DOCTYPE html>
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <title>[Admin] Vinchoice - In phiếu nhập</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family:'Roboto';
            font-size: 14px;
        }

        .text-right {
            text-align: right;
            vertical-align: middle !important;
        }

        .text-center {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>

</head>

<body class="login-page" style="background: white; font-family: Roboto;">

    <div>
        <div class="row">
            <div class="col-xs-7">
                <h4 style="text-transform: uppercase;">Vinchoice</h4>
                <b>SĐT:</b> 090.xxx.xnxx (Mr. N) <br>
                <b>EMAIL</b>: support@gmail.com<br>
                <b>ĐỊA CHỈ:</b> Đại Học Cần Thơ, Đường 3/2 Phường An Khánh Quận Ninh Kiều TP Cần Thơ <br>
                <br>
            </div>

            <div class="col-xs-4">
                <img width="250" height="150"
                    src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcQ_di8q3SjGB7OeF5yGMwm9eiMn1eTTHrad53F4o5Dl_mNhFRn4" />

            </div>
        </div>

        <div style="margin-bottom: 0px">&nbsp;</div>
        @php
            $date = Carbon\Carbon::parse($import->created_at);
        @endphp
        <div class="row" style="font-family:'Roboto'; text-align: center;margin-bottom: 3.5rem;">
            <h2 style="font-weight: bold">PHIẾU NHẬP HÀNG</h2>
            <div style="text-align: center; margin-bottom: 2%;margin-top: -1%;font-size: 9pt">
                Mã phiếu: PN{{ $import->id }}<br>
                Ngày {{ $date->format('d') }} tháng {{ $date->format('m') }} năm {{ $date->format('Y') }}
            </div>
        </div>

        <div class="row" style="margin-bottom: 3%;font-size: 10pt">
            <div class="col-xs-6">
                <b>Nhà cung cấp:</b>
                {{ $import->ImportToUser->full_name }}<br>
                <b>SĐT:</b>
                {{ $import->ImportToUser->phone }}<br>
                <b>Địa chỉ:</b> {{ $import->ImportToUser->address }}
            </div>
            <div class="col-xs-6">
                <b>Mã NCC:</b> ND{{ $import->user_id }}<br>
                <b>Ghi chú:</b> {{ $import->note }}
            </div>
        </div>
        <table class="table">
            <thead style="background: #F5F5F5;">
                <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">Sản phẩm</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-center">Đơn giá</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($importDetail as $key => $ct)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>SP{{ $ct->product_id }} | {{ $ct->ImportDetailToProduct->post->sp_vi }}</td>
                        <td class="text-center">{{ $ct->qty }}</td>
                        <td class="text-center">{{ number_format($ct->price) }}</td>
                        <td class="text-right">{{ number_format($ct->qty * $ct->price) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row">
            <div class="col-xs-6"></div>
            <div class="col-xs-5">
                <table style="width: 100%">
                    <tbody>
                        <tr class="well" style="padding: 5px">
                            <th style="padding: 5px">
                                <div>Tổng tiền</div>
                            </th>
                            <td style="padding: 5px" class="text-right">{{ number_format($import->total_price) }}</td>
                        </tr>
                        <tr class="well" style="padding: 5px">
                            <th style="padding: 5px">
                                <div>Đã thanh toán</div>
                            </th>
                            <td style="padding: 5px" class="text-right">
                                {{ number_format($import->total_price - $import->debt) }}</td>
                        </tr>
                        <tr class="well" style="padding: 5px">
                            <th style="padding: 5px">
                                <div>Công nợ</div>
                            </th>
                            <td style="padding: 5px" class="text-right">{{ number_format($import->debt) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-bottom: 6%">&nbsp;</div>

        <div class="row">
            <table style="width: 100%" class="text-center">
                <tr>
                    <td>Ngày {{ $date->format('d') + 10 }} tháng {{ $date->format('m') }} năm
                        {{ $date->format('Y') }}</td>
                    <td>Ngày {{ $date->format('d') + 10 }} tháng {{ $date->format('m') }} năm
                        {{ $date->format('Y') }}</td>
                </tr>
                <tr>
                    <td>Người giao hàng</td>
                    <td>Người nhận hàng</td>
                </tr>
            </table>
        </div>

</body>

</html>
