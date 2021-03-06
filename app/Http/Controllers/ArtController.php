<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Art;
use App\User;
use App\Tag;

class ArtController extends Controller
{

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => ['required', 'string', 'max:255', 'unique:arts',],
            'file' => ['required', 'string',],
            'caption'=> ['string'],
            'tags'=>['array', 'max:10']
        ]);
    }

    protected function file_validator(array $data)
    {
        return Validator::make($data, [
            'file'=> ['file', 'required','mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,video/MP2T,application/x-mpegURL,image/jpeg,image/jpg,image/png,image/gif']
        ]);
    }
    protected function createArt(Request $data)
    {
        return Art::create([
            'title' => $data['title'],
            'caption' => $data['caption'],
            'file'=> $data['file'],
            "user_id"=>0
        ]);
    }

    protected function createTags(Request $request)
    {
        $tags = [];
        foreach ($request['tags'] as $tag_name){
            $tag = Tag::where("name", $tag_name)->first();
            if ($tag===null){
                $tag = Tag::create(['name'=>$tag_name]);
            }
            $tags[] = $tag;
        }
        return $tags;
    }
    //
    public function create(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails())
        {
            return $this->sendError("Validation Error", $validator->errors(), 400);
        }

        // $user = Auth::user();
        $art = $this->createArt($request);
        if ($request['tags'])
        {
            $tags = $this->createTags($request);
            $art->tags()->saveMany($tags);
        }
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
        $art_query = Art::limit($page_size)->skip($page*$page_size)->orderBy($sort_by, $sort_order);
        //query builder for tags search
        if ($request['tags'] !== null){
            $art_query->whereHas('tags', function(Builder $query) use ($request){
                $query->where('name', $request['tags'][0]);
                foreach ($request['tags'] as $tag){
                    $query->orWhere('name', $tag);
                }
            });
        }
        $arts = $art_query->get();
        return $this->sendResponse($arts);
    }

    public function get($id, Request $request)
    {
        $art = Art::with('author', 'tags')->find($id);
        return $this->sendResponse($art);
    }

    public function upload(Request $request)
    {
        $validator = $this->file_validator($request->all());
        if ($validator->fails())
        {
            return $this->sendError("Validation Error", $validator->errors(), 400);
        }
        $path = $request->file('file')->store('public/artwork');
        return $this->sendResponse(array(
            'file'=>$path
        ));
    }
}
