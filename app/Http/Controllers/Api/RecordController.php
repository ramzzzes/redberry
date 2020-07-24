<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Record;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function index(Request $request)
    {
        $record = Record::orderBy('created_at','desc')->paginate($request->post('limit',10));
        return response()->json($record);
    }


    public function store(Request $request,Record $record)
    {
        try{
            $record->validate($request->all());
            $response = $record->store($request->all());
            return response()->json($response);
        }catch (\Exception $e){
            return response()->json($e->getMessage(),$e->getCode());
        }
    }

    public function update(Request $request,$id)
    {
        try{
            $record = Record::findOrFail($id);
            $record->validate($request->all());
            $record->update($request->all());

            return response()->json($record);
        }catch (\Exception $e){
            return response()->json($e->getMessage(),500);
        }
    }

    public function destroy($id)
    {
        try{
            $record = Record::findOrFail($id);
            return response()->json($record->delete($id));
        }catch (\Exception $e){
            return response()->json($e->getMessage(),500);
        }
    }
}
