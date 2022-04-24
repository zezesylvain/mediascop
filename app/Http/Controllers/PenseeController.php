<?php

namespace App\Http\Controllers;

use App\Pensee;
use Illuminate\Http\Request;

class PenseeController extends Controller
{

   public function __construct ()
   {
       $this->middleware('auth')->except ('app','index');
   }

    public function app(){
       return view ('pensees.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pensees = Pensee::with ('user')->latest ()->get ();

        $user = auth ()->check () ? auth ()->id() : 0;

        return response ()->json ([$pensees, $user]);
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
