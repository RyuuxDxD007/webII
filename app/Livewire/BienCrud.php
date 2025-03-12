<?php

namespace App\Livewire;

use App\Models\Bien;
use Livewire\Component;
use App\Models\TypeBien;
use App\Models\TypeAnnonce;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads; // Importe le trait pour gérer l'upload de fichiers
use App\Models\OptionsBien;
use App\Models\TypeChauffage;

class BienCrud extends Component
{
    use WithFileUploads;

    //declaratgion des variables
    public $action;
    public $disabledForm = false;
    public $bienId, $sold, $lib, $description, $prix, $photo, $photoComplete, $classe_energie, $chambre, $sdb, $wc, $st, $sh, $type_bien_id, $type_annonce_id, $co2, $consomation_energie, $type_chauffage_id, $double_vitrage, $HVAC, $solaire, $puissance_solaire;
    public $typeAnnonce, $typeBien, $typeChauffage, $classeEnergieList = [
        1 => "A",
        2 => "B",
        3 => "C",
        4 => "D",
        5 => "E",
        6 => "F"
    ];

    public function mount($id = null)
    {
        // On définit une seule fois le type annonce et de biens
        $this->typeAnnonce = TypeAnnonce::orderBy("type_annonce")->get();
        $this->typeBien = TypeBien::orderBy("type_bien")->get();
        //charge la liste des type de chauffage
        $this->typeChauffage = TypeChauffage::OrderBy("type_chauffage")->get();

        // Si on détecte "consulter" ou "éditer"
        if ($id && $this->action != 'ajouter') {
            // On va chercher les informations de notre bien et on peuple nos variables livewire
            $bien = Bien::with('optionsBien')->findOrFail($id);
            $this->bienId = $bien->id;
            $this->sold = $bien->sold;
            $this->lib = $bien->lib;
            $this->description = $bien->description;
            $this->prix = str_replace(',', '', number_format($bien->prix, 2));
            
        //ne resortait pas comme A B C mais 1 2 3. a ete modifier pour
            $this->classe_energie = array_search($bien->classe_energie, $this->classeEnergieList);
            $this->chambre = $bien->chambre;
            $this->sdb = $bien->sdb;
            $this->wc = $bien->wc;
            $this->st = $bien->st;
            $this->sh = $bien->sh;
            $this->type_bien_id = $bien->type_bien_id;
            $this->type_annonce_id = $bien->type_annonce_id;

            //peuple les variables livewire de options bien
            $this->co2 = $bien->optionsBien->co2;
            $this->consomation_energie = $bien->optionsBien->consomation_energie;
            //charge l'id du type de chauffage 
            $this->type_chauffage_id = $bien->optionsBien->type_chauffage_id;
            $this->double_vitrage = $bien->optionsBien->double_vitrage;
            $this->HVAC = $bien->optionsBien->HVAC;
            $this->solaire = $bien->optionsBien->solaire;
            $this->puissance_solaire = $bien->optionsBien->puissance_solaire;

            $this->photo = $bien->photo;
            $this->photoComplete = $bien->photoComplete;
        }

        if ($this->action == 'consulter') {
            // Si c'est une consultation de bien, on passe notre variable à true
            // Et dans la vue, on a une condition sur chaque champ qui les désactive
            $this->disabledForm = true;
        }
    }

    //fonction annuler qui renvoie en arriere
    public function cancel()
    {
        $this->redirect(request()->header('Referer'));
    }

    public function save()
    {
        //defini les regles 
        $rules = [
            'sold' => 'nullable',
            'lib' => 'required',
            'photo' => ['sometimes'],
            'description' => 'nullable',
            'prix' => 'required|numeric',
            'classe_energie' => 'required',
            'chambre' => 'required|integer',
            'sdb' => 'required|integer',
            'wc' => 'required|integer',
            'st' => 'required|integer',
            'sh' => 'required|integer',
            'co2' => 'required|integer',
            'consomation_energie' => 'required|integer',
            'type_chauffage_id' => 'required',
            'HVAC' => 'nullable',
            'solaire' => 'nullable',
            'double_vitrage' => 'nullable',
            'puissance_solaire' => 'required_if:solaire,1|nullable|integer',
            'type_bien_id' => 'required',
            'type_annonce_id' => 'required',
        ];

        // Ajouter les règles de validation spécifiques pour 'ajouter'
        if ($this->action == 'ajouter')
            $rules['photo'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];
        elseif ($this->action == 'modifier' && !is_string($this->photo))
            // Pour l'édition, si 'photo' est un fichier téléchargé et non une chaîne
            $rules['photo'] = ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];

            //defini les message d'erreurs
        $this->validate($rules, [
            'lib.required' => 'Le champ libellé est obligatoire',
            'photo.required' => 'La photo est obligatoire',
            'photo.image' => 'Le fichier doit être une image',
            'sh.required' => 'Le champ superficie habitable est obligatoire',
            'sh.integer' => 'Le champ superficie habitable doit être un chiffre',
            'st.required' => 'Le champ superficie terrain est obligatoire',
            'st.integer' => 'Le champ superficie terrain doit être un chiffre',
            'chambre.integer' => 'Le champ chambre doit être un chiffre',
            'sdb.integer' => 'Le champ sdb doit être un chiffre',
            'wc.integer' => 'Le champ wc doit être un chiffre',
            'co2.required' => 'Les emissions de CO2 sont obligatoires',
            'co2.integer' => 'La quantite emisse de CO2 est un chiffre',
            'consomation_energie.required' => 'La consomation totale energetique est obligatoire',
            'consomation_energie.integer' => 'La consomation totale energetique est un chiffre',
            'type_chauffage_id.required' => 'Le type de chauffage est obligatoire',
            'puissance_solaire.integer' => 'La puissance solaire est un chiffre',
            'puissance_solaire.required_if' => 'La puissance solaire est obligatoire',
            'type_bien_id.required' => 'Le type de bien est obligatoire',
            'type_annonce_id.required' => 'Le type d\'annonce est obligatoire',
            'classe_energie.required' => 'La classe énergétique est obligatoire',
            'required' => 'Le champ :attribute est obligatoire'
        ]);

        // Si on détecte un ID dans l'url, c'est que c'est une édition, si pas, c'est un ajout
        $bien = $this->bienId ? Bien::findOrFail($this->bienId) : new Bien;

        // Si ce n'est pas une édition ou qu'on détecte une nouvelle photo
        if (!$this->bienId || !is_string($this->photo)) {
            // Si une nouvelle photo a été téléchargée, supprimer l'ancienne photo du dossier
            if ($bien->photo)
                Storage::delete('public/images/' . $bien->photo);

            // Stocker la nouvelle photo et mettre à jour le nom du fichier dans $bien->photo
            $imageName = time() . '.' . $this->photo->extension();
            $this->photo->storeAs('public/images', $imageName);
            $bien->photo = $imageName;
        }
        //si solaire n'est pas selectionner remet la valeur a null
        if (!$this->solaire) {
            $this->puissance_solaire = null;
        }

        $bien->sold = $this->sold ?? 0;
        $bien->lib = $this->lib;
        $bien->description = $this->description;
        $bien->prix = $this->prix;
        //n'enregistrait pas comme A B C mais 1 2 3. a ete modifier pour
        $bien->classe_energie = $this->classeEnergieList[$this->classe_energie];
        $bien->chambre = $this->chambre;
        $bien->sdb = $this->sdb;
        $bien->wc = $this->wc;
        $bien->st = $this->st;
        $bien->sh = $this->sh;
        $bien->user_id = Auth::id();
        $bien->type_bien_id = $this->type_bien_id;
        $bien->type_annonce_id = $this->type_annonce_id;
        $bien->save();

        //enregistre les options biens
        $options = $bien->optionsBien ?? new OptionsBien();
        $options->bien_id = $bien->id;
        $options->co2 = $this->co2;
        $options->consomation_energie = $this->consomation_energie;
        $options->type_chauffage_id = $this->type_chauffage_id;
        $options->double_vitrage = $this->double_vitrage ?? 0;
        $options->HVAC = $this->HVAC ?? 0;
        $options->solaire = $this->solaire ?? 0;
        $options->puissance_solaire = $this->puissance_solaire;

        $options->save();

        session()->flash('status', $this->bienId ? 'Bien mis à jour avec succès.' : 'Bien créé avec succès.');
        return redirect()->route('biens.index');
    }

    public function render()
    {
        return view('livewire.bien-crud')->extends("layouts.app");
    }

    //fonction qui ajoute le dynamisme au champs de puissance solaire
    public function toggleSolaire()
    {
        if (!$this->disabledForm) {
            $this->solaire = !$this->solaire;
        }
    }
}
