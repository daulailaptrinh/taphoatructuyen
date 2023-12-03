@extends('admin/Admin')

@section('title-ad', 'Quản lý kho')

@section('content-ad')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ !isset($import) ? 'Thêm Mới' : 'Cập Nhật' }}</h6>
        </div>
        @php
            $route = isset($import) ? route('import.update', $import->id) : route('import.store');
            $daThanhToanUp = isset($import) ? $import->total_price - $import->debt : 0;
            $cusName = isset($import) ? $import->user_id . ' | ' . $import->ImportToUser->full_name . ' | ' . $import->ImportToUser->phone : '';
            $note = isset($import) ? $import->note : '';


        @endphp
        <div class="card-body">
            <form action="{{ $route }}" method="POST">
                @csrf
                @isset($import)
                    @method('PUT')
                @endisset
                <div class="row">
                    <div class="col-9" style="border-right: solid 1px #ccc">
                        <table class="table">
                            <thead>
                                <tr style="font-size: 14px; text-align: center; vertical-align: middle;">
                                    <th>
                                        <button type="button" onclick="create_tr('table_body')"
                                            class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i></button>
                                    </th>
                                    <th style="width: 30%;" class="col">SẢN PHẨM</th>
                                    <th style="width: 10%;" class="col">SỐ LƯỢNG</th>
                                    <th style="width: 20%;" class="col">GIÁ TIỀN</th>
                                    <th style="width: 20%;" class="col">THƯƠNG HIỆU</th>
                                    <th style="width: 20%;" class="col">HẠN SỬ DỤNG</th>
                                    {{--  <th style="width: 20%;"  class="col">THÀNH TIỀN</th>  --}}
                                </tr>
                            </thead>
                            <tbody id="table_body">
                                @if (isset($import))
                                    @foreach ($import->ImportToDetail as $i => $row)
                                        @php
                                            $i++;
                                            $pro = $row->ImportDetailToProduct->post;
                                            $nameUp = 'SP' . $row->product_id . ' | ' . $pro->sp_vi;
                                            $qtyUp = $row->qty;
                                            $priceUp = number_format($row->price);
                                            $categoryUp = isset($import) ? $row->ImportDetailToProduct->id_type. ' | '.$row->ImportDetailToProduct->product_type->name_type : '';
                                            $dateUp = isset($import) ? $row->ImportDetailToProduct->date_sale : '';
                                        @endphp
                                        <tr class="idtr" data-id="{{ $i }}">
                                            <td style="vertical-align: middle;">
                                                <button onclick="remove_tr(this)" type="button"
                                                    class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <input id="inputSanPham{{ $i }}"
                                                    onblur="doDuLieuSanPham('{{ $i }}')"
                                                    list="inSanPham{{ $i }}" name="name[]" required
                                                    class="form-control" autocomplete="off" value="{{ $nameUp ?? '' }}">
                                                <datalist id="inSanPham{{ $i }}">
                                                    @foreach ($products as $key => $value)
                                                        <option value="SP{{ $value->id }} | {{ $value->sp_vi }}">Tồn
                                                            kho: {{ $value->qty }} | Khách đặt:
                                                            {{ $value->bill_detail->sum('quantity') }}
                                                        </option>
                                                    @endforeach
                                                </datalist>

                                            </td>
                                            <td>
                                                <input name="qty[]" id="qty{{ $i }}" min="1" required
                                                    class="form-control" onkeyup="tinhTongTien()" step="1"
                                                    value="{{ $qtyUp ?? '' }}">
                                            </td>
                                            <td>
                                                <input name="price[]" id="price{{ $i }}" required
                                                    class="form-control" value="{{ $priceUp ?? '' }}" onkeyup="tinhTongTien()">
                                            </td>
                                            <td>
                                                <input list="category{{ $i }}" name="category[]" required
                                                    class="form-control" autocomplete="off" value="{{ $categoryUp ?? '' }}">
                                                <datalist id="category{{ $i }}">
                                                    @foreach ($category as $value)
                                                        <option value="{{ $value->id }} | {{ $value->name_type }}">
                                                        </option>
                                                    @endforeach
                                                </datalist>
                                            </td>
                                            <td>
                                                <input type="datetime-local" name="date[]" class="form-control" value="{{ $dateUp }}">
                                            </td>
                                            <td style="display: none">
                                                <input name="total" id="total{{ $i }}" required
                                                    class="form-control" readonly>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-3">
                        <div class="row">
                            <div class="col-12 col-md-4 col-lg-12">
                                <div class="form-group">
                                    <label>Nhà cung cấp (*)</label>
                                    <input list="khachHang" name="user_id" type="text" class="form-control"
                                        value="{{ $cusName }}">
                                    <datalist id="khachHang">
                                        @foreach ($users as $nd)
                                            <option
                                                value="{{ $nd->id }} | {{ $nd->full_name }} | {{ $nd->phone }}">
                                            </option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="form-group">
                                    <label>Ghi chú</label>
                                    <textarea class="form-control" name="note" rows="5">{{ $note }}</textarea>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Tổng tiền:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="tongTienHien" pattern="[0-9,]*"
                                            value="0" min="0" readonly name="tongTienHien">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Đã thanh toán:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="daThanhToan" name="daThanhToan"
                                            value="{{ $daThanhToanUp }}" min="{{ $daThanhToanUp }}" required=""
                                            onkeyup="tinhTongTien()">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-6 col-form-label">Công nợ:</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="congNo" name="congNo"
                                            pattern="[0-9,]*" value="0" min="0" required="" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-4" style="border-top: solid 1px #ccc">
                        <button style="float: right;margin-right: 0.5%;margin-top: 2%;" type="submit"
                            class="btn btn-outline-primary">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            var tableBody = document.getElementById("table_body");
            if (tableBody.rows.length > 0) {
                tinhTongTien();
            }
        });

        function formatGia(input) {
            input.value = parseFloat(input.value.replace(/,/g, ""))
                .toFixed(0)
                .toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        };

        function dinhDangGia(input) {
            var giaTri = input.value.split(","); // format tien ,,, lai thanh so
            var temp = "";
            for (var i = 0; i < giaTri.length; i++) {
                temp += giaTri[i];
            }
            input.value = parseFloat(temp);
            if (isNaN(input.value)) {
                input.value = 0;
            } else {
                formatGia(input);
            }
        };

        var stt = {{ isset($import) ? count($import->ImportToDetail) + 1 : 1 }};

        function create_tr() {
            $('#table_body').append(`
                <tr class="idtr" data-id="` + stt + `">
                    <td style="vertical-align: middle;">
                        <button onclick="remove_tr(this)" type="button"
                            class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                    <td>
                        <input id="inputSanPham` + stt + `" onblur="doDuLieuSanPham(` + stt + `)" list="inSanPham` +
                stt + `" name="name[]" required
                            class="form-control" autocomplete="off">
                        <datalist id="inSanPham` + stt + `">
                            @foreach ($products as $key => $value)
                                <option value="SP{{ $value->id }} | {{ $value->sp_vi }}">Tồn
                                    kho: {{ $value->product_quantity }} | Khách đặt: {{ $value->bill_detail->sum('quantity') }}
                                </option>
                            @endforeach
                        </datalist>

                    </td>
                    <td>
                        <input  name="qty[]" id="qty` + stt + `" min="1" required
                            class="form-control" onkeyup="tinhTongTien()" step="1">
                    </td>
                    <td>
                        <input name="price[]" id="price` + stt + `" required class="form-control" onkeyup="tinhTongTien()"
                            >
                    </td>
                    <td>
                        <input list="category` + stt + `" name="category[]" required
                            class="form-control" autocomplete="off">
                        <datalist id="category` + stt + `">
                            @foreach ($category as $value)
                                <option value="{{ $value->id }} | {{ $value->name_type }}">
                                </option>
                            @endforeach
                        </datalist>
                    </td>
                    <td>
                        <input type="datetime-local" name="date[]" required class="form-control" value="">
                    </td>
                    <td style="display: none">
                        <input name="total" id="total` + stt + `" required class="form-control"
                            readonly>
                    </td>
                </tr>
            `);
            stt++;
        }

        function remove_tr(This) {
            This.closest('tr').remove();
            tinhTongTien();
        }

        function doDuLieuSanPham(soThuTu) {
            soThuTuCanDoDuLieu = soThuTu;
            danhSachSanPham = {!! $products !!};
            var inputSanPham = document.getElementById("inputSanPham" + soThuTu);
            maSanPham = inputSanPham.value.split(" | ")[0].split("SP")[1];
            danhSachSanPham.forEach(timThongTinSanPham);
        }

        function timThongTinSanPham(thongTinSanPham) {
            if (thongTinSanPham['id'] == maSanPham) {
                document.getElementById('qty' + soThuTuCanDoDuLieu).value = 1;
                document.getElementById('price' + soThuTuCanDoDuLieu).value = 0;
                tinhTongTien();
            }
        }

        function tinhTongTien() {
            var soTienGiam = 0;
            var inputTongTien = document.getElementById('tongTienHien');
            var inputCongNo = document.getElementById('congNo');
            var inputDaThanhToan = document.getElementById('daThanhToan');

            var giaTriDaThanhToan = inputDaThanhToan.value.split(","); // format tien ,,, lai thanh so
            var temp = "";
            for (var i = 0; i < giaTriDaThanhToan.length; i++) {
                temp += giaTriDaThanhToan[i];
            }
            inputDaThanhToan.value = temp; // format tien ,,, lai thanh so

            inputTongTien.value = 0;
            inputCongNo.value = 0;

            var tableDonhang = document.getElementById("table_body");
            var demDong = tableDonhang.rows.length;
            $(".idtr").each(function() {
                var item = $(this).data('id');
                var inputSoLuong = document.getElementById('qty' + item);
                var inputDonGia = document.getElementById('price' + item);


                var giaTriDonGia = inputDonGia.value.split(","); // format tien ,,, lai thanh so
                temp = "";
                for (var j = 0; j < giaTriDonGia.length; j++) {
                    temp += giaTriDonGia[j];
                }
                inputDonGia.value = temp; // format tien ,,, lai thanh so

                var inputThanhTien = document.getElementById('total' + item);
                inputSoLuong.value = parseFloat(inputSoLuong.value);
                inputDonGia.value = parseFloat(inputDonGia.value);
                inputThanhTien.value = parseFloat(inputSoLuong.value * inputDonGia.value);
                inputTongTien.value = parseFloat(inputTongTien.value) + parseFloat(inputSoLuong.value * inputDonGia
                    .value);
                if (isNaN(inputDonGia.value)) inputDonGia.value = 0;
                if (isNaN(inputSoLuong.value)) inputSoLuong.value = 0;
                if (isNaN(inputThanhTien.value)) inputThanhTien.value = 0;
                formatGia(inputDonGia);
                formatGia(inputThanhTien);
            });

            if (parseFloat(inputDaThanhToan.value) > parseFloat(inputTongTien.value)) {
                inputDaThanhToan.value = inputDaThanhToan.min;
            }

            inputCongNo.value = parseFloat(inputDaThanhToan.value) - parseFloat(inputTongTien.value);

            if (isNaN(inputTongTien.value)) {
                inputTongTien.value = 0;

            }
            if (isNaN(inputDaThanhToan.value)) inputDaThanhToan.value = 0;
            if (isNaN(inputCongNo.value)) inputCongNo.value = 0;

            inputDaThanhToan.setAttribute("min", inputDaThanhToan.min);
            inputDaThanhToan.setAttribute("max", inputTongTien.value);

            formatGia(inputTongTien);
            formatGia(inputDaThanhToan);
            formatGia(inputCongNo);
        };
    </script>
@stop
