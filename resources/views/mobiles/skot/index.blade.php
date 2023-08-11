@php
    $negocio = App\Business::where("id", 2)->with("owner")->first();
    $productos = App\Product::where("business_id", 2)->where("type", "combo")->get();
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
        <ons-splitter-content page="tabbar.html"></ons-splitter-content>
      </ons-splitter>
    </ons-page>
  </ons-navigator>
  
  <template id="tabbar.html">
    <ons-page id="tabbar-page">
      <ons-toolbar>
        <div class="center">{{ $negocio->name }}</div>
      </ons-toolbar>
      <ons-tabbar swipeable id="appTabbar" position="auto">
        <ons-tab label="Inicio" icon="ion-ios-home" page="home.html" active></ons-tab>
        <ons-tab label="Carrito" icon="ion-ios-cart" page="carrito.html"></ons-tab>
        <ons-tab label="Perfil" icon="ion-ios-contact" page="perfil.html"></ons-tab>
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

  {{-- pagina home --}}
  <template id="home.html">
    <ons-page>
      <ons-carousel swipeable auto-scroll overscrollable id="carousel">
        <ons-carousel-item>
          <img src="{{ asset('app/banner1.jpg') }}" alt="" style="width: 100%; height: 140px;">
        </ons-carousel-item>
        <ons-carousel-item>
          <img src="{{ asset('app/banner2.jpg') }}" alt="" style="width: 100%; height: 140px;">
        </ons-carousel-item>
        <ons-carousel-item>
          <ons-carousel-item>
            <img src="{{ asset('app/banner3.jpg') }}" alt="" style="width: 100%; height: 140px;">
          </ons-carousel-item>
        </ons-carousel-item>
      </ons-carousel>

      @foreach ($productos as $item)          
        <ons-card onclick="fn.pushPage({'id': 'producto.html', 'title': '{{ $item->id }}'})">
        <ons-row>
          <ons-col width="50px">
            <img class="list-item__thumbnail" src="{{ asset('uploads/img/'.$item->image) }}">
          </ons-col>
          <ons-col>
            <div class="title">{{ $item->name }} <span class="notification notification--material">{{ $item->id }}</span></div>
            <div class="content">{!! $item->product_description !!}</div>
          </ons-col>
        </ons-row>
        </ons-card>
      @endforeach
  
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
  

  {{-- pagina carrito --}}
  <template id="carrito.html">
    <ons-page>

      <ons-list id="myList">
        {{-- <ons-list-header>Productos en mi carrito</ons-list-header> --}}
      </ons-list> 
      <script>
          ons.getScriptPage().onInit = function () {
            micart()
          }
      </script>
    </ons-page>
  </template>


  {{-- pagina carrito --}}
  <template id="perfil.html">
    <ons-page>
      <ons-toolbar>
        <div class="left">
          <ons-back-button>Atras</ons-back-button>
        </div>
        <div class="center"></div>
      </ons-toolbar>

      <script>
          ons.getScriptPage().onInit = async function () {
            // this.querySelector('ons-toolbar div.center').textContent = "Mi Perfil"
          }
      </script>
            <style>
              .right-icon {
                margin-left: 10px;
              }
        
              .right-label {
                color: #666;
              }
            </style>
    </ons-page>
  </template>



  {{-- pagina producto --}}
  <template id="producto.html">
    <ons-page>
      <ons-toolbar>
        <div class="left">
          <ons-back-button>Atras</ons-back-button>
        </div>
        <div class="center"></div>
      </ons-toolbar>
      <ons-card>
        <img id="img_product" src="https://monaca.io/img/logos/download_image_onsenui_01.png" alt="Onsen UI" style="width: 100%">
        <div class="title">
          <div id="product_description"></div>
        </div>
        <div class="content">
          <ons-input
            id="micant"
            type="number"
            placeholder="Cantidad"
            min="1"
            max="9"
            value="1">
          </ons-input>
          <ons-button class="button--outline" onclick="createAlertDialog()">Agregar a carrito</ons-button>

          <ons-list id="miextraslist">
            <ons-list-header>Extras</ons-list-header>
            {{-- <ons-list-item tappable>
              <label class="left">
                <ons-checkbox input-id="check-1"></ons-checkbox>
              </label>
              <label for="check-1" class="center">
                Carne
              </label>
            </ons-list-item> --}}
          </ons-list>
        
        </div>
      </ons-card>
      <script>
          ons.getScriptPage().onInit = async function () {
            var midata = await axios.get("{{ env('APP_URL') }}/api/productos/id/"+this.data.title)

            this.querySelector('ons-toolbar div.center').textContent = midata.data.name
            localStorage.setItem("miproducto", JSON.stringify(midata.data));
            document.getElementById("img_product").src = midata.data.image_url
            document.getElementById("product_description").innerHTML = midata.data.product_description
            miextras(midata.data.business_id)
          }
          var createAlertDialog = function() {
            var dialog = document.getElementById('my-alert-dialog');

            if (dialog) {
              dialog.show();
            } else {
              ons.createElement('alert-dialog.html', { append: true })
                .then(function(dialog) {
                  dialog.show();
                });
            }
          };
          var hideAlertDialog = function() {
            document
              .getElementById('my-alert-dialog')
              .hide();
          };

          var add = function() {
            hideAlertDialog()
            var midata = JSON.parse(localStorage.getItem("miproducto"))
            var micart = localStorage.getItem("micart") ? JSON.parse(localStorage.getItem("micart")) : []
            micart.push({
              id: midata.id,
              name: midata.name,
              image: midata.image_url,
              cantidad: document.getElementById("micant").value
            })
            localStorage.setItem("micart", JSON.stringify(micart))
            ons.notification.toast('Agredado exitosamente..!', { timeout: 2000, animation: 'fall' });
            this.micart()
            
          };
      </script>
    </ons-page>
  </template>

  <template id="alert-dialog.html">
    <ons-alert-dialog id="my-alert-dialog" modifier="rowfooter">
      <div class="alert-dialog-title">Dialogo</div>
      <div class="alert-dialog-content">
        Estas seguro ?
      </div>
      <div class="alert-dialog-footer">
        <ons-alert-dialog-button onclick="hideAlertDialog()">Cancel</ons-alert-dialog-button>
        <ons-alert-dialog-button onclick="add()">OK</ons-alert-dialog-button>
      </div>
    </ons-alert-dialog>
  </template>

   <style>
    ons-splitter-side[animation=overlay] {
      border-left: 1px solid #bbb;
    }
  </style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.0/axios.min.js"></script>
<script src="{{ asset('js/cart.js') }}"></script>
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
      // example()
    });
    async function minegocios() {
      const config = { headers: { 'Content-Type': 'multipart/form-data', 'Accept': 'application/json', 'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjExNjc1NTNjNWRlMDZmYTkyOGE1MjYyOGZjNjgxYzRlYTM0MzY5YmE2OTBlYTQ2NmMyYzY1YjE5ZGNiYjI4NTUwZjM1YjhkZmIxNWY0YTkzIn0.eyJhdWQiOiIxIiwianRpIjoiMTE2NzU1M2M1ZGUwNmZhOTI4YTUyNjI4ZmM2ODFjNGVhMzQzNjliYTY5MGVhNDY2YzJjNjViMTlkY2JiMjg1NTBmMzViOGRmYjE1ZjRhOTMiLCJpYXQiOjE2NzQ5NDMwNzUsIm5iZiI6MTY3NDk0MzA3NSwiZXhwIjoxNzA2NDc5MDc1LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.ZtO5ruv_P0HuvECUsvKR3gOSVKNUBK87SqQ_K28wzKzKlyVFKYMDZxm8l_I4tYJsVBTik9WnTtNeamJ_Moqr2naEFWMinaCUAamJbfFgSvViGJHCZiwtkkwFput5d3IPDimDt0oFRR4T0rI4r0TLjLnE5SMixCW8AiaoNsXHB5HCfHU9zJM7T1p_gA78ePEGQWt_PPpjobbNFvOoQ5R4by7_T4-QkqkgvIIdD9OL5lNXeTg-DzZDOb1yXclOyt-8EA0Ag4ANcpBIFpqSJ8ETpLbwnQFrQeN8FSKjwyo1cOnqUtMF46aq0Um3etd_mctLhSb-yqtMh6z5_HMD8ccouUHJH2NeMf9uT_tbzipzs0IM1JVXkGttRA82Dxangvb8yNGpNXSFOmwXok6pAv_8liI0l2KiXyHTs8ZHsCsgMALZnTketnMV7HdW_aqTrG4ziLEq7DmBf6xXUq3EztyoGqdDqCBcqlW40l001NmYCsrrPVzzUg5ddOAWPhspGgX25XoXhsReHBGbn1qmhwUPKiFbnDoNoReEdxA-MMdE-X_0V7MnksNKNtYpxYO0PS9WS4umGNkaUvCu7-GqRd22X29K_MVXEnPuH8iTADQTsjPujpvcKpajYVHJJAeYP-f7KEdmPD1kkKMyIYVQclRZtw-RjrmRqK5j3T_d8FxXJCU'} };
      var negocios = await axios.get("/connector/api/active-subscription", config)
      console.log(negocios.data.length)
    }

    function micart(){
      var micart = JSON.parse(localStorage.getItem("micart"))
      $('#myList ons-list-item').remove()
      for (let index = 0; index < micart.length; index++) {
        $('#myList').append("<ons-list-item><div class='left'><img class='list-item__thumbnail' src='"+micart[index].image+"'></div><div class='center'>"+micart[index].name+"<br>cant: "+micart[index].cantidad+"</div><div class='right'><ons-icon style='color: red;' size='20px' icon='fa-trash'></ons-icon></div></ons-list-item>");
      }
    }
    async function miextras(id){
      var midata  = await axios.get("{{ env('APP_URL') }}/api/extras/get/"+id)
      console.log(midata.data)
      $('#miextraslist ons-list-item').remove()
      for (let index = 0; index < midata.data.length; index++) {
        $('#miextraslist').append("<ons-list-item tappable><label class='left'>"+midata.data[index].name+"</label><ons-checkbox input-id='check-1'></ons-checkbox></ons-list-item>");
      }
    }
  </script>
</body>
</html>