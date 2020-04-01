@extends('admin.layouts.app')
@section('title', 'Магазин - Категории')

@section('content')
  <div class="container-fluid pt-5 px-4">
    <div class="row">
      <div class="col-12 col-md-auto">
        <h2>Категории</h2>
      </div>
    </div>
    <div class="row mt-2" style="z-index: 100">
      <div class="col-sm-auto ml-0 pl-0 col-6 px-0 pr-sm-2"><a href="{{ route('admin.production.products.index') }}" class="bg-dark px-3 py-2 d-block">Товары</a></div>
      <div class="col-sm-auto col-6 px-0 px-sm-2"><a href="{{ route('admin.production.category.index') }}" class="bg-white px-3 py-2 d-block">Категории</a></div>
      <div class="col-sm-auto col-6 px-0 px-sm-2"><a href="{{ route('admin.production.brand.index') }}" class="bg-dark px-3 py-2 d-block">Бренды</a></div>
      <div class="col-sm-auto col-6 px-0 px-sm-2"><a href="{{ route('admin.production.attr.index') }}" class="bg-dark px-3 py-2 d-block">Атрибуты</a></div>
    </div>
    <div class="row mt-0 pt-0">
      <div class="card border-0 w-100 rounded-0" style="z-index: 90;box-shadow: 0 18px 19px rgba(0, 0, 0, 0.25)">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <form action="{{ route('admin.production.category.store') }}" method="post">
                @csrf
                <div class="form-group">
                  <label for="name">Название категории</label>
                  <input type="text" class="form-control rounded-0" id="name" name="name" placeholder="Название категории" required>
                </div>
                <div class="form-group">
                  <label for="categories" class="col-12">Родительская категория</label>
                  <select name="categories[]" multiple id="categories" class="form-control rounded-0 js-example-basic-multiple">
                    @foreach(App\Models\Category::all() as $cat)
                      <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn bg-dark rounded-0 border-0">Добавить</button>
                </div>
              </form>
            </div>
            <div class="col-md-6 category">
              <ul>
                @foreach($categories as $category)
                  <li><a href="{{ route('admin.production.category.edit', $category->id) }}" class="text-red">{{ $category->name }}</a> <form action="{{ route('admin.production.category.destroy', $category->id) }}" method="post">
                      @csrf
                      @method('delete')
                      <button class="bg-transparent border-0 rounded-0" style="color: #F33C3C" type="submit"><i style="font-size: 1.5rem" class="fal fa-trash"></i></button>
                    </form></li>
                  @if($category->child()->count() > 0)
                    <ul>
                      @include('admin.layouts.categoryList', ['cat' => $category->child()->get(), 'deleted' => true])
                    </ul>
                  @endif
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script>
    $('.js-example-basic-multiple').select2({
      width: 'resolve'
    });
  </script>
@endsection