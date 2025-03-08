<div>
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 col-lg-6">
                <div class="btn-group" role="group">
                    <button wire:click="$set('typeBien', 'all')" class="btn btn-outline-primary {{ $typeBien == 'all' || is_null($typeBien) ? 'active' : '' }}">Tous les biens</button>
                    <button wire:click="$set('typeBien', 'self')" class="btn btn-outline-primary {{ $typeBien == 'self' ? 'active' : '' }}">Mes biens</button>
                </div>
            </div>
            <div class="col-12 col-lg-6 d-flex align-items-center justify-content-end">
                <div class="btn-group" role="group">
                    <button wire:click="$set('etat', 'tout')" class="btn btn-outline-primary {{ $etat == 'tout' || is_null($etat) ? 'active' : '' }}">Tout</button>
                    <button wire:click="$set('etat', 'vente')" class="btn btn-outline-primary {{ $etat == 'vente' ? 'active' : '' }}">Vente</button>
                    <button wire:click="$set('etat', 'location')" class="btn btn-outline-primary {{ $etat == 'location' ? 'active' : '' }}">Location</button>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-end align-items-center">
                <label for="categorieEnergie" class="me-2">Filtre :</label>
                <select wire:model.live="categorieEnergie" id="categorieEnergie" class="form-select" style="width: auto;">
                    <option value="">Toutes les catégories énergétiques</option>
                    @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            @foreach($biens as $bien)
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <img src="{{ $bien->photoComplete }}" class="card-img-top" alt="{{ $bien->lib }}">
                    <div class="card-header">
                        {{ $bien->lib }}
                    </div>
                    <div class="card-body">
                        <p>{{ $bien->description }}</p>
                        <div class="row">
                            <div class="col">
                                <p>{{ $bien->typeBien->type_bien }}</p>
                            </div>
                            <div class="col text-end">
                                <p>{{ number_format($bien->prix, 2) }} €</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                {!! $bien->typeAnnonceHtml !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <p>Catégorie Énergétique:</p>
                            </div>
                            <div class="col text-end">
                                <p>{{ $bien->classe_energie }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                @if($bien->user_id == auth()->id())
                                <a href="{{ route("biens.edit", ["id" => $bien->id]) }}" class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z" />
                                    </svg> Modifier
                                </a>
                                @else
                                <a href="{{ route("biens.consulter", ["id" => $bien->id]) }}" class="btn btn-secondary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                        <path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z" />
                                    </svg> Consulter
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if(Session::has('status'))
        <div class="alert alert-success alert-bottom mt-4" role="alert">{{Session::get('status')}} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        @endif
        @if (Session::has('error'))
        <div class="alert alert-danger alert-bottom mt-4" role="alert">{{Session::get('error')}} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        @endif
    </div>
</div>