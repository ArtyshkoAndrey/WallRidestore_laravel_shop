@extends('layouts.app')
@section('title', 'Оформление заказа')

@section('content')
  <section class="container mt-5 pt-5 mb-5" id="cart">
    <create-order :amount="{{ $amount }}" :express_companies="{{ App\Models\ExpressCompany::where('name', '!=', 'Самовывоз')->get() }}" :cart_items="{{ json_encode($cartItems) }}" inline-template>
      <div class="row">
        <div class="col-12">
          <div class="row">
            <div class="col-12 col-md-6">
              <h1 class="font-weight-bold">Оформление заказа</h1>
            </div>
            <div class="col-12 mb-2 mb-md-0 col-md-6 text-left text-md-right">
              <h2 v-if="step === 1"><span class="c-red" style="border-bottom: 3px solid #F33C3C">Шаг 1</span> <span class="ml-3" style="color: #A3A3A3">Шаг 2</span></h2>
              <h2 v-else><span @click="step = 1" style="color: #A3A3A3; cursor: pointer">Шаг 1</span> <span class="c-red ml-3" style="border-bottom: 3px solid #F33C3C" >Шаг 2</span></h2>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card">
            <div class="card-body mt-2" v-if="step === 1">
              <h4 class="font-weight-bold">Контактные данные</h4>
              <input type="text" value="{{ auth()->user()->name }}" v-model="order.name" name="name" class="w-100 py-2 px-2 mt-2" placeholder="Имя">
              <input type="text" value="{{ auth()->user()->email }}" v-model="order.email" name="email" class="w-100 py-2 px-2 mt-2" placeholder="E-mail">
              <input type="text" value="{{ auth()->user()->address !== null ? auth()->user()->address->contact_phone !== null ? auth()->user()->address->contact_phone : '' : '' }}" v-model="order.phone" name="contact_phone" class="w-100 py-2 px-2 mt-2" placeholder="Телефон">
            </div>
            <div class="card-body mt-2" v-else>
              <h4 class="font-weight-bold">Адрессные данные</h4>
              <input type="text" value="{{ auth()->user()->address !== null ? auth()->user()->address->country !== null ? auth()->user()->address->country : '' : ''}}" v-model="order.country" name="country" class="w-100 py-2 px-2 mt-2" placeholder="Страна">
              <input type="text" value="{{ auth()->user()->address !== null ? auth()->user()->address->city !== null ? auth()->user()->address->city : '' : ''}}" v-model="order.city" name="city" class="w-100 py-2 px-2 mt-2" placeholder="Город">
              <input type="text" value="{{ auth()->user()->address !== null ? auth()->user()->address->street !== null ? auth()->user()->address->street : '' : ''}}" v-model="order.street" name="street" class="w-100 py-2 px-2 mt-2" placeholder="Адрес">
            </div>
          </div>
        </div>
        <div class="col-12 col-md-8 mt-3 mt-md-0">
          <div class="card">
            <div class="card-body" v-if="step === 1">
              @forelse($cartItems as $item)
                <div class="row mt-3 justify-content-center align-items-center">
                  <div class="col-md-2 offset-md-1 col-4">
                    <img src="{{ $item->productSku->product->imageurl }}" class="img-fluid" alt="{{ $item->productSku->product->title }}">
                  </div>
                  <div class="col-4 col-md-4">
                    {{ ucwords(strtolower($item->productSku->product->title)) }}
                    <br>
                    <p class="text-muted font-small">Размер: {{ $item->productSku->title }}</p>
                  </div>
                  <div class="col-4 col-md text-center font-weight-bold">
                    {{ cost(round($item->productSku->price * $currency->ratio, 0)) }} {{ $currency->symbol }} X {{ $item->amount }}
                  </div>
                </div>
              @empty
                <h3 class="font-weight-bold mt-3">Корзина пуста</h3>
              @endforelse
            </div>

            <div class="card-body mt-3 mt-sm-0" v-else>
              <div class="row p-2">
                <div class="col-md-6">
                  <h5 class="font-weight-bold">Методы доставки</h5>
                  <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-white border-0 rounded-0 p-3" @click="() => {order.pickup = false; order.payment_method = 'card'}">
                      <input type="radio" name="express_company_pickup" id="option1" autocomplete="off" :checked="order.pickup === false"> <i class="fal fa-truck"></i> Курьером
                    </label>
                    <label class="btn btn-white border-0 rounded-0 p-3 ml-2" @click="() => {order.pickup = true}">
                      <input type="radio" name="express_company_pickup" id="option2" autocomplete="off" :checked="order.pickup === true"> <i class="fal fa-shopping-basket"></i> Самовывоз
                    </label>
                  </div>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                  <h5 class="font-weight-bold">Выберите службу доставки</h5>
                  <div class="btn-group btn-group-toggle" data-toggle="buttons" v-if="!order.pickup">
                      <label v-for="company in express_companies" class="btn btn-white border-0 rounded-0 p-3 ml-2" :disabled="order.pickup" @click="() => {!order.pickup ? order.express_company = company.id : null}">
                        <input type="radio" :value="company.id" name="express_company" id="option3" autocomplete="off" :checked="order.express_company === company.id">
                        @{{ company.name }}
                      </label>
<!--                     <label class="btn btn-white border-0 rounded-0 p-3 ml-2" :disabled="order.pickup" @click="() => {!order.pickup ? order.express_company = 'ems' : null}">
                      <input type="radio" value="ems" name="express_company" id="option4" autocomplete="off" :checked="order.express_company === 'ems'"> EMS
                    </label> -->
                  </div>
                  <div v-else class="mt-3">
                    <p class="m-0 p-0 d-flex">В самовывозе нет служб доставки</p>
                  </div>
                </div>
                <div class="col-md-8 mt-3">
                  <h5 class="font-weight-bold">Как будете платить?</h5>
                  <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-white border-0 rounded-0 p-3" @click="() => {!order.pickup ? order.payment_method = 'card' : null}">
                      <input type="radio" value="card" name="payment_method" id="option5" autocomplete="off" :checked="order.payment_method === 'card'"> <i class="fal fa-credit-card-front"></i> Оплатить онлайн
                    </label>
                    <label class="btn btn-white border-0 rounded-0 p-3 ml-2" :disabled="!order.pickup" @click="() => {order.pickup ? order.payment_method = 'cash' : null}">
                      <input type="radio" value="cash" name="payment_method" id="option6" autocomplete="off" :checked="order.payment_method === 'cash'"> <i class="fad fa-coins"></i> Наличными в магазине
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 mt-3">
          <div :class="(step === 1 ? 'justify-content-end' : 'justify-content-between') + ' row'">
            <div class="col-sm-auto col-12 ml-2 ml-sm-0" v-if="step === 2">
              <h4>Общая сумма {{ cost(round($priceAmount * $currency->ratio, 0)) }} {{ $currency->symbol }}</h4>
            </div>
            <div class="col-sm-auto col-12 mt-2 mt-sm-0">
              <button v-if="step === 1" class="btn btn-dark" @click="ordered" id="offer-payment">Следующий шаг</button>
              <button v-else class="btn btn-dark" @click="ordered" id="offer-payment">Завершить оформление заказа и оплатить</button>
            </div>
          </div>
        </div>
      </div>
    </create-order>
  </section>
@endsection

@section('scriptsAfterJs')

@endsection