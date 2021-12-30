<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
  public function index()
  {
    $data = Program::latest()->get();
    return response()->json([
      'Program Diambil', ProgramResource::collection($data)
    ]);
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'desc' => 'required'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors());
    }

    $program = Program::create([
      'name' => $request->name,
      'desc' => $request->desc
    ]);

    return response()->json([
      'Program berhasil dibuat', 
      new ProgramResource($program)
    ]);
  }

  public function show($id) 
  {
    $program = Program::find($id);
    if(is_null($program)) {
      return response()->json('Data tidak ditemukan', 404);
    }
    return response()->json([new ProgramResource($program)]);
  }

  public function update(Request $request, Program $program) {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'desc' => 'required'
    ]);

    if($validator->fails()) {
      return response()->json($validator->errors());
    }

    $program->name = $request->name;
    $program->desc = $request->desc;
    $program->save();

    return response()->json(['Program Berhasil Diupdate', new ProgramResource($program)]);
  }

  public function destroy(Program $program) {
    $program->delete();

    return response()->json('Program Berhasil Dihapus');
  }
}
