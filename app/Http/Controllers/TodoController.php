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
        $item = TodoItem::where('user_id', auth()->id())->findOrFail($id);
        return response()->json($item);
    }

    public function updateTodoItem(Request $request, $id)
    {
        $item = TodoItem::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $item->update($request->only('title'));

        return response()->json($item);
    }

    public function updateTodoItemStatus(Request $request, $id)
    {
        $item = TodoItem::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'completed' => 'required|boolean',
        ]);

        $item->update(['completed' => $request->completed]);

        return response()->json($item);
    }

    public function deleteTodoItem($id)
    {
        $item = TodoItem::where('user_id', auth()->id())->findOrFail($id);
        $item->delete();

        return response()->json(null, 204);
    }
}
