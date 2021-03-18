<?php
/*
 * Copyright (c) 2021. Данный файл является интелектуальной собственостью Fulliton.
 * Я буду рад если вы будите вносить улучшения, всегда жду ваших пул реквестов
 */

namespace App\Http\Controllers;


use App\Models\User;
use File;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;

class ProfileController extends Controller
{

  /**
   * @return Application|Factory|View
   * @throws BindingResolutionException
   */
  public function index()
  {
    return view('user.profile.index');
  }

  /**
   * @param Request $request
   * @return RedirectResponse
   */
  public function data(Request $request): RedirectResponse
  {
    $request->validate([
      'currency' => 'required|exists:currencies,id',
      'name' => 'required|string',
      'phone' => 'required|string',
      'email' => 'required|unique:users,email,' . auth()->user()->id,
      'address' => 'required|string',
      'country' => 'required|exists:countries,id',
      'city' => 'required|exists:cities,id'

    ]);
    $user = User::find(auth()->user()->id);
    $user->update($request->all());
    $user->currency()
      ->associate($request->currency);
    $user->city()
      ->associate($request->city);
    $user->country()
      ->associate($request->country);
    $user->save();

    return redirect()->route('profile.index')->with('success', [__('success.data')]);
  }

  /**
   * @param Request $request
   * @return RedirectResponse
   * @throws BindingResolutionException
   */
  public function photo(Request $request): RedirectResponse
  {
    $user = auth()->user();
    $image = $request->file('photo');

    $imageName = time() . '.' . $image->getClientOriginalExtension();

    $destinationPath = public_path(User::PHOTO_PATH);

    $img = Image::make($image->getRealPath());
    $img
      ->fit(200)
      ->save($destinationPath . '/' . $imageName);
    if ($user->avatar) {
      File::delete($destinationPath . '/' . $user->avatar);
    }

    $user->avatar = $imageName;
    $user->save();
    return redirect()->route('profile.index')->with('success', [__('success.photo')]);
  }

  /**
   * @param Request $request
   * @return RedirectResponse
   */
  public function password(Request $request): RedirectResponse
  {
    $user = auth()->user();
    $request->validate([
      'password' => 'required|confirmed'
    ]);
    $user->password = Hash::make($request->password);
    $user->save();
    return redirect()->route('profile.index')->with('success', [__('success.password')]);
  }

}
