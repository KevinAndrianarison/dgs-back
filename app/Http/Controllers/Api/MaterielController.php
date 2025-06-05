<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiel;
use App\Models\TypeMateriel;
use App\Models\Reference;
use App\Models\Historique;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;
use App\Models\Photo;

class MaterielController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Materiel::with(['categorie', 'type', 'source', 'reference', 'region', 'responsable','appartenance', 'photos', 'utilisations'=>function($query) {
            $query->orderBy('date', 'desc');
        }])->orderBy('date_acquisition', 'desc')->get());
    }

    public function getMaterielParIdRegion($regionId)
    {
    $materiels = Materiel::where('region_id', $regionId)
        ->with(['categorie', 'type', 'source', 'reference','appartenance', 'responsable', 'region', 'photos', 'utilisations'=>function($query) {
            $query->orderBy('date', 'desc');
        }])
        ->orderBy('date_acquisition', 'desc')
        ->get();
    return response()->json($materiels);
    }


    public function getAllMaterielVehicule()
    {
        return response()->json(Materiel::whereHas('categorie', function($query) {
            $query->where('isVehicule', true);
        })->with(['categorie', 'type', 'source', 'reference','appartenance', 'responsable', 'region', 'photos', 'utilisations'=>function($query) {
            $query->orderBy('date', 'desc');
        }])->orderBy('date_acquisition', 'desc')->get());
    }

    public function getMaterielVehiculeParIdRegion($regionId)
    {
        return response()->json(Materiel::whereHas('categorie', function($query) {
            $query->where('isVehicule', true);
        })->where('region_id', $regionId)->with(['categorie', 'type', 'source', 'reference','appartenance', 'responsable', 'region', 'photos', 'utilisations'=>function($query) {
            $query->orderBy('date', 'desc');
        }])->orderBy('date_acquisition', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
        // $reference = Reference::findOrFail($request->reference_id);
        // $count = Materiel::where('reference_id', $request->reference_id)->count();
        // $numero = $reference->nom . '-' . ($count + 1);
        
        // $data = $request->all();
        // $data['numero'] = $numero;
        // $nomType = TypeMateriel::findOrFail($request->type_id)->nom;
        // $nomRegion = Region::findOrFail($request->region_id)->nom;

        // $historique = Historique::create([
        //     'titre' => 'Gestion de stock',
        //     'description' => 'Création du matériel ' . $nomType . '-' . $request->caracteristiques . ' dans la région ' . $nomRegion,
        //     'date_heure' => now(),
        //     'user_id' => Auth::user()->id,
        // ]);
        // $historique->save();
        
        // $materiel = Materiel::create($data);
    //     return response()->json($materiel, 201);
    // }



    public function store(Request $request)
{
    $reference = Reference::findOrFail($request->reference_id);
    $count = Materiel::where('reference_id', $request->reference_id)->count();
    $numero = $reference->nom . '-' . ($count + 1);
    $data = $request->except('photos');
    $data['numero'] = $numero;
    if (isset($data['montant']) && ($data['montant'] === "" || $data['montant'] === "null")) {
        $data['montant'] = null;
    }
    if (isset($data['source_id']) && ($data['source_id'] === "" || $data['source_id'] === "null")) {
        $data['source_id'] = null;
    }
    if (isset($data['appartenance_id']) && ($data['appartenance_id'] === "" || $data['appartenance_id'] === "null")) {
        $data['appartenance_id'] = null;
    }
    $nomType = TypeMateriel::findOrFail($request->type_id)->nom;
    $nomRegion = Region::findOrFail($request->region_id)->nom;

    $historique = Historique::create([
        'titre' => 'Gestion de stock',
        'description' => 'Création du matériel ' . $nomType . '-' . $request->caracteristiques . ' dans la région ' . $nomRegion,
        'date_heure' => now(),
        'user_id' => Auth::user()->id,
    ]);
    $historique->save();
    
    $materiel = Materiel::create($data);
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $photo) {
            $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('materiels_photos', $filename, 'public');
            $materiel->photos()->create([
                'chemin' => $filename
            ]);
        }
    }

    return response()->json($materiel->load('photos'), 201);
}

    public function changeIdRegion(Request $request, string $idMateriel, string $idRegion)
    {
        $materiel = Materiel::findOrFail($idMateriel);
        $materiel->region_id = $idRegion;
        $materiel->date_transfert = $request->date_transfert;
        if ($request->responsable_id) {
            $materiel->responsable_id = $request->responsable_id;
        }
        $materiel->save();
        $nomType = TypeMateriel::findOrFail($materiel->type_id)->nom;
        $nomRegion = Region::findOrFail($idRegion)->nom;
        $historique = Historique::create([
            'titre' => 'Gestion de stock',
            'description' => 'Transfert du matériel ' . $nomType . '-' . $materiel->caracteristiques . ' vers la région ' . $nomRegion,
            'date_heure' => now(),
            'user_id' => Auth::user()->id,
        ]);
        $historique->save();
        return response()->json($materiel);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return response()->json(Materiel::with(['categorie', 'type', 'source', 'reference', 'region', 'responsable', 'photos', 'appartenance', 'utilisations'=>function($query) {
            $query->orderBy('date', 'desc');
        }])->orderBy('date_acquisition', 'desc')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $materiel = Materiel::findOrFail($id);
        
        $validFields = $request->only([
            'numero', 'categorie_id', 'type_id', 'marque', 'caracteristiques', 
            'etat', 'montant', 'numero_serie', 'numero_imei', 'region_id', 
            'responsable_id', 'date_acquisition', 'lieu_affectation', 
            'source_id', 'reference_id', 'appartenance_id'
        ]);

        $materiel->update($validFields);
        $nomType = TypeMateriel::findOrFail($materiel->type_id)->nom;
        $nomRegion = Region::findOrFail($materiel->region_id)->nom;
        $historique = Historique::create([
            'titre' => 'Gestion de stock',
            'description' => 'Modification du matériel ' . $nomType . '-' . $materiel->caracteristiques . ' dans la région ' . $nomRegion,
            'date_heure' => now(),
            'user_id' => Auth::user()->id,
        ]);
        $historique->save();

        return response()->json($materiel);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     //
    //     Materiel::destroy($id); 
    //     $materiel = Materiel::findOrFail($id);
    //     $nomType = TypeMateriel::findOrFail($materiel->type_id)->nom;
    //     $nomRegion = Region::findOrFail($materiel->region_id)->nom;
    //     $historique = Historique::create([
    //         'titre' => 'Gestion de stock',
    //         'description' => 'Suppression du matériel ' . $nomType . '-' . $materiel->caracteristiques . ' dans la région ' . $nomRegion,
    //         'date_heure' => now(),
    //         'user_id' => Auth::user()->id,
    //     ]);
    //     $historique->save();
    //     return response()->json(null, 204);
    // }

    public function destroy(string $id)
{
    $materiel = Materiel::findOrFail($id);
    $nomType = TypeMateriel::findOrFail($materiel->type_id)->nom;
    $nomRegion = Region::findOrFail($materiel->region_id)->nom;
    foreach ($materiel->photos as $photo) {
        Storage::disk('public')->delete('materiels_photos/' . $photo->chemin);
        $photo->delete();
    }
    $materiel->delete();
    Historique::create([
        'titre' => 'Gestion de stock',
        'description' => 'Suppression du matériel ' . $nomType . '-' . $materiel->caracteristiques . ' dans la région ' . $nomRegion,
        'date_heure' => now(),
        'user_id' => Auth::id(),
    ]);

    return response()->json(null, 204);
}

}
