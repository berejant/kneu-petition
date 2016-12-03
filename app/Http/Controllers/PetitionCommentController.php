<?php

namespace Kneu\Petition\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kneu\Petition\Http\Requests\StorePetitionCommentPost;
use Kneu\Petition\Petition;
use Kneu\Petition\PetitionComment;

class PetitionCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  StorePetitionCommentPost  $request
     * @param  Petition $petition
     * @return \Illuminate\Http\Response
     */
    public function store(StorePetitionCommentPost $request, Petition $petition)
    {
        $petitionComment = new PetitionComment();
        $petitionComment->fill($request->all());
        $petitionComment->petition()->associate($petition);
        $petitionComment->user()->associate(Auth::user());

        $petitionComment->save();
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
    public function destroy($id)
    {
        //
    }
}
