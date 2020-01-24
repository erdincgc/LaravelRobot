<?php

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
use App\Model\BlogCategory;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;


//KİTAP 2 ÖRNEKLERİ - Laravel up and running























//KİTAP 1 ÖRNEKLERİ
Route::get('/', function () {

    $all = (new App\Model\BlogCategory)->first();
    $newData = new BlogCategory();
    $newData->name = 'erdinç';
    $newData->save();
});

Route::get('/home', 'HomeControler@index');

Route::get('/page1', function () {
    return 'Burası  Page1 !'. URL::to('page2');
});
                // ?????????? ESKİ Mİ
                //Route::filter('id', function () {
                //    if (Input::get('id') < 200) {
                //        die('200 den küçük id li kullanıcı giriş yapamaz');
                //    }
                //});
Route::get('/page2', array('as' => 'profil','HomeController@index'));
// as olarak belirtilen route name tanımlaması aşağıdaki şekillerde kod içinde kullanılabilir
// $url = URL::route('profil');
// $yonlendirme = Redirect::route('profil');
// $isim = Route::currentRouteName(); ile o andaki route name alınabilir
Route::get('/users', 'UserController@listUsers');
Route::get('/user/{id}', 'UserController@viewUser');
Route::get('/user2/{id}', array('before'=>array('auth', 'yas'),'uses'=>'UserController@viewUser'));

//Hem GET hem POST requestlere cevap verebilmek ve alınan parametreleri limitlemek için
Route::match(['GET', 'POST'], '/getpost/{id?}', function ($id = 33541) {
    return 'Beni GET ve POST ile çağırabilirsiniz '.$id;
})
->where(array(
    'id' => '[0-9]+',
    'name' => '[a-z]+'
));
// yada
// Route::pattern('id', '[0-9]+');// şeklinde global pattern tanımlanabilirdi


//Hem GET hem POST requestlere cevap verebilmek için
Route::any('/any', function () {
    return 'Beni herhangi bir request tipi ile çağırabilirsiniz';
});

//Hem GET hem POST requestlere cevap verebilmek için
Route::any(
    '/https',
    array('https',function () {
        return 'Sadece HTTPS isteği ile ulaşılabilir !';
    })
);

//routeları gruplayarak belirli bir filtreyi tamamına uygulayabiliriz , namespace tanımlayabiliriz
Route::group(
    array('before' => 'auth','namespace' => 'Admin'),
    function () {
        Route::get('/dashboard', 'UserController@dashboard');
        Route::get('/administrator', 'UserController@adminDashboard');
    }
);

//routeları gruplarken subdomain tanımlayabiliriz ve prefix ile tüm gruba ön ek ekleyebiliriz
Route::group(
    array(
        'domain' => '{subdomain}.domain.com',
        'prefix' => 'admin',
        'namespace' => 'Admin'
    ),
    function () {
        Route::get('user4/{id}', function($subdomain, $id){

        });
    }
);




// Route lara model bağlayabiliriz , önce modelleri tanımlamamız gerekir
Route::model('user', 'User'/*
function() {
       //404 hatası fırlatmak için
    App::abort(404);
}
*/);
// Aşağıdaki şekilde profile/1 url i ne request geldiğinde id si 1 olan User object route içine aktarılacaktır
Route::get('profile/{user}', function (User $user) {
    // User bilgisini kullan
});
// burada önemli nokta bu id ile database den user object alınamaz ise 404 hatası döndürecek olmasıdır.
// bulunamaması durumunda ne olmasını istediğimizi belirtmek istiyor isek model tanımında üçüncü parametre olarak
// closure belirtmemiz gerekir... yukarıda escape edilerek gösterilmiştir.

// Ayrıca eğer route parametresinin kullanımını farklı şekilde yönetmek için aşağıdaki şekilde bind kullanılabilir
// Örneğin id yerine name kullanarak kullanıcı objesine ulaşmak için ;
Route::bind('/user3', function($email, $route) {
    return User::where('email', $email)->first();
});

// Routerdan direkt view kullanarak response dönebilirz
Route::get('/staticpage', function() {
    return View::make('staticpage', array('isim' => 'Tuana Şeyma')); // app/views/staticpage.php dosyasını döndürür
});


