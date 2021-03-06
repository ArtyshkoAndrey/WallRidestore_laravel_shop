<header>

  @widget('left-menu')

  <nav class="navbar navbar-expand navbar-light bg-dark">
    <div class="container-fluid">
      <div class="collapse navbar-collapse align-items-center justify-content-center" id="main-menu">
        <ul class="navbar-nav w-100 px-0 px-lg-4">

          <li class="nav-item d-lg-none d-block">
            <div id="nav-toggle">
              <span></span>
              <span></span>
              <span></span>
            </div>
          </li>

          <li class="nav-item dropdown d-none d-lg-flex">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="currencyDropdown"
              role="button"
              data-mdb-toggle="dropdown"
              aria-expanded="false"
            >
              @{{ $store.state.currency.short_name ? $store.state.currency.short_name : 'Загрузка' }}
            </a>
            <ul class="dropdown-menu p-4" aria-labelledby="currencyDropdown">
              @foreach(\App\Models\Currency::all() as $currency)
                <li>

                  <button
                    @click="$store.dispatch('set_currency', { currency: {{$currency}} })"
                    class="dropdown-item px-0"
                    v-bind:class="$store.state.currency.id === {{ $currency->id }} ? 'active' : '' ">
                    {{ $currency->short_name }}
                  </button>

                </li>
              @endforeach
            </ul>
          </li>

          <li class="nav-item dropdown d-none d-lg-flex">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="languageDropdown"
              role="button"
              data-mdb-toggle="dropdown"
              aria-expanded="false"
            >
              {{ isset($_COOKIE['language']) ? $_COOKIE['language'] == 'ru' ? 'RUS' : 'ENG' : 'RUS' }}
            </a>
            <ul class="dropdown-menu p-4" aria-labelledby="languageDropdown">
              <li>
                <a
                  class="dropdown-item px-0 active"
                  href="{{ url('/language/change/ru') }}">
                  {{ __('Русский') }}
                </a>
              </li>

              <li>
                <a
                  class="dropdown-item px-0"
                  href="{{ url('/language/change/en') }}">
                  {{ __('English') }}
                </a>
              </li>
            </ul>
          </li>

          <li class="divider d-none d-lg-block"></li>

          <li class="nav-item dropdown d-flex" id="search-nav-item">
            <a
              class="nav-link dropdown-toggle not-after"
              href="#"
              id="searchDropdown"
              role="button"
              data-mdb-toggle="dropdown"
              aria-expanded="false"
            >
              <i class="far fa-search d-block"></i>
            </a>
            <ul class="dropdown-menu p-4" aria-labelledby="searchDropdown">
              <li class="dropdown-item px-0">
                <form action="{{ route('product.search') }}" class="h-100" method="GET">
                  <div class="row h-100 m-0">
                    <div class="col-9 offset-1 px-0">
                      <input type="text" id="search" name="q" class="w-100" placeholder="{{ __('Название товара, бренд категория') }}" />
                    </div>
                    <div class="col-1 px-0">
                      <button class="btn btn-dark shadow-none w-100 h-100 h4 align-items-center justify-content-center d-flex"><i class="far fa-search"></i></button>
                    </div>
                  </div>
                </form>
              </li>
            </ul>
          </li>

{{--      LOGo Image    --}}
          <li class="nav-item mx-auto">
            <a class="navbar-brand" href="{{ route('index') }}">
              <img src="{{ asset('images/logo.svg') }}" class="logo" alt="wallridestore">
            </a>
          </li>

          <li class="nav-item dropdown d-flex">
            <a
              class="nav-link nav-link-end dropdown-toggle not-after"
              href="#"
              id="userDropdown"
              role="button"
              data-mdb-toggle="dropdown"
              aria-expanded="false"
            >
              <i class="far fa-user"></i>
            </a>
            @guest
              <ul class="dropdown-menu dropdown-menu-end p-4" aria-labelledby="userDropdown">
                <li class="dropdown-item px-0">
                  <a href="{{ route('login') }}" class="text-gray-1">{{ __('Вход') }}</a>
                </li>
                <li class="dropdown-item px-0">
                  <a href="{{ route('register') }}" class="text-gray-1">{{ __('Регистрация') }}</a>
                </li>
              </ul>
            @else
              <div class="dropdown-menu dropdown-menu-end p-4 w-auto" aria-labelledby="userDropdown">
                <div class="row">
                  <div class="col-12">
                    <div class="row">
                      <a href="{{ route('profile.index') }}" class="d-flex dropdown-item">
                        <div class="col-2 d-flex align-items-center justify-content-center">
                          <img src="{{ auth()->user()->user_image }}" alt="logo" class="rounded-circle" style="width: 25px; height: 25px;">
                        </div>
                        <div class="col-auto mx-2">
                          {{ auth()->user()->name }}
                        </div>
                      </a>
                    </div>
                    <div class="row">
                      <a href="{{ route('order.index') }}" class="d-flex dropdown-item">
                        <div class="col-2 d-flex align-items-center justify-content-center">
                          <i class="far fa-stream"></i>
                        </div>
                        <div class="col-auto mx-2">
                          {{ __('Мои заказы') }}
                        </div>
                      </a>
                    </div>
                    @if(auth()->user()->is_admin)
                      <div class="row">
                        <a href="{{ route('admin.index') }}" class="d-flex dropdown-item">
                          <div class="col-2 d-flex align-items-center justify-content-center">
                            <i class="fad fa-truck-loading"></i>
                          </div>
                          <div class="col-auto mx-2">
                            Администитивная панель
                          </div>
                        </a>
                      </div>
                    @endif
                    <div class="row">
                      <a href="#" onclick="event.preventDefault();$('#logout').submit()" class="d-flex dropdown-item">
                        <div class="col-2 d-flex align-items-center justify-content-center">
                          <i class="fad fa-sign-out-alt"></i>
                        </div>
                        <div class="col-auto mx-2">
                          {{ __('Выйти') }}
                        </div>
                      </a>
                      <form action="{{ route('logout') }}" id="logout" method="POST" class="d-none">
                        @csrf
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            @endguest
          </li>

          <li class="divider d-none d-lg-block"></li>

          <li class="nav-item dropdown d-none d-lg-flex">
            <a
              class="nav-link"
              href="{{ route('product.favor') }}"
            >
              <i class="far fa-heart"></i>
            </a>
          </li>

          <li class="nav-item dropdown">
            <a
              class="nav-link nav-link-end dropdown-toggle not-after"
              href="#"
              id="cartDropdown"
              role="button"
              data-mdb-toggle="dropdown"
              aria-expanded="false"
            >
              <i class="far fa-shopping-bag"></i>
              <span class="badge rounded-pill badge-notification bg-danger text-black">
                @{{ $store.state.cart.items.reduce((a, b) => +a + +b.amount, 0) }}
              </span>
            </a>
            <div class="dropdown-menu full-width dropdown-menu-end p-4">

              <div class="row mx-0 mb-3 mb-sm-2" v-for="product in $store.getters.productsCart" v-if="product">
                <div class="col-sm-2 col-3 d-flex align-items-center p-0 pb-2">
                  <img :src="product.thumbnail_jpg" :alt="product.title" class="img-fluid">
                </div>

                <div class="col-9 col-sm-10 pb-2">
                  <div class="row align-items-center justify-content-between h-100">

                    <div class="col-6 col-sm-5 order-1 d-flex align-self-stretch align-self-sm-auto">
                      <p class="m-0 font-weight-bold product-name">
                        @{{ product.title }} -  @{{ product.skus.skus.title }}
                      </p>
                    </div>

                    <div class="col-7 col-sm-auto order-3 order-sm-2 ml-sm-auto mt-auto mt-sm-0">
                      <p class="m-0 font-weight-normal product-price">
                        @{{ $cost( (product.on_sale ? product.price_sale : product.price) * $store.state.currency.ratio) }} @{{ $store.state.currency.symbol }}
                      </p>
                    </div>

                    <div class="col-5 col-sm-auto order-4 order-sm-3 d-flex justify-content-between align-items-center mt-auto mt-sm-0">
                      <button type="button" class="btn btn-dark p-2"
                              onclick="event.stopPropagation();"
                              @click="$store.commit('addItem', {id: product.item.id, amount: -1 })">
                        <i class="far fa-minus"></i>
                      </button>
                      <p class="mx-2 my-auto">
                        @{{ product.item.amount }}
                      </p>
                      <button type="button" class="btn btn-dark p-2"
                              onclick="event.stopPropagation();"
                              @click="$store.commit('addItem', {id: product.item.id, amount: 1 })">
                        <i class="far fa-plus"></i>
                      </button>
                    </div>

                    <div class="col-6 col-sm-auto order-2 order-sm-4 d-flex align-items-start justify-content-end align-self-stretch align-self-sm-auto">
                      <button type="button"
                              class="p-0 btn bg-transparent shadow-0 border-0 text-danger"
                              onclick="event.stopPropagation();"
                              @click="$store.commit('removeItem', product.item.id)">
                        <i class="far fa-trash h5"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row align-items-center flex-wrap-reverse justify-content-between mt-3">

                <div class="col-12 col-md-6 d-flex d-md-block justify-content-between ml-2 mb-3 mb-md-0 text-left">
                  <div class="row m-0">
                    <div class="col-6 col-12 d-flex justify-content-start align-items-center p-0">
                      <p class="h5 font-weight-bold mb-1">
                        @{{ $cost($store.getters.priceAmount) }} @{{ $store.state.currency.symbol }}
                      </p>
                    </div>
                    <div class="col-12 d-flex justify-content-start p-0">
                      <button class="bg-transparent border-0 text-decoration-none p-0"
                              onclick="event.stopPropagation();"
                              @click="$store.commit('clearCart')">
                        {{ __('Очистить корзину') }}
                      </button>
                    </div>
                  </div>
                </div>

                <div class="col-12 order-first order-md-last col-md-5 d-flex align-items-end align-self-start p-0">
                  <a href="{{ route('cart.index') }}" class="btn btn-dark py-3 w-100">{{ __('Перейти в корзину') }}</a>
                </div>
              </div>

            </div>
          </li>

        </ul>
      </div>
    </div>
  </nav>

  @widget('sub-header')
</header>
