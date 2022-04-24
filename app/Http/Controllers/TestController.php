<?php

namespace App\Http\Controllers;

use App\Format;
use App\Media;
use App\Secteur;
use App\Nature;
use Illuminate\Http\Request;

class TestController extends Controller
{

   public function __construct ()
   {
       
   }

    public function app(){
       return view ('pensees.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $formats = Format::find(1) ;
        dd($formats->medias);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|max:1000',
        ]);

        $request->merge (['user_id' => $request->user()->id]);
        $pensee = Pensee::create($request->all ());
        
        return Pensee::with ('user')->find($pensee->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pensee $pensee)
    {
        $this->authorize ('delete', $pensee);
        $pensee->delete ();
        return response ()->json ();
    }
}
