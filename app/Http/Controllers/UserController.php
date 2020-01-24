<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{

    public function __construct()
    {
        //Controller filtrelerinin kullanımı
        $this->beforeFilter('auth', array('except' => 'getGiris')); // closure ile de belirtilebilir
        $this->beforeFilter('@filterRequests'); // bir method da kullanılabilir
        // bu durumda public function filterRequests($route, $request) gibi bir method yazılmalıdır
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->afterFilter('log', array(
            'only' => array('falancaMetod', 'filancaMetod')
        ));
    }

    public function viewUser()
    {
        die("Kullanıcı detay bilgileri");

        // Loglama işlemleri
        // debug, info,notice, warning, error, critical ve alert olabilir
        Log::info('İşte bu yararlı bir bilgidir.');
        Log::info('Günce mesajı', array('context' => 'Diğer yardımcı bilgi'));
        Log::warning('Yanlış giden bir şeyler olabilir.');
        Log::error('Gerçekten yanlış giden bir şey var.');
    }

    public function listUsers()
    {

//         GET input bilgilerine erişilmesi
        $ismi = Input::get('name', 'Meral'); // get ile alınan isim null ise Meral default değeri döndürülür
        Input::has('name'); // var olup olmadığı kontrolü
        $all = Input::all(); // tümünü al
        $some = Input::only('email', 'password'); // seçilenleri al
        $except = Input::except('creditCardNo'); // seçilenlerin dışındakileri al
        $input = Input::get('products.0.name'); // array olan inputların alt indexlerine . ile erişilebilir


//        Cookie bilgilerine erişilmesi
        $deger = Cookie::get('ismi');
        //Response a yeni çerez eklenmesi
        $yanit = Response::make('Merhaba Dünya');
        $yanit->withCookie(Cookie::make('ismi', 'degeri', $dakika));
        Cookie::queue($name, $value, $minutes);// Yada cevap oluşturulmadan eklenecek cookie kuyruğuna ekleyin
        $cerez = Cookie::forever('ismi', 'degeri'); // Süresiz cookie oluşturmak için
        Input::flash(); // önceki request bilgilerinin sessiona geçici olarak taşınması için
        Input::flashOnly('kullaniciadi', 'email'); // sadece belirtilen request değerlerin taşınması için
        Input::flashExcept('sifre'); // belirtilen hariç diğerlerinin taşınması için
        // flash işlemi genellikle redirect ile kullanıldığından birleştirilebilir. Örneğin ;
        // return Redirect::to('form')->withInput(Input::except('sifre')); // Input::except('sifre') opsiyonel
        Input::old('kullaniciadi'); // ile önceki request bilgisine erişilebilir
        $dosya = Input::file('foto'); // post ile gönderilen bir dosyanın bilgisine erişim
        if (Input::hasFile('foto')) true; // kontrolü için
        if (Input::file('foto')->isValid()) true; // geçerlilik kontrolü
        Input::file('foto')->move($hedefDizinPatikasi, $dosyaAdi); // dosyanın temp ten taşınması
        $patika = Input::file('foto')->getRealPath(); // path i almak için
        $name = Input::file('foto')->getClientOriginalName(); // asıl filename i almak için


//        Diğer bilgiler
        $uri = Request::path(); // istek URI sini döndürür
        $segment = Request::segment(1); // URI nin belirli bir kısmının öğrenilmesi
        Request::isMethod('post')); // post isteği ise true döndürür
        Request::is('admin/*'); // request url admin/... şeklinde ise true döndürür
        $url = Request::url(); // istek url i
        $deger = Request::header('Content-Type'); // request header bilgisine erişim
        $deger = Request::server('PATH_INFO'); // server değişkenlerine erişim
        if (Request::secure()) echo "HTTPS Request"; // HTTPS istek olup olmadığı kontrolü
        if (Request::ajax()) echo "Ajax req"; // AJAX isteği kontrolü
        if (Request::isJson()) echo "Content type is JSON"; // Json header kontrolü
        if (Request::wantsJson()) true; // Response un Json istenip istenmediği
        // yada HTTP Accept header bilgisine bakarak dönüş formatını incelemek için ;
        // if (Request::format() == 'json')

//        Response lar
        $cevap = Response::make($contents, $statusCode);
        $cevap->header('Content-Type', $deger);
        return $cevap;

        // view ile beraber response methodlarını kullanmak için
        return Response::view('hello')->header('Content-Type', $type);

        //cookie ile response üretmek  için
        $cerez = Cookie::make('isim', 'deger');
        return Response::make($content)->withCookie($cerez);

        //dosya download response u üretmek için
        return Response::download($indirilecekDosyaYolu, $isim, $basliklar);

//        Redirect işlemleri
        return Redirect::to('uye/giris'); // yönlendirme
        return Redirect::route('giris'); // isimlendirilmiş route a yönlendirme
        return Redirect::route('profil', array('uye' => 1)); // parametre ile
        return Redirect::to('uye/giris')->with('mesaj', 'Giriş başarısız!'); // flash verisi ile yönlendirme
        return Redirect::action('UserController@profil', array('uye' => 1)); // başka bir controllera yönlendirme



        // URL generate etmek için
        $pageUrl = action('pageController@metodAdi');

//        Authorize ve login işlemleri
        // Default tanımlı olan Bcrypt ile bir şifreyi hashlemek için
        $parola = Hash::make('secret');
        // alınan şifreyi doğrulamak için
        if (Hash::check('secret', $karistirilmisParola)) true;
        // Kullanıcı doğrulaması için standart kullanım
        if (Auth::attempt(array('email' => $email, 'password' => $parola, 'aktif' => 1), true)) {
            // aktif => 1 ek parametredir ve email alanı yerine default username kullanılır
            // son parametre true süresiz session içindir
            return Redirect::intended('pano'); // başarısız işlemde bir önceki url e yada yok ise pano ya gidecektir
        }

        // diğer controllerlarda login olup olmadığının test için
        if (Auth::check()) true;

        // oturum açmış kullanıcının email bilgisine ulaşmak için
        $email = Auth::user()->email;

        // login işlemi dışında kullanıcı kimlik bilgilerinin geçerliliğini kontrol için
        if (Auth::validate($kimlikbilgileri)) true;

        // session açmadan tek bir request için authorize etmek istersek
        if (Auth::once($kimlikbilgileri)) true;

        // logout için
        Auth::logout();

        // programatik olarak bir kullanıcıyı login etmek istersek
        $uye = Uye::find(1);
        Auth::login($uye);


// CSRF koruması
//        Aşağıdaki gibi bir csrf token forma eklenmelidir
/*        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">*/

        // bu token route da aşağıdaki şekilde kontrol edilebilir
        //        Route::post('register', array('before' => 'csrf', function()
        //        {
        //            return 'Geçerli bir CSRF jetonu verdiniz!';
        //        }));

        //HTTP Basic Kimlik Doğrulaması, kullanıcıları özel bir “giriş” sayfası açmadan uygulamanıza giriş
        //yapabilmeleri için hızlı bir yoldur. Bunun için, rotanıza auth.basic filtresi tutturun:
        /*
            Route::get('profil', array('before' => 'auth.basic', function()
            {
                // Sadece kimliği doğrulanmış üyeler girebilir...
            }));
        */
        //Ayrıca bu işlemin PHP FastCGI ile problemsiz çalışması için aşağıdaki satırlar .htaccess e eklenmelidir
        //RewriteCond %{HTTP:Authorization} ^(.+)$
        //RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

        // mcrypt extensionı ile AES-256 şifrelenebilir , çözülebilir
        $kriptolu = Crypt::encrypt('secret'); //şifreleme kullanmak için
        $cozuk = Crypt::decrypt($kriptolu); //çözmek için
        Crypt::setMode('crt'); // mod set etmek için
        Crypt::setCipher($cipher); // cipher set etmek için


//        Cache işlemleri
        Cache::put('anahtar', 'değer', $dakika);
        $sonZaman = Carbon::now()->addMinutes(10); // Bir Öğeyi Önbelleğe Koymak
        Cache::put('anahtar', 'değer', $sonZaman); // Carbon ile Son Kullanım Zamanını Ayarlamak
        Cache::add('anahtar', 'değer', $dakika); // Eğer Öğe Önbellekte Yoksa, Öğeyi Önbelleğe Koymak
        if (Cache::has('anahtar')) true; // Öğenin Önbellekte Var Olup Olmadığını Kontrol Etmek
        $value = Cache::get('anahtar'); Önbellekten Bir Öğeyi Almak
        // Bir Önbellek Değeri Almak
        $value = Cache::get('anahtar', 'varsayılanDeğer');
        // Bir Önbellek Değeri Almak Veya Varsayılan Bir Değer Döndürmek
        $value = Cache::get('anahtar', function() { return 'varsayılanDeğer'; });
        Cache::forever('anahtar', 'değer'); // Bir Öğeyi Önbelleğe Kalıcı Olarak Koymak
        Cache::remember('kullanicilar', $dakika, 'default'); //öğe yok ise default değeri atar ve döndürür
        Cache::rememberForever('kullanicilar', $dakika, 'default'); //öğe yok ise default değeri kalıcı olarak atar ve döndürür
        Cache::forget('anahtar'); // cachten bir değeri silmek
        Cache::increment('anahtar'); // değeri bir artırmak ... decrement azaltır
        Cache::increment('anahtar', $miktar); // değeri miktar kadar artırmak

        //Ayrıca cacheleri tagleyebilir tag bazlı silme vs işlemleri yapabilirsiniz. Örneğin ;
        Cache::tags('insanlar', 'yazarlar')->put('Can', $can, $dakika);
        Cache::tags(array('insanlar', 'artistler'))->put('Mine', $mine, $dakika);
        // Kullanımı ;
        $mine = Cache::tags('insanlar', 'artistler')->get('Mine');
        $can = Cache::tags(array('insanlar', 'yazarlar'))->get('Can');










        // User modelinden tüm userları çekmek için 
        $users = \App\Model\User::all();
//        var_dump($data);die();
        $data['users'] = $users;
        $data['text2'] = Config::get('database.default');
        $data['text'] = Config::get('app.timezone', 'CET');
        Config::set('app.timezone', 'CET');
        $data['text'] = Config::get('app.timezone');

        return \view('userList', $data);
    }
}
