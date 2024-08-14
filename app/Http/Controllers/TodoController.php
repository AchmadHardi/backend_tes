<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\TodoItem;

class TodoController extends Controller
{
    public function createChecklist(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
            ]);

            $checklist = Checklist::create([
                'title' => $request->title,
                'user_id' => auth()->id(),
            ]);

            return response()->json($checklist, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function deleteChecklist($id)
    {
        $checklist = Checklist::where('user_id', auth()->id())->findOrFail($id);

        // Hapus checklist
        $checklist->delete();

        // Kembalikan respons sukses dengan pesan
        return response()->json([
            'success' => 'Checklist successfully deleted!'
        ], 200); // Gunakan status code 200 OK
    }


    public function getChecklists()
    {
        $checklists = Checklist::where('user_id', auth()->id())->get();
        return response()->json($checklists);
    }

    public function getChecklistDetails($id)
    {
        $checklist = Checklist::where('user_id', auth()->id())->findOrFail($id);
        return response()->json($checklist->load('todoItems'));
    }


    // TodoController.php
    public function createTodoItem(Request $request, $checklistId)
    {
        // Validasi input dari request
        $request->validate([
            'item' => 'required|string|max:255',
        ]);

        // Temukan checklist berdasarkan user_id dan checklist_id
        $checklist = Checklist::where('user_id', auth()->id())->findOrFail($checklistId);

        // Buat item baru yang terkait dengan checklist
        $item = $checklist->todoItems()->create([
            'item' => $request->item,
            'is_completed' => false, // Anda bisa menyesuaikan ini sesuai kebutuhan
        ]);

        // Kembalikan respons JSON
        return response()->json($item, 201);
    }

    public function addItem(Request $request, $checklistId)
    {
        $request->validate([
            'item' => 'required|string|max:255',
        ]);

        $checklist = Checklist::findOrFail($checklistId);

        $item = $checklist->todoItems()->create([
            'item' => $request->item,
            'is_completed' => false,
        ]);

        return response()->json($item, 201);
    }

    public function toggleItem($itemId)
    {
        $item = TodoItem::findOrFail($itemId);
        $item->is_completed = !$item->is_completed;
        $item->save();

        return response()->json($item, 200);
    }

    public function getTodoItem($id)
    {
        $item = TodoItem::where('id', auth()->id())->findOrFail($id);
        return response()->json($item);
    }

    public function updateTodoItem(Request $request, $id)
    {
        $item = TodoItem::where('id', auth()->id())->findOrFail($id);

        $request->validate([
            'item' => 'required|string|max:255',
        ]);

        $item->update($request->only('item'));

        return response()->json($item);
    }

    public function updateTodoItemStatus(Request $request, $id)
    {
        // Validasi request
        $request->validate([
            'completed' => 'required|boolean',
        ]);

        // Temukan item dengan ID yang sesuai
        $item = TodoItem::where('id', $id)
                        ->whereHas('checklist', function ($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->firstOrFail();

        // Konversi nilai boolean ke integer
        $isCompleted = $request->completed ? 1 : 0;

        // Perbarui status item
        $item->update(['is_completed' => $isCompleted]);

        // Kembalikan respons JSON
        return response()->json($item);
    }

    public function deleteTodoItem($id)
{
    // Temukan item dengan ID yang sesuai
    $item = TodoItem::where('id', $id)
                    ->whereHas('checklist', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->firstOrFail();

    // Hapus item
    $item->delete();

    // Kembalikan respons JSON dengan status 204 No Content
    return response()->json([
        'success' => 'Checklist successfully deleted!'
    ], 200); // Gunakan status code 200 OK
}

}
