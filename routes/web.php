<?php

use App\Http\Controllers\Admin\ActionLogController;
use App\Http\Controllers\Admin\AdminIndexController;
use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Admin\BrandsController;
use App\Http\Controllers\Admin\ChangelogsController;
use App\Http\Controllers\Admin\ConfigurationsController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\DistrictsController;
use App\Http\Controllers\Admin\InformationController;
use App\Http\Controllers\Admin\KeywordsController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\Admin\MessagesController;
use App\Http\Controllers\Admin\NewsCategoriesController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\NewsesController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\PhotoGalleryController;
use App\Http\Controllers\Admin\ProductCategoriesController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\ReviewsController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\SeosController;
use App\Http\Controllers\Admin\SlidersController;
use App\Http\Controllers\Admin\SubscribesController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\TextpagesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Front\IndexController;
use App\Http\Livewire\Client\About\AboutIndex;
use App\Http\Livewire\Client\Contact\ContactIndex;
use App\Http\Livewire\Client\Home\Index;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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
// Route::get('/api', [IndexController::class, 'index']);

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {
    Route::get('/', Index::class)->name('clientIndex');
    Route::get('/contact', ContactIndex::class)->name('clientContact');
    Route::get('/about', AboutIndex::class)->name('clientAbout');

    Route::get('/permissions', function () {
        return User::roles();
    });
});

Route::get('/admin/login', [LoginController::class, 'index'])->middleware('AdminLogin')->name('LoginPageAdmin');
Route::post('/admin/singin', [LoginController::class, 'singin'])->middleware('AdminLogin')->name('LoginAdmin');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('LogoutAdmin');

Route::middleware(['admin', 'check_permission'])->group(function () {
    Route::prefix('admin')->group(function () {
        // ადმინისტრატორის პანელის მთავარი გვერდი
        Route::get('/', [AdminIndexController::class, 'index'])->name('AdminMainPage');

        /*
         * ყველა მოდულისათვის საერთო მეთოდები
         */

        // integer ტიპის ისეთი ველების განახლება, რომელთა შესაძლო მნიშვნელობებიცაა 0 და 1
        Route::post('status', [BaseController::class, 'status'])->name('Status');
        // წაშლა
        Route::post('/remove', [BaseController::class, 'remove'])->name('Remove');
        // სტატუსის შეცვლა რამოდენიმე ელემენტზე ერთდროულად ან მათი წაშლა
        Route::post('/multi', [BaseController::class, 'multi'])->name('Multi');
        // თანმიმდევრობის შეცვლა ჩამონათვალის გვერდზე
        Route::post('/ordering', [BaseController::class, 'ordering'])->name('Ordering');
        // მიმაგრებული ფაილის წაშლა და შესაბამისი ველის მნიშვნელობად null
        Route::post('/remove_file', [BaseController::class, 'remove_file'])->name('RemoveFile');
        // ფოტოს წაშლა გალერიიდან
        Route::post('/remove_image_from_gallery', [BaseController::class, 'remove_image_from_gallery'])->name('RemoveImageFromGallery');
        // ვიდეოს წაშლა გალერიიდან
        Route::post('/remove_video_from_gallery', [BaseController::class, 'remove_video_from_gallery'])->name('RemoveVideoFromGallery');

        // სლაიდერი
        Route::prefix('sliders')->group(function () {
            Route::get('/', [SlidersController::class, 'index'])->name('Sliders');
            Route::get('/add', [SlidersController::class, 'create'])->name('AddSliders');
            Route::post('create', [SlidersController::class, 'store'])->name('StoreSliders');
            Route::get('/edit/{id}', [SlidersController::class, 'edit'])->name('EditSliders');
            Route::post('update/{id}', [SlidersController::class, 'update'])->name('UpdateSliders');
        });

        Route::prefix('products')->group(function () {
            Route::get('/', [ProductsController::class, 'index'])->name('ProductsIndex');

            // პროდუქტი
            Route::prefix('/product')->group(function () {
                Route::get('/', [ProductController::class, 'index'])->name('Products');
                Route::get('/add', [ProductController::class, 'create'])->name('AddProducts');
                Route::post('create', [ProductController::class, 'store'])->name('StoreProducts');
                Route::get('/edit/{id}/{page?}', [ProductController::class, 'edit'])->name('EditProducts');
                Route::post('update/{id}', [ProductController::class, 'update'])->name('UpdateProducts');
                Route::post('/remove_color_images', [ProductController::class, 'RemoveColorImages'])->name('RemoveColorImageProducts');
                Route::get('/search', [ProductController::class, 'search'])->name('SearchProducts');
                Route::post('/livesearch', [ProductController::class, 'live_search'])->name('LiveSearchProducts');
                Route::get('/import', [ProductController::class, 'import'])->name('ImportProducts');
                Route::post('/import', [ProductController::class, 'upload'])->name('UploadProducts');
            });

            // კატეგორიები
            Route::prefix('/categories')->group(function () {
                Route::get('/', [ProductCategoriesController::class, 'index'])->name('ProductCategories');
                Route::get('/add', [ProductCategoriesController::class, 'create'])->name('AddProductCategories');
                Route::post('create', [ProductCategoriesController::class, 'store'])->name('StoreProductCategories');
                Route::get('/edit/{id}', [ProductCategoriesController::class, 'edit'])->name('EditProductCategories');
                Route::post('update/{id}', [ProductCategoriesController::class, 'update'])->name('UpdateProductCategories');
            });

            // ბრენდები
            Route::prefix('/brands')->group(function () {
                Route::get('/', [BrandsController::class, 'index'])->name('Brands');
                Route::get('/add', [BrandsController::class, 'create'])->name('AddBrands');
                Route::post('create', [BrandsController::class, 'store'])->name('StoreBrands');
                Route::get('/edit/{id}', [BrandsController::class, 'edit'])->name('EditBrands');
                Route::post('update/{id}', [BrandsController::class, 'update'])->name('UpdateBrands');
            });

            // აქციები
            Route::prefix('/sales')->group(function () {
                Route::get('/', [SalesController::class, 'index'])->name('Sales');
                Route::get('/add', [SalesController::class, 'create'])->name('AddSales');
                Route::post('create', [SalesController::class, 'store'])->name('StoreSales');
                Route::get('/edit/{id}', [SalesController::class, 'edit'])->name('EditSales');
                Route::post('update/{id}', [SalesController::class, 'update'])->name('UpdateSales');
            });

            // შეფასებები
            Route::prefix('/reviews')->group(function () {
                Route::get('/', [ReviewsController::class, 'index'])->name('Reviews');
                Route::post('remove', [ReviewsController::class, 'remove'])->name('RemoveReviews');
            });
        });

        Route::prefix('newses')->group(function () {
            Route::get('/', [NewsesController::class, 'index'])->name('Newses');

            // სიხლეები
            Route::prefix('/news')->group(function () {
                Route::get('/', [NewsController::class, 'index'])->name('News');
                Route::get('/add', [NewsController::class, 'create'])->name('AddNews');
                Route::post('create', [NewsController::class, 'store'])->name('StoreNews');
                Route::get('/edit/{id}', [NewsController::class, 'edit'])->name('EditNews');
                Route::post('update/{id}', [NewsController::class, 'update'])->name('UpdateNews');
            });

            // სიხლეების კატეგორიები
            Route::prefix('/categories')->group(function () {
                Route::get('/', [NewsCategoriesController::class, 'index'])->name('NewsCategories');
                Route::get('/add', [NewsCategoriesController::class, 'create'])->name('AddNewsCategories');
                Route::post('create', [NewsCategoriesController::class, 'store'])->name('StoreNewsCategories');
                Route::get('/edit/{id}', [NewsCategoriesController::class, 'edit'])->name('EditNewsCategories');
                Route::post('update/{id}', [NewsCategoriesController::class, 'update'])->name('UpdateNewsCategories');
            });

            // ტეგები
            Route::prefix('tags')->group(function () {
                Route::get('/', [TagsController::class, 'index'])->name('Tags');
                Route::get('/add', [TagsController::class, 'create'])->name('AddTags');
                Route::post('create', [TagsController::class, 'store'])->name('StoreTags');
                Route::get('/edit/{id}', [TagsController::class, 'edit'])->name('EditTags');
                Route::post('update/{id}', [TagsController::class, 'update'])->name('UpdateTags');
            });
        });

        // ფოტო გალერეა
        Route::prefix('photogallery')->group(function () {
            Route::get('/', [PhotoGalleryController::class, 'index'])->name('PhotoGalleries');
            Route::get('/add', [PhotoGalleryController::class, 'create'])->name('AddPhotoGalleries');
            Route::post('create', [PhotoGalleryController::class, 'store'])->name('StorePhotoGalleries');
            Route::get('/edit/{id}', [PhotoGalleryController::class, 'edit'])->name('EditPhotoGalleries');
            Route::post('update/{id}', [PhotoGalleryController::class, 'update'])->name('UpdatePhotoGalleries');
        });

        // ტექსტური გვერდები
        Route::prefix('textpages')->group(function () {
            Route::get('/', [TextpagesController::class, 'index'])->name('Textpages');
            Route::get('/add', [TextpagesController::class, 'create'])->name('AddTextpages');
            Route::post('create', [TextpagesController::class, 'store'])->name('StoreTextpages');
            Route::get('/edit/{id}', [TextpagesController::class, 'edit'])->name('EditTextpages');
            Route::post('update/{id}', [TextpagesController::class, 'update'])->name('UpdateTextpages');
        });

        // რეგისტრირებული მომხმარებლები
        Route::prefix('users')->group(function () {
            Route::get('/', [UsersController::class, 'index'])->name('Users');
            Route::get('/filter', [UsersController::class, 'filter'])->name('FilterUsers');
            Route::get('/export', [UsersController::class, 'export'])->name('ExportUsers');
        });

        // შეკვეთები
        Route::prefix('/orders')->group(function () {
            Route::get('/', [OrdersController::class, 'index'])->name('Orders');
            Route::get('/details/{id}', [OrdersController::class, 'details'])->name('OrderDetails');
            Route::get('/export', [OrdersController::class, 'export'])->name('ExportOrders');
            Route::post('status', [OrdersController::class, 'status'])->name('StatusOrders');
            Route::get('/generate_pdf/{id}', [OrdersController::class, 'generate_pdf'])->name('GeneratePdfOrders');
        });

        // მომხმარებელი ეძებს
        Route::prefix('keywords')->group(function () {
            Route::get('/', [KeywordsController::class, 'index'])->name('Keywords');
        });

        // ამ გვერდებზე შესვლის უფლება აქვს მხოლოდ სუპერადმინს
        Route::middleware('check_if_super')->group(function () {
            // ფასდაკლებები
            Route::prefix('/coupons')->group(function () {
                Route::get('/', [CouponsController::class, 'index'])->name('Coupons');
                Route::post('remove', [CouponsController::class, 'remove'])->name('RemoveCoupons');
                Route::get('/import', [CouponsController::class, 'import'])->name('ImportCoupons');
                Route::post('/import', [CouponsController::class, 'upload'])->name('UploadCoupons');
            });

            // უბნები
            Route::prefix('districts')->group(function () {
                Route::get('/', [DistrictsController::class, 'index'])->name('Districts');
                Route::get('/add', [DistrictsController::class, 'create'])->name('AddDistricts');
                Route::post('create', [DistrictsController::class, 'store'])->name('StoreDistricts');
                Route::get('/edit/{id}', [DistrictsController::class, 'edit'])->name('EditDistricts');
                Route::post('update/{id}', [DistrictsController::class, 'update'])->name('UpdateDistricts');
            });

            // საკონტაქტო ინფორმაციის გვერდი
            Route::prefix('informations')->group(function () {
                Route::get('/', [InformationController::class, 'edit'])->name('EditInformations');
                Route::post('/update/{id}', [InformationController::class, 'update'])->name('UpdateInformations');
            });

            // შეტყობინები
            Route::prefix('messages')->group(function () {
                Route::get('/', [MessagesController::class, 'index'])->name('Messages');
                Route::get('/remove/{id}', [MessagesController::class, 'remove'])->name('RemoveMessages');
                Route::post('/seen', [MessagesController::class, 'seen'])->name('SeenMessages');
            });

            // გამომწერები
            Route::prefix('subscribes')->group(function () {
                Route::get('/', [SubscribesController::class, 'index'])->name('Subscribes');
            });

            // SEO
            Route::prefix('seos')->group(function () {
                Route::get('/', [SeosController::class, 'index'])->name('Seos');
                Route::get('/add', [SeosController::class, 'create'])->name('AddSeos');
                Route::post('create', [SeosController::class, 'store'])->name('StoreSeos');
                Route::get('/edit/{route}', [SeosController::class, 'edit'])->name('EditSeos');
                Route::post('update/{id}', [SeosController::class, 'update'])->name('UpdateSeos');
            });

            // ადმინისტრატორები
            Route::prefix('admins')->group(function () {
                Route::get('/', [AdminsController::class, 'index'])->name('Admins');
                Route::get('/add', [AdminsController::class, 'create'])->name('AddAdmins');
                Route::post('create', [AdminsController::class, 'store'])->name('StoreAdmins');
                Route::get('/edit/{id}', [AdminsController::class, 'edit'])->name('EditAdmins');
                Route::post('update/{id}', [AdminsController::class, 'update'])->name('UpdateAdmins');
                Route::post('remove', [AdminsController::class, 'remove'])->name('RemoveAdmins');
            });

            // ჟურნალი
            Route::prefix('logs')->group(function () {
                Route::get('/', [LogsController::class, 'index'])->name('Logs');

                Route::prefix('changelog')->group(function () {
                    Route::get('/', [ChangelogsController::class, 'index'])->name('Changelogs');
                });

                Route::prefix('operationlog')->group(function () {
                    Route::get('/', [ActionLogController::class, 'index'])->name('Operationlogs');
                });
            });

            // საიტის კონფიგურაციული პარამეტრები
            Route::prefix('configuration')->group(function () {
                Route::get('/', [ConfigurationsController::class, 'edit'])->name('EditConfigurations');
                Route::post('/update/{id}', [ConfigurationsController::class, 'update'])->name('UpdateConfigurations');
                Route::get('/remove_cache_key/{key}', [ConfigurationsController::class, 'remove_cache_key'])->name('RemoveCacheKeyConfigurations');
            });
        });
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
