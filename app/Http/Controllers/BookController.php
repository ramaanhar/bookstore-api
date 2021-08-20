<?php

namespace App\Http\Controllers;

use App\Models\Book;
use ErrorException;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        // $book = Book::all();
        $book = Book::paginate(3);

        try {
            $response = [
                'message' => 'List of books:',
                'data' => $book
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
            'author_id' => ['required'],
            'pages' => ['required', 'numeric'],
            'weight' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $book = Book::create($request->all());
            $response = [
                'message' => 'A book created!',
                'data' => $book
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
        $book = Book::findOrFail($id);

        $response = [
            'message' => 'Detail of the book:',
            'data' => $book
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
        $book = Book::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'author_id' => ['required'],
            'pages' => ['required', 'numeric'],
            'weight' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $book->update($request->all());
            $response = [
                'message' => 'A book data was updated!',
                'data' => $book,
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
        $book = Book::findOrFail($id);

        try {
            $book->delete();
            $response = [
                'message' => 'A book deleted!'
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
