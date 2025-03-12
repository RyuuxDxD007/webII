<?php

namespace App\Livewire;

use App\Models\Bien;
use Livewire\Component;
use Illuminate\Http\Request;

class BienListing extends Component
{
    // Déclare les propriétés publiques qui seront utilisées dans la vue et liées à la query string de l'URL.
    public $biens;
    public $typeBien, $etat;
    //ajout de la propriete pour le filtre
    public $categorieEnergie = '';

    // Configure les paramètres de query string pour synchroniser `typeBien` et `etat` avec l'URL, excluant ces paramètres lorsqu'ils sont vides.
    protected $queryString = [
        'typeBien' => ['except' => ''],
        'etat' => ['except' => ''],
        //ajout filtrage energie pour le filtre
        'categorieEnergie' => ['except' => ''],
    ];

    // Définit la méthode `loadBiens` qui charge les biens de la base de données en fonction des filtres appliqués.
    public function loadBiens()
    {
        $query = Bien::query(); // Commence avec une requête pour tous les biens.

        // Applique un filtre pour ne montrer que les biens de l'utilisateur courant si `$typeBien` est défini sur 'self'.
        if ($this->typeBien === 'self')
            $query->where("user_id", auth()->id());

        // Applique un filtre basé sur l'état de la vente ou de la location si `$etat` est spécifié.
        if ($this->etat === 'vente')
            $query->where("type_annonce_id", 1);
        elseif ($this->etat === 'location')
            $query->where("type_annonce_id", 2);

        //Applique le filtre pour l'energie
        if ($this->categorieEnergie) {
            $query->where('classe_energie', $this->categorieEnergie);
        }
        // Charge les biens filtrés et les assigne à la propriété `$biens`.
        $this->biens = $query->orderBy('lib')->get();
    }

    // Ces méthodes sont automatiquement appelées par Livewire lorsque les propriétés `typeBien` ou `etat` changent. Elles rechargent simplement les biens.
    public function updatedTypeBien($value)
    {
        $this->loadBiens();
    }

    public function updatedEtat($value)
    {
        $this->loadBiens();
    }

    //fonction qui est trigger quand le choix de filtre pour les categorie d'energie a été trigger
    public function updatedCategorieEnergie($value)
    {
        $this->loadBiens();
    }

    // La méthode `mount` est appelée au montage du composant. Elle sert ici à charger initialement les biens avec les filtres appliqués.
    public function mount(Request $request)
    {
        $this->loadBiens();
    }

    // Définit comment le composant doit être rendu, en spécifiant la vue associée et en l'étendant avec un layout.
    public function render()
    {
        return view('livewire.bien-listing')
            ->extends("layouts.app");
    }
}
