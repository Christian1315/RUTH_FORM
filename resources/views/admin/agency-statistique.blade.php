<x-templates.agency :title="'Statistiques'" :active="'statistique'" :agency="$agency">

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Panel des statistiques de l'agence</h1>
    </div>
    <br>

    <livewire:agency-statistique :agency=$agency />

</x-templates.agency>