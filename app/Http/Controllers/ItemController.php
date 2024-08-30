<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Items;

class ItemController extends Controller
{
    public function index() {
        return view('front/todo');
    }

    public function fetch(Request $request)
    {
         //$items = Items::withTrashed()->restore();
        if ($request->fetch == 'all') {
            $items = Items::all();
        } else {
            $items = Items::where('status', 0)->get();
        }
        $itemsData = "";
        if(!empty($items)) {
            foreach ($items as $item)
            $itemsData .= '<tr>
                <td>'.$item['id'].'</td>
                <td>'.$item['name'].'</td>
                <td>'.(($item['status'] == 1) ? "Done" : " - ").'</td>
                <td>'.(($item['status'] == 1) ? '<a type="button" data-id="'.$item['id'].'" class="btn badge bg-danger deletebtn btn-sm"><i class="fa fa-close"></i></a>' : '<a type="button" data-id="'.$item['id'].'" class="btn badge bg-success statusbtn btn-sm"> <i class="fa fa-check"></i></a> | <a type="button" data-id="'.$item['id'].'" class="btn badge bg-danger deletebtn btn-sm"><i class="fa fa-close"></i></a>').'</td>
            </tr>';
        }

        return response()->json([
            'items'=>$itemsData,
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'=> 'unique:items|required|max:191',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
        } else {
            $item = new Items;
            $item->name = $request->input('name');
            $item->save();
            return response()->json([
                'status'=>200,
                'message'=>'Todo Task Added Successfully.'
            ]);
        }
    }

    public function destroy($id)
    {
        $item = Items::find($id);
        if($item)
        {
            $item->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Item Deleted Successfully.'
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Item Found.'
            ]);
        }
    }

    public function updateStatus($id)
    {
        $item = Items::where(['id' => $id ])->update(['items.status'=> 1]);
        if ($item > 0) {
            return response()->json([
                'status'=>200,
                'message'=>'Item Updated Successfully.'
            ]);
        } else {
            return response()->json([
                'status'=>404,
                'message'=>'No Item Found.'
            ]);
        }
    }
}
