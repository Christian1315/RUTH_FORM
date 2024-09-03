<x-templates.agency :title="'Locataires'" :active="'locator'" :agency=$agency>

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Panel des Locataires <em class="text-red">à jour </em> </h1>
    </div>
    <br>

    <livewire:paid-locators :agency="$agency" />

</x-templates.agency>