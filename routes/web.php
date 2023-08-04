<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductImgController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductCommentController;
use App\Http\Controllers\ProductController;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\Product\ProductService;
use App\Services\Product\ProductServiceInterface;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\SubProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [IndexController::class, 'index'])->name('home');

Route::prefix('shop')->name('shop.')->group(function(){
    Route::get('/', [ShopController::class, 'index'])->name('products');
    Route::get('/category/{category}', [ShopController::class, 'index'])->name('category');
});

Route::prefix('product')->name('product.')->group(function(){
    Route::get('/{product}', [ProductController::class, 'index'])->name('detail');
    Route::post('comment/{id}', [ProductController::class, 'comment'])->name('comment');
});


Route::get('/cart', [CartController::class, 'index'])->name('cart')->middleware('auth');
Route::post('/update-cart', [CartController::class, 'update'])->name('updateCart')->middleware('auth');
Route::post('/delete-cart-item', [CartController::class, 'delete'])->name('deleteCartItem')->middleware('auth');

Route::prefix('checkout')->middleware('auth')->name('checkout.')->group(function(){
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'process'])->name('checkoutPost');
});


Route::post('add-to-cart', [CartController::class, 'add'])->name('addToCart');
Route::get('pre-cart/{id}', [ProductController::class, 'getProductItemDetail'])->name('countQtyProdcut');

Auth::routes();

// admin 
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function(){
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // manage product
    Route::prefix('product')->name('product.')->group(function(){
        Route::get('/', [AdminProductController::class, 'index'])->name('list');
        Route::get('show/{id}', [AdminProductController::class, 'show'])->name('show');

        Route::get('add', [AdminProductController::class, 'add'])->name('add');
        Route::post('add', [AdminProductController::class, 'postAdd'])->name('postAdd');

        Route::get('edit/{id}', [AdminProductController::class, 'showFormEdit'])->name('showFormEdit');
        Route::post('edit/{id}', [AdminProductController::class, 'edit'])->name('edit');
        Route::get('/delete/{product}', [AdminProductController::class, 'delete'])->name('sortDelete');
        Route::get('/restore/{id}', [AdminProductController::class, 'restore'])->name('restore');
        Route::delete('/force-delete', [AdminProductController::class, 'forceDelete'])->name('forceDelete');

        Route::get('/{product_id}/image', [ProductImgController::class, 'index'])->name('productImg');
        Route::post('/{product_id}/image', [ProductImgController::class, 'upload'])->name('uploadProductImg');
        Route::get('/{product_id}/image/{product_img_id}', [ProductImgController::class, 'delete'])->name('deleteImg');

        Route::get('/{product_id}/sub-items', [SubProductController::class, 'showSubItems'])->name('showSubItems');
        Route::get('/{product}/sub-item/{sub_item}', [SubProductController::class, 'showSubItem'])->name('showSubItem');  
        Route::post('/{product}/sub-item/{sub_item}', [SubProductController::class, 'editSubItem'])->name('editSubItem');
        Route::get('/{product}/sub-item', [SubProductController::class, 'addSubItem'])->name('addSubItem');
        Route::post('/{product}/sub-item', [SubProductController::class, 'postAddSubItem'])->name('postAddSubItem');

        // delete subitem
    
    });

    // manage order
    Route::prefix('order')->name('order.')->group(function(){
        Route::get('/', [AdminOrderController::class, 'index'])->name('list');
        Route::post('/change-status/{id}', [AdminOrderController::class, 'changeStatus'])->name('changeStatus');
        Route::get('/show/{id}', [AdminOrderController::class, 'detail'])->name('detail');
    });


    // manage user
    Route::prefix('user')->name('user.')->group(function(){
        Route::get('/', [AdminUserController::class, 'index'])->name('list');
        Route::get('/delete/{user}', [AdminUserController::class, 'delete'])->name('sortDelete');
        Route::get('/restore/{id}', [AdminUserController::class, 'restore'])->name('restore');
        Route::delete('/force-delete', [AdminUserController::class, 'forceDelete'])->name('forceDelete');

        // edit user
    });
   
});

// user
Route::prefix('user')->name('user.')->middleware(['auth', 'user'])->group(function(){
    Route::get('/', [UserController::class, 'index'])->name('index');

    Route::prefix('order')->name('order.')->group(function(){
        Route::get('/', [UserController::class, 'show'])->name('order');
        Route::get('/{id}', [UserController::class, 'detailOrder'])->name('show');
    });
    
      
});

