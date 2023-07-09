@extends('dashboard')

@section('title')
Sửa sản phẩm | E-shopper
@endsection

@section('sidebar')
@include('admin.sidebar')
@endsection

@section('content')
<div class="container">
<div class="col-md-12" style="color:black">
        <div class="main-card mb-3 card">
            <div class="card-body display_data">

                <div class="position-relative row form-group">
                    <label for="" class="col-md-2 text-md-right col-form-label">Ảnh:</label>
                    <div class="col-md-10 col-xl-8 col-form-label">
                        <ul class="text-nowrap d-flex flex-wrap" id="images">
                            @if(count($productImgs) > 0)
                            @foreach($productImgs as $productImg)
                            <li class="float-left d-inline-block mr-2 mb-2" 
                            style="position:relative; width: 32%">

                                <form action="{{ route('admin.product.deleteImg', ['product_id' => $product_id, 'product_img_id' => $productImg->id ])}}" enctype="multipart/form-data">
                                    <button type="submit"
                                    onclick="return confirm('Bạn có chắc chắn muốn xoá')" class="btn btn-sm btn-outline-danger border-0 position-absolute">x</button>
                                </form>  
                                <div style="width:100%; overflow:hidden">
                                    <img src="{{ asset('imgs/products/' .$productImg->path) }}" alt="" style="width: 100%; display:block; max-width:100%">
                                </div>
                            </li>
                            @endforeach
                            @endif

                            <form action="" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="upload_image">
                                <input type="submit" name="submit">
                            </form>
                            
                        </ul>
                    </div>
                </div>

                


            </div>
        </div>
    </div>
</div>
@endsection