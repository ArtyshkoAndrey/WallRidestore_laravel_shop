@extends('admin.layouts.app')
@section('title', 'Редактирование заказа ' . $order->user->name)

@section('content')
  <div class="container-fluid pt-5 px-4">
    <div class="row">
      <div class="col-12">
        <h2>Заказ</h2>
      </div>
    </div>
    <div class="row mt-2" style="z-index: 100">
      <div class="col-sm-auto ml-0 pl-0 col-6 px-0 pr-sm-2"><a href="{{ route('admin.store.order.index') }}" class="bg-white px-3 py-2 d-block">Заказы</a></div>
      <div class="col-sm-auto col-6 px-0 px-sm-2"><a href="{{ route('admin.store.coupon.index') }}" class="bg-dark px-3 py-2 d-block">Промокоды</a></div>
      <div class="col-sm-auto col-6 px-0 px-sm-2"><a href="{{ route('admin.store.express.index') }}" class="bg-dark px-3 py-2 d-block">Доставка</a></div>
      <div class="col-sm-auto col-6 px-0 px-sm-2"><a href="{{ route('admin.store.order.index') }}" class="bg-dark px-3 py-2 d-block">Оплата</a></div>
    </div>
    <div class="row mt-0 pt-0">
      <div class="card border-0 w-100 rounded-0" style="z-index: 90;box-shadow: 0 18px 19px rgba(0, 0, 0, 0.25)">
        <div class="card-header">
          <div class="row">
            <div class="col-auto">
              <a href="{{ url()->previous() }}" class="h4 d-flex align-content-center"><i class="fal fa-long-arrow-left mr-2"></i> Назад</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.store.order.update', $order->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row justify-content-between">
              <div class="col-auto">
                <h3>Заказ № {{ $order->no }}</h3>
              </div>
              <div class="col-auto">
                <button class="btn btn-dark rounded-0 border-0 px-3 py-2" type="submit">Обновить</button>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-lg-3 col-md-6">
                <div class="row">
                  <div class="col-12">
                    <label for="created_at">Дата заказа</label>
                  </div>
                  <div class="col-12">
                    <input type="datetime-local" name="created_at" id="created_at" class="form-control w-auto" value="{{ $order->created_at->format('Y-m-d\TH:i') }}" required>
                  </div>
                </div>

                <div class="row mt-2">
                  <div class="col-12">
                    <label for="ship_status">Стутас заказа</label>
                  </div>
                  <div class="col-12">
                    <select name="ship_status" id="ship_status" class="form-control w-auto" required>
                      @foreach(\App\Models\Order::SHIP_STATUS_MAP as $ship)
                        <option value="{{ $ship }}" {{ $order->ship_status === $ship ? 'selected' : '' }}>{{ \App\Models\Order::$shipStatusMap[$ship] }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="row mt-2">
                  <div class="col-12">
                    <label for="user">Клиент</label>
                  </div>
                  <div class="col-12">
                    <select name="user" id="user" class="form-control w-auto" required>
                      @foreach(\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}" {{ $order->user->id === $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="row mt-2">
                  <div class="col-12">
                    <label for="express_no">Трек номер</label>
                  </div>
                  <div class="col-12">
                    <input type="text" name="express_no" id="express_no" class="form-control" value="{{ $order->ship_data['express_no'] ? $order->ship_data['express_no'] : '' }}">
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="row">
                  <div class="col-12">
                    <label for="address">Платежный адрес</label>
                  </div>
                  <div class="col-12">
                    <p id="address" class="text-muted">{{ $order->address['address'] }}</p>
                  </div>
                </div>

                <div class="row mt-2">
                  <div class="col-12">
                    <label for="email">Email</label>
                  </div>
                  <div class="col-12">
                    <p id="email" class="text-red">{{ $order->user->email }}</p>
                  </div>
                </div>

                <div class="row mt-2">
                  <div class="col-12">
                    <label for="phone">Телефон</label>
                  </div>
                  <div class="col-12">
                    <p id="phone" class="text-red">{{ $order->address['contact_phone']}}</p>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="row">
                  <div class="col-12">
                    <label for="address">Доставка</label>
                  </div>
                  <div class="col-12">
                    <p id="address" class="text-muted">{{ $order->address['address'] }}</p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12">
                    <label for="express_company">Способ доставки</label>
                  </div>
                  <div class="col-12">
                    <p id="express_company" class="text-muted">{{$order->expressCompany->name }}</p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12">
                    <label for="total_amount">Сумма доставки</label>
                  </div>
                  <div class="col-12">
                    <p id="total_amount" class="text-muted">{{ $order->total_amount }} тг. ЗАМЕНИТЬ</p>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="row">
                  <div class="col-12">
                    <label for="payment_method">Детали оплаты</label>
                  </div>
                  <div class="col-12">
                    <p id="payment_method" class="text-muted">{{ \App\Models\Order::$paymentMethodsMap[$order->payment_method] }}</p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12">
                    <label for="total_amount">Сумма без доставки</label>
                  </div>
                  <div class="col-12">
                    <p id="total_amount" class="text-muted">{{ $order->total_amount }} тг.</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12"><h5 class="font-weight-bold">Позиции заказа</h5></div>
              <div class="col-12 px-md-4 px-0 mt-3">
                <table class="table text-nowrap table-responsive">
                  <thead align="center">
                    <tr>
                      <th class="border-top-0"></th>
                      <th class="border-top-0">Товар</th>
                      <th class="border-top-0">Цена</th>
                      <th class="border-top-0">Кол-во</th>
                      <th class="border-top-0">Итого</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($order->items as $item)
                    <tr align="center">
                      <td style="width:10%; vertical-align: middle;"><img src="{{ $item->product->image_url }}" height="100px" alt=""></td>
                      <td style="width:20%; vertical-align: middle;" class="text-wrap">{{ ucwords(strtolower($item->product->title)) }} - {{ $item->productSku->title }}</td>
                      <td style="width:20%; vertical-align: middle;">{{ $item->price }} тг.</td>
                      <td style="width:20%; vertical-align: middle;">{{ $item->amount }}</td>
                      <td style="width:20%; vertical-align: middle;">{{ $item->price * $item->amount }} тг.</td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="row mt-4 justify-content-lg-between px-md-4 px-0">
              <div class="col-12 col-md-auto h5">
                <span class="font-weight-bold">Доставка:</span> {{ $order->expressCompany->name }} - 0 тг. ИЗМЕНИТЬ
              </div>
              <div class="col-12 col-md-auto h5">
                <span class="font-weight-bold">Сумма заказа:</span> {{ $order->total_amount }} тг.
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')

@endsection