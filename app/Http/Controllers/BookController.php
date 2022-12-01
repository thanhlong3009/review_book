<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('id', 'DESC')->paginate(8);

        return view('books.book',compact('books'));
    }

    public function create() {
        return view('books.create');
    }

    public function store(Request $request) {
        $data = $request->validate(
            [
                'title' => 'required|max:255',
                'author' => 'required',
                'description' => 'required',
            ]
        );
        $book = new Book();
        $book->title = $data['title'];
        $book->description = $data['description'];
        $book->author = $data['author'];
        $path = $this->_upload($request);
        if ($path) {
            $book->image = $path;
        }
        $book->save();
        return redirect()->route('book.create')->with('status','thêm thành công!');
    }

    public function edit($id)
    {
        $book = Book::find($id);
        return view('books.edit',compact(['book']));
    }

    public function update(Request $request, $id){
        
        $book = Book::find($id);
        if($request->title) {
            $book->title = $request['title'];
        }
        if($request->description) {
            $book->description = $request['description'];
        }
        if($request->author) {
            $book->author = $request['author'];
        }

        
        $path = $this->_upload($request);
        if ($path) {
            $book->image = $path;
        }
        $book->save();
        return redirect()->back()->with('status','sửa thành công!');
    }

    public function destroy($id)
    {
        $comic = Book::find($id);
        Storage::delete($comic->image);
        $comic->delete();
        return redirect()->back()->with('status','xóa thành công!');
    }

    public function _upload($request) {
        if ($request->file()) {
            try {
                $name = $request->file('image')->getClientOriginalName();
                $pathFull = 'uploads/' . date("Y/m/d");
                $request->file('image')->storeAs(
                    'public/' . $pathFull,
                    $name
                );
                return '/storage/' . $pathFull . '/' . $name;
            } catch (\Exception $error) {
                return false;
            }

        }
        return false;
    }
}
