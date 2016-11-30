<?php

namespace Kneu\Petition\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kneu\Petition\Http\Requests\StorePetitionPost;
use Kneu\Petition\Petition;
use Kneu\Petition\PetitionVote;

class PetitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => [
                'index',
                'show',
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $builder = Petition::query();

        // $builder = $builder->where('is_closed', false);

        $petitions = $builder->paginate(15);

        return view('petition.list', compact(
            'petitions'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function show(Petition $petition)
    {
        return view('petition.view', [
            'petition' => $petition
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $petition = new Petition();
        $petition->user_id = Auth::id();

        return $this->edit($petition);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function edit(Petition $petition)
    {
        return view('petition.edit', [
            'petition' => $petition
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePetitionPost $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePetitionPost $request)
    {
        $petition = new Petition();
        $petition->user_id = Auth::id();

        return $this->update($request, $petition);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function update(StorePetitionPost $request, Petition $petition)
    {
        $petition->fill($request->all());
        $petition->save();

        return redirect()->action('PetitionController@show', ['petition' => $petition]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Petition $petition)
    {
        //
    }

    public function vote(Request $request, Petition $petition)
    {
        abort_if($petition->is_closed, 400);

        $userId = Auth::id();

        if(!$petition->hasUserVoted($userId)) {
            $vote = new PetitionVote;
            $vote->user_id = $userId;
            $vote->petition_id = $petition->id;
            $status = $vote->save();

            $petition->calculateVotes();
        } else {
            $status = true;
        }

        if($request->ajax()) {
            return response()->json([
                'status' => $status,
            ]);
        }

        return redirect()->action('PetitionController@show', ['petition' => $petition]);
    }
}
