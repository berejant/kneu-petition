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
        abort_if($petition->is_closed, 403);

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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StorePetitionCommentPost $request
     * @param  Petition $petition
     * @param  PetitionComment $petitionComment
     * @return \Illuminate\Http\Response
     */
    public function update(StorePetitionCommentPost $request, Petition $petition, PetitionComment $petitionComment)
    {
        $this->authorize('update', $petitionComment);

        $petitionComment->fill($request->all());
        $petitionComment->save();

        if($request->ajax()) {
            return response()->json([
                'status' => true,
            ]);
        }

        return redirect()->action('PetitionController@show', ['petition' => $petition]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @param  Petition $petition
     * @param  PetitionComment $petitionComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Petition $petition, PetitionComment $petitionComment)
    {
        $this->authorize('delete', $petitionComment);

        $petitionComment->delete();

        if($request->ajax()) {
            return response()->json([
                'status' => true,
            ]);
        }

        return redirect()->action('PetitionController@show', ['petition' => $petition]);
    }
}
