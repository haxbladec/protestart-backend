<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Art;
use App\User;

class ArtController extends Controller
{

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => ['required', 'string', 'max:255', 'unique:arts',],
            'file' => ['required', 'string',],
            'caption'=> ['string']
        ]);
    }


    protected function createArt(Request $data, User $user)
    {
        return $user->arts()->create([
            'title' => $data['title'],
            'caption' => $data['caption'],
            'file'=> $data['file'],
        ]);
    }
    //
    public function create(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails())
        {
            return $this->sendError("Validation Error", $validator->errors(), 400);
        }

        $user = Auth::user();
        $art = $this->createArt($request, $user);
        return $this->sendResponse(array(
            "id" => $art->id
        ));
    }

    public function index(Request $request)
    {
        $page_size = $request->input('page_size', 20);
        $page = $request->input('page', 0);
        $sort_by = $request->input('sort_by', 'created_at');
        $sort_order = $request->input('sort_order', 'desc');
        $arts = Art::limit($page_size)->skip($page)->orderBy($sort_by, $sort_order)->get();
        return $this->sendResponse($arts);
    }

    public function get($id, Request $request)
    {
        $art = Art::find($id);
        return $this->sendResponse($art);
    }
}
