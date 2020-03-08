<?php

namespace App\Http\Controllers\Admin;

use App\Models\ExpressZone;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ExpressZoneController extends Controller
{
  public function __construct()
  {
//    parent::__construct($cartService);
  }

  /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
      return view('admin.express-zone.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
      $request->validate([
        'name' => 'required|unique:express_zones,name',
        'cost' => 'required|numeric|min:0',
        'step' => 'required|numeric|min:0',
        'cost_step' => 'required|numeric|min:0',
        'company_id' => 'required|exists:express_companies,id'
      ]);

      $zone = new ExpressZone();
      $zone = $zone->create($request->all());

      return redirect()->route('admin.store.express-zone.edit', $zone->id);
    }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return void
   */
    public function show($id)
    {
        //
    }

  /**
   * Show the form for editing the specified resource.
   *
   * @param $id
   * @return Factory|View
   */
    public function edit($id)
    {
      $zone = ExpressZone::find($id);
      return view('admin.express-zone.edit', compact('zone'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
      if (isset($request->city_id)) {
        $zone = ExpressZone::find($id);
        $request->validate([
          'city_id' => 'required|unique:city_expresses,city_id,'.$request->city_id.',id,express_company_id,'.$zone->company->id
        ]);

        $zone->cities()->attach($request->city_id, ['express_company_id' => $zone->company->id]);
        return ['success' => 'ok'];
      } else {
        $zone = ExpressZone::find($id);
        $request->validate([
          'name' => 'required|unique:express_zones,name,' . $id,
          'cost' => 'required|numeric|min:0',
          'step' => 'required|numeric|min:0',
          'cost_step' => 'required|numeric|min:0',
          'company_id' => 'required|exists:express_zones'
        ]);

        $zone->update($request->all());
        return redirect()->route('admin.store.express-zone.edit', $zone->id);
      }
    }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return RedirectResponse
   */
    public function destroy($id)
    {
      $zone = ExpressZone::find($id);
      $zone->delete();
      return redirect()->route('admin.store.express.edit', $zone->company->id);
    }

  public function destroyCity(Request $request, $id)
  {
    if (isset($request->city_id)) {
      $zone = ExpressZone::find($id);
      $zone->cities()->detach($request->city_id);
      return ['success'=>'ok'];
    }
  }
}
