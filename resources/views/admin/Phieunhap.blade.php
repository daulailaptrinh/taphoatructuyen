@extends('admin/Admin')

@section('title-ad', 'Quản lý kho')

@section('content-ad')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách</h6>
        </div>
        <div style="margin-top: 25px; margin-bottom: 1px; margin-left: 22px">
            <table>
                <tr>
                    <a href="{{ route('import.create') }}">
                        <button class="btn btn-outline-primary">
                            <i class="fa fa-plus" aria-hidden="true"></i> {{ trans('home_ad.add') }}
                        </button>
                    </a>
                </tr>&ensp;
            </table>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"
                    style="text-align: center;">
                    <thead>
                        <tr style="text-transform: capitalize;">
                            <th>ID</th>
                            <th>thời gian</th>
                            <th>tên nhà cung cấp</th>
                            <th>trạng thái</th>
                            <th>tổng tiền</th>
                            <th>thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($imports as $key => $item)
                            <tr>
                                <td>PN{{ $key+=1 }}</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i A') }}</td>
                                <td>{{ ucfirst($item->ImportToUser->full_name) }}</td>
                                <td>{{ $item->debt > 0 ? 'Công nợ' : 'Đã thanh toán' }}</td>
                                <td>{{ number_format($item->total_price) }}</td>
                                <td>
                                    <a href="{{ route('import.edit', $item->id) }}" target="_blank">
                                        <button class="btn btn-outline-success" type="button"><i
                                                class="fa fa-print"></i></button>
                                    </a>
                                    <a href="{{ route('import.show', $item->id) }}">
                                        <button class="btn btn-outline-primary" type="button">&nbsp;<i
                                                class="fas fa-info">&nbsp;</i></button>
                                    </a>
                                    <button data-url="{{ route('import.destroy', $item->id) }}"
                                        class="btn btn-outline-danger clickDel" type="button">&nbsp;<i
                                            class="fas fa-trash-alt">&nbsp;</i></button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal Delete-->
    <div class="modal fade" id="ModalDel" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bạn muốn xóa?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Chọn "Delete" nếu bạn đã chắc chắn muốn xóa.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Huỷ bỏ </button>

                    <form method="POST" id="formDel" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            Delete
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).on('click','.clickDel', function(){
            var url = $(this).data('url');
            $('#ModalDel').modal('show');
            $('#formDel').attr('action',url);
        });
    </script>
@stop
