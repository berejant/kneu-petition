<?php

namespace Kneu\Petition\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Kneu\Petition\Http\Requests\StorePetitionPost;
use Kneu\Petition\Petition;
use Kneu\Petition\PetitionComment;
use Kneu\Petition\PetitionVote;
use Cache;

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
    public function index(Request $request)
    {
        $actionName = $request->route()->getName();

        $filter = null;
        if( ($pos = strpos($actionName, 'index.')) !== false) {
            $filter = substr($actionName, $pos + 6);
        }

        $builder = Petition::orderBy('created_at', 'DESC');

        switch ($filter) {
            case 'open': $builder->where('is_closed', false); break;
            case 'successful': $builder->where('is_successful', true); break;

            default: $filter = 'all'; break;
        }

        $petitions = $builder->paginate(10);

        $statusStatistics = $this->getPetitionStatusStatistics();

        return view('petition.list', compact(
            'petitions', 'filter', 'statusStatistics'
        ));
    }

    protected function getPetitionStatusStatistics()
    {
        /* Dummy  Statistics  */
        return [
            ['Статус петиції', 'Кількість'],
            ['Йде голосування', 11],
            ['Не підтримано', 4],
            ['На розгляді', 4],
            ['Розглянуто', 5]
        ];
        /* */

        $statistics = Cache::get(__METHOD__);

        if(!$statistics) {
            $statistics = [
                ['Статус петиції', 'Кількість'],
                ['Йде голосування', Petition::where('is_closed', 0)->count()],
                ['Не підтримано', Petition::where('is_closed', 1)->where('is_successful', 0)->count()],
                ['На розгляді',   Petition::where('is_successful', 1) ->count()],
                ['Розглянуто',  0],
            ];

            Cache::put(__METHOD__, $statistics, 10);
        }

        return $statistics;

    }

    /**
     * Display the specified resource.
     *
     * @param  Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function show(Petition $petition)
    {
        return view('petition.item', [
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
        $this->authorize('create', Petition::class);

        return view('petition.edit', [
            'petition' =>  new Petition,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function edit(Petition $petition)
    {
        $this->authorize('update', $petition);

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
        $this->authorize('create', Petition::class);

        $petition = new Petition();
        $petition->user()->associate(Auth::user());

        $this->save($request, $petition);

        return redirect()->action('PetitionController@show', ['petition' => $petition]);
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
        $this->authorize('update', $petition);

        $this->save($request, $petition);

        return redirect()->action('PetitionController@show', ['petition' => $petition]);
    }

    protected function save(StorePetitionPost $request, Petition $petition)
    {
        $petition->fill($request->all());
        $petition->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Petition $petition)
    {
        $this->authorize('delete', $petition);

        $petition->delete();

        if($request->ajax()) {
            return response()->json([
                'status' => true,
            ]);
        }

        return redirect()->action('PetitionController@index');

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
