<?php

namespace App\Http\Controllers\Godpanel;

use App\Models\MapProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class MapProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maps = MapProvider::orderBy('created_at', 'DESC')->paginate(10);
        return view('godpanel/maps')->with(['maps' => $maps]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $map = new MapProvider();
        return view('godpanel/map-form')->with(['map' => $map]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'add');
        }

        $data = [
            'provider' => $request->provider,
            'keyword' => strtolower(str_replace(' ', '_', $request->provider)),
            'status' => $request->status,
        ];

        $client = MapProvider::create($data);
        return redirect()->route('map.index')->with('success', 'Map provider added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MapProvider  $mapProvider
     * @return \Illuminate\Http\Response
     */
    public function show(MapProvider $mapProvider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MapProvider  $mapProvider
     * @return \Illuminate\Http\Response
     */
    public function edit(MapProvider $mapProvider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MapProvider  $mapProvider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MapProvider $mapProvider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MapProvider  $mapProvider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, MapProvider $mapProvider)
    {
        $map_prov = MapProvider::findOrFail($id);
        if($map_prov->keyword == 'google_map'){
            return redirect()->back()->with('error_delete', 'Google map provider is not deletable!');
        }
        $map_prov->delete();
        return redirect()->back()->with('success', 'Map provider deleted successfully!');
    }
}
