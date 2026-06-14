<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\Subclassification;
use App\Models\Qualification;
use App\Models\WorkPosition;
use App\Models\Lsp;
use App\Models\Association;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index()
    {
        return view('master.index');
    }

    // ==========================================
    // Classification API
    // ==========================================
    public function getClassifications()
    {
        return response()->json(Classification::all());
    }

    public function storeClassification(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:classifications,nama'
        ]);
        return response()->json(Classification::create($validated), 201);
    }

    public function updateClassification(Request $request, Classification $classification)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:classifications,nama,' . $classification->id
        ]);
        $classification->update($validated);
        return response()->json($classification);
    }

    public function destroyClassification(Classification $classification)
    {
        $classification->delete();
        return response()->json(['success' => true]);
    }

    // ==========================================
    // Subclassification API
    // ==========================================
    public function getSubclassifications()
    {
        return response()->json(Subclassification::with('classification')->get());
    }

    public function storeSubclassification(Request $request)
    {
        $validated = $request->validate([
            'classification_id' => 'required|exists:classifications,id',
            'nama' => 'required|string|max:255'
        ]);
        return response()->json(Subclassification::create($validated)->load('classification'), 201);
    }

    public function updateSubclassification(Request $request, Subclassification $subclassification)
    {
        $validated = $request->validate([
            'classification_id' => 'required|exists:classifications,id',
            'nama' => 'required|string|max:255'
        ]);
        $subclassification->update($validated);
        return response()->json($subclassification->load('classification'));
    }

    public function destroySubclassification(Subclassification $subclassification)
    {
        $subclassification->delete();
        return response()->json(['success' => true]);
    }

    // ==========================================
    // Qualification API
    // ==========================================
    public function getQualifications()
    {
        return response()->json(Qualification::all());
    }

    public function storeQualification(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:qualifications,nama'
        ]);
        return response()->json(Qualification::create($validated), 201);
    }

    public function updateQualification(Request $request, Qualification $qualification)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:qualifications,nama,' . $qualification->id
        ]);
        $qualification->update($validated);
        return response()->json($qualification);
    }

    public function destroyQualification(Qualification $qualification)
    {
        $qualification->delete();
        return response()->json(['success' => true]);
    }

    // ==========================================
    // Work Position API
    // ==========================================
    public function getWorkPositions()
    {
        return response()->json(WorkPosition::with('subclassification.classification')->get());
    }

    public function storeWorkPosition(Request $request)
    {
        $validated = $request->validate([
            'subclassification_id' => 'required|exists:subclassifications,id',
            'kode_jabatan' => 'required|string|max:255|unique:work_positions,kode_jabatan',
            'nama' => 'required|string|max:255'
        ]);
        return response()->json(WorkPosition::create($validated)->load('subclassification.classification'), 201);
    }

    public function updateWorkPosition(Request $request, WorkPosition $workPosition)
    {
        $validated = $request->validate([
            'subclassification_id' => 'required|exists:subclassifications,id',
            'kode_jabatan' => 'required|string|max:255|unique:work_positions,kode_jabatan,' . $workPosition->id,
            'nama' => 'required|string|max:255'
        ]);
        $workPosition->update($validated);
        return response()->json($workPosition->load('subclassification.classification'));
    }

    public function destroyWorkPosition(WorkPosition $workPosition)
    {
        $workPosition->delete();
        return response()->json(['success' => true]);
    }

    // ==========================================
    // LSP API
    // ==========================================
    public function getLsps()
    {
        return response()->json(Lsp::all());
    }

    public function storeLsp(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:lsps,nama'
        ]);
        return response()->json(Lsp::create($validated), 201);
    }

    public function updateLsp(Request $request, Lsp $lsp)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:lsps,nama,' . $lsp->id
        ]);
        $lsp->update($validated);
        return response()->json($lsp);
    }

    public function destroyLsp(Lsp $lsp)
    {
        $lsp->delete();
        return response()->json(['success' => true]);
    }

    // ==========================================
    // Association API
    // ==========================================
    public function getAssociations()
    {
        return response()->json(Association::all());
    }

    public function storeAssociation(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:associations,nama'
        ]);
        return response()->json(Association::create($validated), 201);
    }

    public function updateAssociation(Request $request, Association $association)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:associations,nama,' . $association->id
        ]);
        $association->update($validated);
        return response()->json($association);
    }

    public function destroyAssociation(Association $association)
    {
        $association->delete();
        return response()->json(['success' => true]);
    }
}
