<?php

namespace App\Http\Controllers;

use App\Language;
use App\Resource;
use App\Type;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = User::find(session('usuario_id'))->resources()->paginate(6);
        //$resources = User::find(session('usuario_id'))->resources;
        $languages = Language::pluck('name', 'id');
        $types = Type::pluck('name', 'id');
        $method = 'POST';
        $url = '/resource';
        return view('resources.resources', compact('resources', 'languages', 'types', 'method', 'url'));
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'link' => 'required',
            'description' => 'required'
        ]);

        $resource = new Resource;
        $resource->name = $request->name;
        $resource->user_id = session('usuario_id');
        $resource->type_id = $request->type_id;
        if (isset($request->has_cost) && $request->has_cost != null) {
            $resource->has_cost = 1;
        }
        $resource->language_id = $request->language_id;
        $resource->link = $request->link;
        if (strlen($resource->description) > 350) {
            $resource->short_description = substr($resource->description, 0, 350) . '...';
        } else {
            $resource->short_description = $request->description;
        }
        $resource->description = $request->description;
        $resource->tags = $request->tags;

        $resource->save();

        return back()->with('success', 'Recurso creado satisfactoriamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $resource = Resource::find($id);
            return response()->json(['status' => 200, 'data' => compact('resource')]);
        }

        $resource = Resource::find($id);
        dd($resource);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $recurso = Resource::find($id);
        if (Auth::user()->id != $recurso->user->id) {
            return redirect('/');
        }

        $resources = User::find(session('usuario_id'))->resources;
        $languages = Language::pluck('name', 'id');
        $types = Type::pluck('name', 'id');
        $method = 'PATCH';

        $url = "/resource/$recurso->id";

        return view('resources.update', compact('resources', 'languages', 'types', 'method', 'url', 'recurso'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'link' => 'required',
            'description' => 'required'
        ]);

        $resource = Resource::find($id);
        $resource->name = $request->name;
        $resource->type_id = $request->type_id;
        $resource->has_cost = ($request->has('has_cost')) ? 1 : 0;
        $resource->language_id = $request->language_id;
        $resource->link = $request->link;
        $resource->description = $request->description;
        $resource->tags = '';

        $resource->save();
        //return back()->with('success', 'Recurso actualizado satisfactoriamente');
        return redirect('/resource')->with('success', 'Recurso actualizado satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Resource::destroy($id);
        return back()->with('success', 'Registro eliminado satisfactoriamente');
    }
}
