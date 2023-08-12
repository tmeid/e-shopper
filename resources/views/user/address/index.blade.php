@extends('dashboard')

@section('title')
Địa chỉ cá nhân | E-shopper
@endsection

@section('sidebar')
@include('user.sidebar')
@endsection

@section('content')
<div class="container">
    <h2>Địa chỉ cá nhân</h2>
    <button type="button" class="create btn btn-success" data-toggle="modal" data-target="#modalCreate">Thêm địa chỉ</button>
    <hr>
    <div class="row address-div">
        @if(count($addresses))
        @foreach($addresses as $address)
        <div class="col-12 address-{{ $address->id }}">
            <p>
                <span class="name-{{ $address->id }}">{{ $address->name }}</span> | <span class="phone-{{ $address->id }}">{{ $address->phone }} </span>
            </p>
            <p class="address-{{ $address->id }}">{{ $address->address }}</p>
            <p>
                <button type="button" class="edit btn border-success" data-toggle="modal" data-target="#modalEdit{{ $address->id }}" id="{{ $address->id }}">Cập nhật</button>
                <button type="button" class="delete-btn btn border-danger">Xoá</button>

                <!-- Edit Modal -->
            <div class="modal fade" id="modalEdit{{ $address->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Cập nhật địa chỉ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $address->id }}" class="id">
                                <label for="name">Tên</label>
                                <span class="text-danger name-error-{{ $address->id }}"></span>
                                <input type="text" name="name" value="{{ $address->name }}" id="name" class="form-control name">

                                <label for="phone">Số điện thoại</label>
                                <span class="text-danger phone-error-{{ $address->id }}"></span>
                                <input type="text" name="phone" value="{{ $address->phone }}" id="phone" class="form-control phone">

                                <label for="address">Địa chỉ</label>
                                <span class="text-danger address-error-{{ $address->id }}"></span>
                                <input type="text" name="address" value="{{ $address->address }}" id="address" class="form-control address">

                                <hr>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn" data-dismiss="modal">Đóng</button>
                                    <button type="submit" class="edit-modal btn btn-primary">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </p>
            <hr>

        </div>
        @endforeach
        @else
        <p>Chưa có địa chỉ</p>
        @endif
    </div>

    <!-- Create Modal  -->
    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Thêm địa chỉ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        <label for="add-name">Tên</label>
                        <span class="text-danger addName-error"></span>
                        <input type="text" name="addName" id="add-name" class="form-control addName">

                        <label for="add-phone">Số điện thoại</label>
                        <span class="text-danger addPhone-error"></span>
                        <input type="text" name="addPhone" id="add-phone" class="form-control addPhone">

                        <label for="add-address">Địa chỉ</label>
                        <span class="text-danger addAddress-error"></span>
                        <input type="text" name="addAddress" id="add-address" class="form-control addAddress">

                        <hr>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="create-modal btn btn-primary">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.edit-modal').on('click', function(e) {
            e.preventDefault();
            let id = $('.modal.show .id').val();

            $.ajax({
                method: 'POST',
                url: '/user/address/' + id,
                dataType: 'json',
                data: {
                    name: $('.modal.show .name').val(),
                    phone: $('.modal.show .phone').val(),
                    address: $('.modal.show .address').val()
                },
                beforeSend: function(){
                    $('.name-error-' + id).text('');
                    $('.phone-error-' + id).text('');
                    $('.address-error-' + id).text('');
                },
                success: function(response) {
                    if (response.status == 404) {
                        alert('Không thể update thông tin không tồn tại');
                    } else if (response.status == 304) {
                        alert('Có lỗi xảy ra, vui lòng thử lại');
                    } else if (response.status == 200) {
                        // $('body').removeClass('modal-open');
                        // $('.modal-backdrop').remove();
                        alert('Cập nhật thành công');
                        location.reload();
                    }
                },
                error: function(error) {
                    let respJson = error.responseJSON.errors;
                    if (Object.keys(respJson).length > 0) {
                        for (let key in respJson) {
                            $('.modal.show .' + key + '-error-' + id).text(respJson[key][0]);
                        }
                    }
                }
            })
        });

        $('.delete-btn').on('click', function(e) {
            let id = $(this).prev().attr('id');
            $.ajax({
                method: 'DELETE',
                url: '/user/address/' + id,
                beforeSend: function(){
                    return confirm('Bạn chắc chắn xoá');
                },
                success: function(response){
                    if(response.status == 200){
                        $('.address-' + id).remove();
                    }else if(response.status == 404){
                        alert('Địa chỉ không tồn tại');
                    }else if(response.status == 304){
                        alert('Đã có lỗi xảy ra, vui lòng thử lại');
                    }
                    
                },
                error: function(error){
                    console.log(error);
                }
            });
        });


        $('.create-modal').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                method: 'POST',
                url: '/user/address/add',
                dataType: 'json',
                data: {
                    addName: $('.addName').val(),
                    addPhone: $('.addPhone').val(),
                    addAddress: $('.addAddress').val()
                },
                beforeSend: function(){
                    $('.addName-error').text('');
                    $('.addPhone-error').text('');
                    $('.addAddress-error').text('');
                },
                success: function(response) {
                    if (response.status == 304) {
                        alert('Có lỗi xảy ra, vui lòng thử lại');
                    } else if (response.status == 200) {
                        alert('Thêm thành công');
                        // let data = response.data;

                        // let html = '<div class="col-12 address-' + data['id'] + '">';
                        // html += '<p><span class="name-' + data['id'] + '">' + data['name'] + '</span> | <span class="phone-' + data['id'] + '">' + data['phone'] + '</span></p>';
                        // html += '<p class="address-' + data['id'] + '">' + data['address'] + '</p>';
                        // html += '<p>';
                        // html += '<button type="button" class="edit btn border-success" data-toggle="modal" data-target="#modalEdit' + data['id'] + ' id=' + data['id'] + '">Cập nhật</button>';
                        // html += '<button type="button" class="delete-btn btn border-danger">Xoá</button>';


                        // html += '<div class="modal fade" id="modalEdit' + data['id'] + '" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">';
                        // html += '<div class="modal-dialog modal-dialog-centered" role="document">';
                        // html += '<div class="modal-content">';
                        // html += '<div class="modal-header">';
                        // html += '<h5 class="modal-title" id="exampleModalLongTitle">Cập nhật địa chỉ</h5>';
                        // html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                        // html += '<span aria-hidden="true">&times;</span>';
                        // html += '</button>';
                        // html += '</div>';
                        // html += '<div class="modal-body">';
                        // html += '<form action="" method="POST">';
                        // html += '@csrf';
                        // html += '<input type="hidden" name="id" value="' + data['id'] + '" class="id">';
                        // html += '<label for="name">Tên</label>';
                        // html += '<span class="text-danger name-error-' + data['id'] + '"></span>';
                        // html += '<input type="text" name="name" value="' + data['name'] + '" id="name" class="form-control name">';

                        // html += '<label for="phone">Số điện thoại</label>';
                        // html += '<span class="text-danger phone-error-' + data['id'] + '"></span>';
                        // html += '<input type="text" name="phone" value="' + data['phone'] + '" id="phone" class="form-control phone">';

                        // html += '<label for="address">Địa chỉ</label>';
                        // html += '<span class="text-danger address-error-' + data['id'] + '"></span>';
                        // html += '<input type="text" name="address" value="' + data['address'] + '" id="address" class="form-control address">';

                        // html += '<hr>';
                        // html += '<div class="d-flex justify-content-end">';
                        // html += '<button type="button" class="btn" data-dismiss="modal">Đóng</button>';
                        // html += '<button type="submit" class="edit-modal btn btn-primary">Cập nhật</button>';
                        // html += '</div>';
                        // html += '</form>';
                        // html += '</div>';
                        // html += '</div>';
                        // html += '</div>';
                        // html += '</div>';
                        // html += '</p>';
                        // html += '<hr>'

                        // html += '</div>';
                        // $('.address-div').append(html);
                        location.reload();
                    }

                },
                error: function(error) {
                    let respJson = error.responseJSON.errors;
                    if (Object.keys(respJson).length > 0) {
                        for (let key in respJson) {
                            $('.' + key + '-error').text(respJson[key][0]);
                        }
                    }
                    
                }
            })
        });
    });
</script>
@endsection