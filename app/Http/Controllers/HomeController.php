<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Slider;
use Cache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
  /**
   * Homepage
   *
   * @return View
   * @throws \Psr\SimpleCache\InvalidArgumentException
   */
  public function index(): View
  {
    $newProducts = Product::whereOnNew(true)
      ->orderByDesc('id')
      ->take(8)
      ->get();

    $bestseller = Product::whereOnTop(true)
      ->orderByDesc('id')
      ->take(8)
      ->get();
    Cache::delete('sliders-index-page');
    $sliders = Cache::remember('sliders-index-page', config('app.cache.db'), function () {
      return Slider::all();
    });

    $brands = Cache::remember('brands-to-index', config('app.cache.bd'), function () {
      return Brand::where('to_index', true)->take(6)->get();
    });
    return view('user.index', compact('brands', 'bestseller', 'newProducts', 'sliders'));
  }

  /**
   * Change site language
   *
   * @param string $locale
   * @return RedirectResponse
   */
  public function language(string $locale): RedirectResponse
  {
    setcookie('language', $locale, time() + (86400 * 30), "/");
    return redirect()->back();
  }

  /**
   * Page PRIVACY POLICY
   *
   * @return View
   */
  public function policy(): View
  {
    return view('user.page.policy');
  }

  /**
   * Page PAY
   *
   * @return View
   */
  public function pay(): View
  {
    return view('user.page.pay');
  }
}
