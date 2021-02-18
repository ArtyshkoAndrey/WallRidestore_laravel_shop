<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\PhotoService;
use Cache;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psr\SimpleCache\InvalidArgumentException;

class BrandController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return Application|Factory|View|Response
   */
  public function index(Request $request)
  {
    $brands = Brand::query();
    $name = $request->get('name');
    if ($name) {
      $brands = $brands->where('name', 'like', '%' . $name . '%');
    }

    $filter = [
      'name' => $name
    ];
    $brands = $brands->paginate(7);
    $brands->appends($filter);
    return view('admin.brand.index', compact('brands', 'filter'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
      //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return RedirectResponse
   */
  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'name'            => 'required|string|unique:brands,name',
      'photo'           => 'sometimes|image',
      'logo'            => 'sometimes|image',
      'ru.description'  => 'required|string',
      'en.description'  => 'required|string',
      'to_index'        => 'required|boolean'
    ]);

    $data = $request->all();
    if ($request->has('photo'))
      $data['photo'] = PhotoService::create($request->file('photo'), 'storage/brands/photo', true, 30, 500);
    if ($request->has('logo'))
      $data['logo'] = PhotoService::create($request->file('logo'), 'storage/brands/logo', true, 30, 500);

    Brand::create($data);
//    TODO: Чистить кеш брендов левого меню и брендов верхнего меню
    Cache::delete('brands-to-index');
    Cache::delete('brands-menu');
    return redirect()->back()->with('success', ['Бренд успешно создан']);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
      //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
      //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param int $id
   * @return RedirectResponse
   * @throws InvalidArgumentException
   */
  public function update(Request $request, int $id): RedirectResponse
  {
    $request->validate([
      'name'            => 'required|string|unique:brands,name,' . $id,
      'photo'           => 'sometimes|image',
      'logo'            => 'sometimes|image',
      'ru.description'  => 'required|string',
      'en.description'  => 'required|string',
      'to_index'        => 'required|boolean'
    ]);

    $brand = Brand::find($id);

    $data = $request->all();
    if ($request->has('photo'))
      $data['photo'] = PhotoService::create($request->file('photo'), 'storage/brands/photo', true, 30, 500);
    if ($request->has('logo'))
      $data['logo'] = PhotoService::create($request->file('logo'), 'storage/brands/logo', true, 30, 500);
    $brand->update($data);

    Cache::delete('brands-to-index');
    Cache::delete('brands-menu');
    return redirect()->back()->with('success', ['Бренд успешно обнавлён']);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return RedirectResponse
   * @throws Exception
   */
  public function destroy(int $id): RedirectResponse
  {
    $brand = Brand::find($id);
    $brand->delete();
    Cache::delete('brands-to-index');
    Cache::delete('brands-menu');
    return redirect()->back()->with('success', ['Бренд успешно удалён']);
  }
}
