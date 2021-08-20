<?php

namespace App\Http\Controllers;

use App\Models\Author;
use ErrorException;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $author = Author::all();

        try {
            $response = [
                'message' => 'List of authors:',
                'data' => $author
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $qe) {
            return response()->json([
                'message' => "Failed " . $qe->errorInfo
            ]);
        }
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
            'name' => ['required'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $author = Author::create($request->all());
            $response = [
                'message' => 'An author created!',
                'data' => $author
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $qe) {
            return response()->json([
                'message' => "Failed " . $qe->errorInfo
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $author = Author::findOrFail($id);

        $response = [
            'message' => 'Detail of the author:',
            'data' => $author
        ];

        return response()->json($response, Response::HTTP_OK);
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
        $author = Author::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $author->update($request->all());
            $response = [
                'message' => 'An author data was updated!',
                'data' => $author,
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $qe) {
            return response()->json([
                'message' => 'Failed ' . $qe->errorInfo
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $author = Author::findOrFail($id);

        try {
            $author->delete();
            $response = [
                'message' => 'An author deleted!'
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $qe) {
            return response()->json([
                'message' => "Failed " . $qe->errorInfo
            ]);
            //throw $th;
        }
    }
}
