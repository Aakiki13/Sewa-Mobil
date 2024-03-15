<?php

namespace App\Http\Controllers\Admin;


use App\Models\Useraccount;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UseraccountRequest;

class UseraccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = Useraccount::all();
            // dd($query);
            return DataTables::of($query)
                ->addColumn('profil_photos_path', function ($useraccount) {
                    return '<img src="' . $useraccount->profile_photo_path . '" alt="" class="w-20 mx-auto rounded-md">';
                })
                ->addColumn('action', function ($useraccount) {
                    return '
                        <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none ease hover:bg-gray-800 focus:outline-none focus:shadow-outline" 
                            href="' . route('admin.useraccounts.edit', $useraccount->id) . '">
                            Sunting
                        </a>
                        <form class="block w-full" onsubmit="return confirm(\'Apakah anda yakin?\');" -block" action="' . route('admin.useraccounts.destroy', $useraccount->id) . '" method="POST">
                        <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                            Hapus
                        </button>
                            ' . method_field('delete') . csrf_field() . '
                        </form>';
                })
                ->rawColumns(['action', 'profil_photos_path'])
                ->make(true);
        }

        return view('admin.useraccounts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.useraccounts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UseraccountRequest $request)
    {
        $data = $request->all();
        // dd($data);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(5));

        // Upload multiple photos
        if ($request->hasFile('photos')) {
            $photos = [];

            foreach ($request->file('photos') as $photo) {
                $photoPath = $photo->store('assets/useraccount', 'public');

                // Store as json
                array_push($photos, $photoPath);
            }

            $data['photos'] = json_encode($photos);
        }
        // Hash password hanya jika ada perubahan pada password
        if ($request->filled('password')) {
            $data['password'] =  Hash::make($request->password);
        }

        Useraccount::create($data);

        return redirect()->route('admin.useraccounts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Useraccount  $useraccount
     * @return \Illuminate\Http\Response
     */
    public function show(Useraccount $useraccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Useraccount  $useraccount
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Useraccount $useraccount)
    {
        return view('admin.useraccounts.edit', [
            'useraccount' => $useraccount,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Useraccount  $useraccount
     * @return \Illuminate\Http\Response
     */
    public function update(UseraccountRequest $request, Useraccount $useraccount)
    {
        $data = $request->all();

        // If photos is not empty, then upload new photos
        if ($request->hasFile('photos')) {
            $photos = [];

            foreach ($request->file('photos') as $photo) {
                $photoPath = $photo->store('assets/useraccount', 'public');

                // Store as json
                array_push($photos, $photoPath);
            }

            $data['photos'] = json_encode($photos);
        } else {
            // If photos is empty, then use old photos
            $data['photos'] = $useraccount->photos;
        }

        $useraccount->update($data);

        return redirect()->route('admin.useraccounts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Useraccount  $useraccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Useraccount $useraccount)
    {
        $useraccount->delete();

        return redirect()->route('admin.useraccounts.index');
    }
}
