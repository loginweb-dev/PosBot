@php
    $negocios = App\Business::where("id", ">", 0)->with("owner")->get();
@endphp

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://unpkg.com/onsenui/css/onsenui.css">
  <link rel="stylesheet" href="https://unpkg.com/onsenui/css/onsen-css-components.min.css">
  <script src="https://unpkg.com/onsenui/js/onsenui.min.js"></script>
  <script src="https://unpkg.com/jquery/dist/jquery.min.js"></script>
</head>
<body>
   
  <ons-navigator id="appNavigator" swipeable swipe-target-width="80px">
    <ons-page>
      <ons-splitter id="appSplitter">
        <ons-splitter-side id="sidemenu" page="sidemenu.html" swipeable side="right" collapse="" width="260px"></ons-splitter-side>
        <ons-splitter-content page="tabbar.html"></ons-splitter-content>
      </ons-splitter>
    </ons-page>
  </ons-navigator>
  
  <template id="tabbar.html">
    <ons-page id="tabbar-page">
      <ons-toolbar>
        <div class="center">Inicio</div>
        <div class="right">
          <ons-toolbar-button onclick="fn.toggleMenu()">
            <ons-icon icon="ion-ios-menu, material:md-menu"></ons-icon>
          </ons-toolbar-button>
        </div>
      </ons-toolbar>
      <ons-tabbar swipeable id="appTabbar" position="auto">
        <ons-tab label="Inicio" icon="ion-ios-home" page="home.html" active></ons-tab>
        <ons-tab label="Tiendas" icon="ion-ios-business" page="pullHook.html"></ons-tab>
        <ons-tab label="Carrito" icon="ion-ios-cart" page="forms.html"></ons-tab>
        <ons-tab label="Perfil" icon="ion-ios-person" page="animations.html"></ons-tab>
      </ons-tabbar>
  
      <script>
        ons.getScriptPage().addEventListener('prechange', function(event) {
          if (event.target.matches('#appTabbar')) {
            event.currentTarget.querySelector('ons-toolbar .center').innerHTML = event.tabItem.getAttribute('label');
          }
        });
      </script>
    </ons-page>
  </template>
  
  <template id="sidemenu.html">
    <ons-page>
      <div class="profile-pic">
        <img src="https://monaca.io/img/logos/download_image_onsenui_01.png">
      </div>
  
      <ons-list-title>Access</ons-list-title>
      <ons-list>
        <ons-list-item onclick="fn.loadView(0)">
          <div class="left">
            <ons-icon fixed-width class="list-item__icon" icon="ion-ios-home, material:md-home"></ons-icon>
          </div>
          <div class="center">
            Inicio
          </div>
          <div class="right">
            <ons-icon icon="fa-link"></ons-icon>
          </div>
        </ons-list-item>
        <ons-list-item onclick="fn.loadView(1)">
          <div class="left">
            <ons-icon fixed-width class="list-item__icon" icon="ion-ios-create, material:md-edit"></ons-icon>
          </div>
          <div class="center">
            Forms
          </div>
          <div class="right">
            <ons-icon icon="fa-link"></ons-icon>
          </div>
        </ons-list-item>
        <ons-list-item onclick="fn.loadView(2)">
          <div class="left">
            <ons-icon fixed-width class="list-item__icon" icon="ion-ios-film, material: md-movie-alt"></ons-icon>
          </div>
          <div class="center">
            Animations
          </div>
          <div class="right">
            <ons-icon icon="fa-link"></ons-icon>
          </div>
        </ons-list-item>
      </ons-list>
  
      <ons-list-title style="margin-top: 10px">Links</ons-list-title>
      <ons-list>
        <ons-list-item onclick="fn.loadLink('https://onsen.io/v2/docs/guide/js/')">
          <div class="left">
            <ons-icon fixed-width class="list-item__icon" icon="ion-ios-document"></ons-icon>
          </div>
          <div class="center">
            Docs
          </div>
          <div class="right">
            <ons-icon icon="fa-external-link"></ons-icon>
          </div>
        </ons-list-item>
        <ons-list-item onclick="fn.loadLink('https://github.com/OnsenUI/OnsenUI')">
          <div class="left">
            <ons-icon fixed-width class="list-item__icon" icon="ion-logo-github"></ons-icon>
          </div>
          <div class="center">
            GitHub
          </div>
          <div class="right">
            <ons-icon icon="fa-external-link"></ons-icon>
          </div>
        </ons-list-item>
        <ons-list-item onclick="fn.loadLink('https://community.onsen.io/')">
          <div class="left">
            <ons-icon fixed-width class="list-item__icon" icon="ion-ios-chatboxes"></ons-icon>
          </div>
          <div class="center">
            Forum
          </div>
          <div class="right">
            <ons-icon icon="fa-external-link"></ons-icon>
          </div>
        </ons-list-item>
        <ons-list-item onclick="fn.loadLink('https://twitter.com/Onsen_UI')">
          <div class="left">
            <ons-icon fixed-width class="list-item__icon" icon="ion-logo-twitter"></ons-icon>
          </div>
          <div class="center">
            Twitter
          </div>
          <div class="right">
            <ons-icon icon="fa-external-link"></ons-icon>
          </div>
        </ons-list-item>
      </ons-list>
  
      <script>
        ons.getScriptPage().onInit = function() {
          // Set ons-splitter-side animation
          this.parentElement.setAttribute('animation', ons.platform.isAndroid() ? 'overlay' : 'reveal');
        };
      </script>
  
      <style>
        .profile-pic {
          width: 200px;
          background-color: #fff;
          margin: 20px auto 10px;
          border: 1px solid #999;
          border-radius: 4px;
        }
  
        .profile-pic > img {
          display: block;
          max-width: 100%;
        }
  
        ons-list-item {
          color: #444;
        }
      </style>
    </ons-page>
  </template>
  
  {{-- inicio  --}}
  <template id="home.html">
    <ons-page>
            
      <p class="intro">
        Marketplace y Delivery para Bolivia.<br>Negocios Disponibles<br>
      </p>
      <ons-carousel swipeable auto-scroll overscrollable id="carousel">
        <ons-carousel-item>
          <img src="{{ asset('app/appbanner2.jpg') }}" alt="" style="width: 100%; height: 120px;">
        </ons-carousel-item>
        <ons-carousel-item>
          <ons-carousel-item>
            <img src="{{ asset('app/banner3.jpg') }}" alt="" style="width: 100%; height: 120px;">
          </ons-carousel-item>
        </ons-carousel-item>
      </ons-carousel>
      <style>
        .intro {
          text-align: center;
          padding: 0 20px;
          margin-top: 40px;
        }
  
        ons-card {
          cursor: pointer;
          color: #333;
        }
  
        .card__title,
        .card--material__title {
          font-size: 20px;
        }
      </style>
    </ons-page>
  </template>
  
  {{-- tiendas  --}}
  <template id="pullHook.html">
    <ons-page>
      <p style="text-align: center; margin-top: 10px;">
        <ons-search-input
          placeholder="Search"
          onchange="ons.notification.alert('Searched for: ' + this.value)"
        ></ons-search-input>
      </p>
      @foreach ($negocios as $item)          
        <ons-card onclick="fn.pushPage({'id': 'negocio.html', 'title': '{{ $item->name }}'})">
          <div class="title">{{ $item->name }}</div>
          <div class="content">{{ $item->id.' - '.$item->created_at }}</div>
        </ons-card>
      @endforeach
    </ons-page>
  </template>

  {{-- mi carrito  --}}
  <template id="forms.html">
    <ons-page id="forms-page">
      <ons-list>
       
       
          <ons-list-header style="text-align: center; font-size: 20px;">
            Mi lista de compras

          </ons-list-header>
         

        <ons-list-item>
          <div class="left">
            <img class="list-item__thumbnail" src="https://placekitten.com/g/40/40">
          </div>
          <div class="center">
            <span class="list-item__title">Product 01</span>
            <span class="list-item__subtitle">Precio: 5Bs | Cant: 2 | Total: 10Bs</span>
          </div>
          <div class="right">
            <ons-icon icon="md-delete" class="list-item__icon" size="30px"></ons-icon>
          </div>
        </ons-list-item>



      
      <ons-list-item>
        <div class="left">
          <img class="list-item__thumbnail" src="https://placekitten.com/g/60/60">
        </div>
        <div class="center">
          <span class="list-item__title">Product 02</span>
          <span class="list-item__subtitle">Precio: 8Bs | Cant: 1 | Total: 8Bs</span>
        </div>
        <div class="right">
          <ons-icon icon="md-delete" class="list-item__icon" size="30px"></ons-icon>
        </div>
      </ons-list-item>

      <ons-list-item>
        <div class="left">    
                
        </div>
        <div class="center">
          <ons-input id="" modifier="" placeholder="Tienes cupon de descuento ?" style="width: 100%"></ons-input>
        </div>
        <div class="right">
       
        </div>
      </ons-list-item>

      <ons-list-item>
        <div class="left">          
        </div>
        <div class="center">
        </div>
        <div class="right">
          Descuento: 0Bs
        </div>
      </ons-list-item>

      <ons-list-item>
        <div class="left">          
        </div>
        <div class="center">         
        </div>
        <div class="right">
          Total: 18Bs
        </div>
      </ons-list-item>

      <ons-list-item>
        <div class="left">          
        </div>
        <div class="center">         
        </div>
        <div class="right">
          <ons-button modifier="large" onclick="fn.pushPage({'id': 'pasarela.html', 'title': 'Pasarela'})">Pediar ahora !</ons-button>
        </div>
      </ons-list-item>

    </ons-list>

    </ons-page>
  </template>
  
  {{-- mi perfil  --}}
  <template id="animations.html">
    <ons-page>
      <ons-card>
        <img src="https://monaca.io/img/logos/download_image_onsenui_01.png" alt="Onsen UI" style="width: 100%">
        <div class="title">
          sin datos
        </div>
        <div class="content">
          {{-- <div>
            <ons-button><ons-icon icon="ion-ios-thumbs-up"></ons-icon></ons-button>
            <ons-button><ons-icon icon="ion-ios-share"></ons-icon></ons-button>
          </div> --}}
          <ons-list>
            <ons-list-header>Informacion</ons-list-header>
            <ons-list-item>sin datos</ons-list-item>
            <ons-list-item>sin datos</ons-list-item>
            <ons-list-item>sin datos</ons-list-item>
          </ons-list>
        </div>
      </ons-card>

      <ons-row>
        <ons-col><ons-button modifier="large" onclick="showModal()">Ingresar</ons-button></ons-col>
      </ons-row>
    </ons-page>
  </template>
  
  {{-- pagina negocio --}}
  <template id="negocio.html">
    <ons-page>
      <ons-toolbar>
        <div class="left">
          <ons-back-button>Atras</ons-back-button>
        </div>
        <div class="center"></div>
      </ons-toolbar>
 
      <script>
          ons.getScriptPage().onInit = function () {
            this.querySelector('ons-toolbar div.center').textContent = this.data.title;
          }
      </script>
    </ons-page>
  </template>

  {{-- pasarele --}}
  <template id="pasarela.html">
    <ons-page>
      <ons-toolbar>
        <div class="left">
          <ons-back-button>Atras</ons-back-button>
        </div>
        <div class="center"></div>
      </ons-toolbar>
  
      <script>
          ons.getScriptPage().onInit = function () {
            this.querySelector('ons-toolbar div.center').textContent = this.data.title;
          }
      </script>
    </ons-page>
  </template>

  {{-- login whatsapp --}}
  <ons-modal direction="up">
    <div style="text-align: center">
      <p>         
        <ons-input id="" modifier="" placeholder="Ingresa tu numero de whatsapp" style="width: 90%"></ons-input>
      </p>
      <br>
      <p>Te enviaremos un PIN de acceso a tu whatsapp</p>
      <br>
      <p>
        <ons-icon icon="md-spinner" size="28px" spin></ons-icon> Loading...
      </p>
    </div>
  </ons-modal>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.0/axios.min.js"></script>

  <script>
    window.fn = {};
    window.fn.toggleMenu = function () {
      document.getElementById('appSplitter').right.toggle();
    };
    
    window.fn.loadView = function (index) {
      document.getElementById('appTabbar').setActiveTab(index);
      document.getElementById('sidemenu').close();
    };

    window.fn.loadLink = function (url) {
      window.open(url, '_blank');
    };

    window.fn.pushPage = function (page, anim) {
      if (anim) {
        document.getElementById('appNavigator').pushPage(page.id, { data: { title: page.title }, animation: anim });
      } else {
        document.getElementById('appNavigator').pushPage(page.id, { data: { title: page.title } });
      }
    };

    $(document).ready(function () {
      // minegocios()
    });

    async function minegocios() {
      const config = { headers: { 'Content-Type': 'multipart/form-data', 'Accept': 'application/json', 'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjExNjc1NTNjNWRlMDZmYTkyOGE1MjYyOGZjNjgxYzRlYTM0MzY5YmE2OTBlYTQ2NmMyYzY1YjE5ZGNiYjI4NTUwZjM1YjhkZmIxNWY0YTkzIn0.eyJhdWQiOiIxIiwianRpIjoiMTE2NzU1M2M1ZGUwNmZhOTI4YTUyNjI4ZmM2ODFjNGVhMzQzNjliYTY5MGVhNDY2YzJjNjViMTlkY2JiMjg1NTBmMzViOGRmYjE1ZjRhOTMiLCJpYXQiOjE2NzQ5NDMwNzUsIm5iZiI6MTY3NDk0MzA3NSwiZXhwIjoxNzA2NDc5MDc1LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.ZtO5ruv_P0HuvECUsvKR3gOSVKNUBK87SqQ_K28wzKzKlyVFKYMDZxm8l_I4tYJsVBTik9WnTtNeamJ_Moqr2naEFWMinaCUAamJbfFgSvViGJHCZiwtkkwFput5d3IPDimDt0oFRR4T0rI4r0TLjLnE5SMixCW8AiaoNsXHB5HCfHU9zJM7T1p_gA78ePEGQWt_PPpjobbNFvOoQ5R4by7_T4-QkqkgvIIdD9OL5lNXeTg-DzZDOb1yXclOyt-8EA0Ag4ANcpBIFpqSJ8ETpLbwnQFrQeN8FSKjwyo1cOnqUtMF46aq0Um3etd_mctLhSb-yqtMh6z5_HMD8ccouUHJH2NeMf9uT_tbzipzs0IM1JVXkGttRA82Dxangvb8yNGpNXSFOmwXok6pAv_8liI0l2KiXyHTs8ZHsCsgMALZnTketnMV7HdW_aqTrG4ziLEq7DmBf6xXUq3EztyoGqdDqCBcqlW40l001NmYCsrrPVzzUg5ddOAWPhspGgX25XoXhsReHBGbn1qmhwUPKiFbnDoNoReEdxA-MMdE-X_0V7MnksNKNtYpxYO0PS9WS4umGNkaUvCu7-GqRd22X29K_MVXEnPuH8iTADQTsjPujpvcKpajYVHJJAeYP-f7KEdmPD1kkKMyIYVQclRZtw-RjrmRqK5j3T_d8FxXJCU'} };
      var negocios = await axios.get("/connector/api/active-subscription", config)
      console.log(negocios.data.length)
    }

    function showModal() {
      var modal = document.querySelector('ons-modal');
      modal.show();
      setTimeout(function() {
        modal.hide();
      }, 30000);
    }
  </script>

</body>
</html>