<?php

use App\Http\Controllers\Api\V1\ActionController;
use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\Authorization;
use App\Http\Controllers\Api\V1\IMMO\ActivityDomainController;
use App\Http\Controllers\Api\V1\IMMO\AgencyController;
use App\Http\Controllers\Api\V1\IMMO\AreaControlller;
use App\Http\Controllers\Api\V1\IMMO\CardTypeController;
use App\Http\Controllers\Api\V1\IMMO\CityController;
use App\Http\Controllers\Api\V1\IMMO\ClientController;
use App\Http\Controllers\Api\V1\IMMO\ClientTypeController;
use App\Http\Controllers\Api\V1\IMMO\CounterStatusController;
use App\Http\Controllers\Api\V1\IMMO\CounterTypeController;
use App\Http\Controllers\Api\V1\IMMO\CountryController;
use App\Http\Controllers\Api\V1\IMMO\CurrencyController;
use App\Http\Controllers\Api\V1\IMMO\DepartementController;
use App\Http\Controllers\Api\V1\IMMO\FactureStatusController;
use App\Http\Controllers\Api\V1\IMMO\FactureTypeController;
use App\Http\Controllers\Api\V1\IMMO\HouseController;
use App\Http\Controllers\Api\V1\IMMO\HouseTypeController;
use App\Http\Controllers\Api\V1\IMMO\ImmoAccountController;
use App\Http\Controllers\Api\V1\IMMO\LocataireController;
use App\Http\Controllers\Api\V1\IMMO\LocationController;
use App\Http\Controllers\Api\V1\IMMO\LocationTypeController;
use App\Http\Controllers\Api\V1\IMMO\PaiementModuleController;
use App\Http\Controllers\Api\V1\IMMO\PaiementStatusController;
use App\Http\Controllers\Api\V1\IMMO\PaiementTypeController;
use App\Http\Controllers\Api\V1\IMMO\PayementController;
use App\Http\Controllers\Api\V1\IMMO\ProprietorController;
use App\Http\Controllers\Api\V1\IMMO\QuarterController;
use App\Http\Controllers\Api\V1\IMMO\RoomController;
use App\Http\Controllers\Api\V1\IMMO\RoomNatureController as IMMORoomNatureController;
use App\Http\Controllers\Api\V1\IMMO\RoomTypeController;
use App\Http\Controllers\Api\V1\IMMO\ZoneController;
use App\Http\Controllers\Api\V1\IMMO\FactureController;
use App\Http\Controllers\Api\V1\IMMO\HouseStopStateController;
use App\Http\Controllers\Api\V1\IMMO\LocationElectrictyFactureController;
use App\Http\Controllers\Api\V1\IMMO\LocationWaterFactureController;
use App\Http\Controllers\Api\V1\IMMO\ManageAccountController;
use App\Http\Controllers\Api\V1\IMMO\PaiementInitiationController as IMMOPaiementInitiationController;
use App\Http\Controllers\Api\V1\IMMO\PaiementInitiationStatusController;
use App\Http\Controllers\Api\V1\IMMO\StopHouseElectricityStateController;
use App\Http\Controllers\Api\V1\IMMO\StopHouseWaterStateController;
use App\Http\Controllers\Api\V1\MemberController;

use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\OrganisationController;
use App\Http\Controllers\Api\V1\TeamController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\TicketStatusController;

use App\Http\Controllers\Api\V1\STOCK\ProductController;
use App\Http\Controllers\Api\V1\STOCK\ProductCategoryController;
use App\Http\Controllers\Api\V1\STOCK\ProductTypeController;
use App\Http\Controllers\Api\V1\STOCK\EtiquetteController;
use App\Http\Controllers\Api\V1\STOCK\LogistiqueController;
use App\Http\Controllers\Api\V1\STOCK\MarketeurController;
use App\Http\Controllers\Api\V1\STOCK\ProductStockController;
use App\Http\Controllers\Api\V1\STOCK\ExploitationController;
use App\Http\Controllers\Api\V1\STOCK\ChargementController;

use App\Http\Controllers\Api\V1\MINISTERS\RepertoryController;
use App\Http\Controllers\Api\V1\ProfilController;
use App\Http\Controllers\Api\V1\RangController;
use App\Http\Controllers\Api\V1\RightController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
*/

###___
Route::prefix('v1')->group(function () {

    ###========== PROFILS ROUTINGS ========###
    Route::controller(ProfilController::class)->group(function () {
        Route::prefix('profil')->group(function () {
            Route::any('add', 'CreateProfil'); #AJOUT DE PROFIL
            Route::any('all', 'Profils'); #RECUPERATION DE TOUT LES PROFILS
            Route::any('{id}/retrieve', 'RetrieveProfil'); #RECUPERATION D'UN PROFIL
            Route::any('{id}/update', 'UpdateProfil'); #MODIFICATION D'UN PROFIL
            Route::any('{id}/delete', 'DeleteProfil'); #SUPPRESSION D'UN PROFIL
        });
    });

    ###========== RANG ROUTINGS ========###
    Route::controller(RangController::class)->group(function () {
        Route::prefix('rang')->group(function () {
            Route::any('add', 'CreateRang'); #AJOUT DE RANG
            Route::any('all', 'Rangs'); #RECUPERATION DE TOUT LES RANGS
            Route::any('{id}/retrieve', 'RetrieveRang'); #RECUPERATION D'UN RANG
            Route::any('{id}/delete', 'DeleteRang'); #SUPPRESSION D'UN RANG
            Route::any('{id}/update', 'UpdateRang'); #MODIFICATION D'UN RANG'
        });
    });

    ###========== ACTION ROUTINGS ========###
    Route::controller(ActionController::class)->group(function () {
        Route::prefix('action')->group(function () {
            Route::any('add', 'CreateAction'); #AJOUT D'UNE ACTION'
            Route::any('all', 'Actions'); #GET ALL ACTIONS
            Route::any('{id}/retrieve', 'RetrieveAction'); #RECUPERATION D'UNE ACTION
            Route::any('{id}/delete', 'DeleteAction'); #SUPPRESSION D'UNE ACTION
            Route::any('{id}/update', 'UpdateAction'); #MODIFICATION D'UNE ACTION
        });
    });

    ###========== RIGHTS ROUTINGS ========###
    Route::controller(RightController::class)->group(function () {
        Route::prefix('right')->group(function () {
            Route::any('add', 'CreateRight'); #AJOUT D'UN DROIT'
            Route::any('all', 'Rights'); #GET ALL RIGHTS
            Route::any('{id}/retrieve', 'RetrieveRight'); #RECUPERATION D'UN DROIT
            Route::any('{id}/delete', 'DeleteRight'); #SUPPRESSION D'UN DROIT
            Route::any('search', '_Search');
        });
    });

    ###========== ROLES ROUTINGS ========###
    Route::controller(RoleController::class)->group(function () {
        Route::prefix('role')->group(function () {
            Route::any('add', 'CreateRole'); #AJOUT D'UN ROLE'
            Route::any('all', 'Roles'); #GET ALL ROLE
            Route::any('{id}/retrieve', 'RetrieveRole'); #RECUPERATION D'UN ROLE

            Route::any('attach-user', 'AttachRoleToUser'); #Attacher un droit au user 
            Route::any('desattach-user', 'DesAttachRoleToUser'); #Attacher un droit au user 
        });
    });

    ###========== USERs ROUTINGS ========###
    Route::controller(UserController::class)->group(function () {
        Route::prefix("user")->group(function () {
            Route::any('login', 'Login');
            Route::middleware(['auth:api'])->get('logout', 'Logout');
            Route::any('add', 'AddUser');
            Route::any('users', 'Users');
            Route::any('users/{id}', 'RetrieveUser');
            Route::any('{id}/password/update', 'UpdatePassword');
            Route::any('{id}/delete', 'DeleteAccount');

            Route::any('{id}/archive', 'ArchiveAccount');
            Route::any('{id}/duplicate', 'DuplicatAccount');

            Route::any('password/demand_reinitialize', 'DemandReinitializePassword');
            Route::any('password/reinitialize', 'ReinitializePassword');
            Route::any('{id}/update', 'UpdateCompte');

            Route::any('supervisors', 'GetAllSupervisors');
            Route::any('accountAgents', 'GetAllAccountAgents');

            Route::any('account_agents', 'GetAllAccountAgents');
            Route::any('attach-supervisor-to-agent_account', 'AffectSupervisorToAccountyAgent'); #Affecter un superviseur à un agent comptable
            Route::any('dettach-supervisor-to-agent_account', 'DetachSupervisorToAccountyAgent'); #Detacher un superviseur d'un agent comptable

            Route::any('attach-user', 'AttachRightToUser'); #Attacher un droit au user 
            Route::any('desattach-user', 'DesAttachRightToUser'); #Attacher un droit au user 

            Route::any('{userId}/search', 'SearchUser'); #RECHERCHER UN UTILISATEUR
        });
    });

    Route::any('authorization', [Authorization::class, 'Authorization'])->name('authorization');

    ######################## MODULE IMMO ##############################
    Route::prefix('immo')->group(function () {
        ###========== COUNTRY ========###
        Route::prefix("country")->group(function () {
            Route::controller(CountryController::class)->group(function () {
                Route::any('all', 'Countries'); #RECUPERATION DE TOUT LES PAYS
                Route::any('{id}/retrieve', 'RetrieveCountrie'); #RECUPERATION D'UN PAYS
            });
        });

        ###========== DOMAIN ACTIVITY ========###
        Route::prefix("activity_domain")->group(function () {
            Route::controller(ActivityDomainController::class)->group(function () {
                Route::any('all', 'ActivityDomains'); #RECUPERATION DE TOUT LES DOMAINES D'ACTIVITE
                Route::any('{id}/retrieve', 'RetrieveActivityDomain'); #RECUPERATION D'UN DOMAINE D'ACTIVITE
            });
        });

        ###========== CITY ========###
        Route::prefix("city")->group(function () {
            Route::controller(CityController::class)->group(function () {
                Route::any('all', 'Cities'); #RECUPERATION DE TOUTES LES VILLES
                Route::any('{id}/retrieve', '_RetrieveCity'); #RECUPERATION D'UNE VILLE
            });
        });
        ##___

        ###========== AREA ========###
        Route::prefix("area")->group(function () {
            Route::controller(AreaControlller::class)->group(function () {
                Route::any('all', 'Areas'); #RECUPERATION D'UN TERRITOIRE
                Route::any('{id}/retrieve', '_RetrieveArea'); #RECUPERATION D'UN TERRITOIRE 
            });
        });
        ##___

        ###========== CURRENCY ========###
        Route::prefix("currency")->group(function () {
            Route::controller(CurrencyController::class)->group(function () {
                Route::any('all', 'Currencies'); #RECUPERATION DE TOUTES LES DEVISES
                Route::any('{id}/retrieve', '_RetrieveCurrency'); #RECUPERATION D'UNE DEVISE
            });
        });
        ##___

        ###========== DEPARTEMENT ========###
        Route::prefix("departement")->group(function () {
            Route::controller(DepartementController::class)->group(function () {
                Route::any('all', 'Departements'); #RECUPERATION DE TOUT LES DEPARTEMENTS
                Route::any('{id}/retrieve', '_RetrieveDepartement'); #RECUPERATION D'UN DEPARTEMENT
            });
        });
        ##___


        ###========== TYPES DE COMPTEUR ========###
        Route::prefix("counterType")->group(function () {
            Route::controller(CounterTypeController::class)->group(function () {
                Route::any('all', 'CounterTypes'); #RECUPERATION DE TOUT LES TYPES DE COMPTEUR
                Route::any('{id}/retrieve', '_RetrieveCounterType'); #RECUPERATION D'UN TYPE DE COMPTEUR
            });
        });
        ##___

        ###========== TYPES DE CARTES ========###
        Route::prefix("cardType")->group(function () {
            Route::controller(CardTypeController::class)->group(function () {
                Route::any('all', 'CardTypes'); #RECUPERATION DE TOUT LES TYPES DE CARTE
                Route::any('{id}/retrieve', '_RetrieveCardType'); #RECUPERATION D'UN TYPE DE CARTE
            });
        });
        ##___

        ###========== STATUS DE COMPTEUR ======== ###
        Route::prefix("counterStatus")->group(function () {
            Route::controller(CounterStatusController::class)->group(function () {
                Route::any('all', 'CounterStatus'); ## RECUPERATION DE TOUT LES STATUS DE COMPTEUR
                Route::any('{id}/retrieve', '_RetrieveCounterStatus'); ## RECUPERATION D'UN STATUS DE COMPTEUR
            });
        });
        ##___

        ###========== DEPARTEMENTS ======== ###
        Route::prefix("departement")->group(function () {
            Route::controller(DepartementController::class)->group(function () {
                Route::any('all', 'Departements'); ## RECUPERATION DE TOUT LES DEPARTEMENTS
                Route::any('{id}/retrieve', '_RetrieveDepartement'); ## RECUPERATION D'UN DEPARTEMENT
            });
        });
        ##___

        ###========== ZONES ======== ###
        Route::prefix("zone")->group(function () {
            Route::controller(ZoneController::class)->group(function () {
                Route::any('all', 'Zones'); ## RECUPERATION DE TOUTES LES ZONES
                Route::any('{id}/retrieve', '_RetrieveZone'); ## RECUPERATION D'UNE ZONE
            });
        });
        ##___

        ###========== QUARTIERS ======== ###
        Route::prefix("quarter")->group(function () {
            Route::controller(QuarterController::class)->group(function () {
                Route::any('all', 'Quarters'); ## RECUPERATION DE TOUT LES QUARTIERS
                Route::any('{id}/retrieve', '_RetrieveQuarter'); ## RECUPERATION D'UN QUARTIER
            });
        });
        ##___

        ###========== AGENCY ROUTINGS ========###
        Route::controller(AgencyController::class)->group(function () {
            Route::prefix('agency')->group(function () {
                Route::any('add', 'AddAgency'); #AJOUT D'UN AGENCY
                Route::any('all', 'Agencys'); #GET ALL AGENCYS
                Route::any('{id}/retrieve', 'RetrieveAgency'); #RECUPERATION D'UN AGENCY
                Route::any('{id}/delete', 'DeleteAgency'); #SUPPRESSION D'UN AGENCY
                Route::any('{id}/update', 'UpdateAgency'); #MODIFICATION D'UN AGENCY
                Route::any('search', 'SearchAgency'); #RECHERCHE D'UNE AGENCE

                Route::any('{id}/supervisors', 'GetAgencySupervisors');
            });
        });

        ###========== ACCOUNT ======== ###
        Route::prefix("account")->group(function () {
            Route::controller(ImmoAccountController::class)->group(function () {
                Route::any('all', 'Accounts'); ## RECUPERATION DE TOUT LES COMPTES
                Route::any('{id}/retrieve', '_RetrieveAccount'); ## RECUPERATION D'UN COMPTE
            });


            ##__ACCOUNT_SOLD MANAGEMENT
            Route::prefix("sold")->group(function () {
                ###__
                Route::controller(AgencyController::class)->group(function () {
                    Route::any('creditate', '_CreditateAccount'); ## CREDITATION DU SOLDE DU COMPTE D'UNE AGENCE
                    Route::any('decreditate', '_DeCreditateAccount'); ## DECREDITATION DU SOLDE DU COMPTE D'UNE AGENCE
                });
            });
        });
        ##___

        ##========__PAIEMENT INITIATION MANAGEMENT======
        Route::prefix("payement_initiation")->group(function () {
            Route::prefix("status")->group(function () {
                Route::controller(PaiementInitiationStatusController::class)->group(function () {
                    Route::any('all', 'PaiementInitiationStatus'); ## RECUPERATION DE TOUT LES STATUS D'INITIATION DE PAYEMENT
                    Route::any('{id}/retrieve', '_RetrievePaiementInitiationStatus'); ## RECUPERATION D'UN STATUS D'INITIATION DE PAYEMENT
                });
            });

            Route::controller(IMMOPaiementInitiationController::class)->group(function () {
                Route::any('initiateToProprio', 'InitiatePaiementToProprietor'); ## INITIER UN PAIEMENT A UN PROPRIETAIRE
                Route::any('all', 'PaiementInitiations'); ## RECUPERATION DES INITIATIONS DE PAIEMENTS
                Route::any('{id}/retrieve', 'RetrievePaiementInitiation'); ## RECUPERATION D'UNE INITIATION DE PAIEMENT
                Route::any('{id}/valide', 'ValidePaiementInitiation'); ## MODIFICATION D'UNE INITIATION DE PAIUEMENT
                Route::any('{id}/rejet', 'RejetPayementInitiation'); ## MODIFICATION D'UNE INITIATION DE PAIUEMENT
            });
        });

        ###========== PROPRIETAIRE ========###
        Route::prefix("proprietor")->group(function () {
            Route::controller(ProprietorController::class)->group(function () {
                Route::any('add', '_AddProprietor'); #AJOUT D'UN PROPRIETAIRE
                Route::any('all', 'Proprietors'); #RECUPERATION DE TOUT LES PROPRIETAIRES
                Route::any('{id}/retrieve', 'RetrieveProprietor'); #RECUPERATION D'UN PROPRIETAIRE
                Route::any('{id}/update', 'UpdateProprietor'); # MODIFICATION D'UN PROPRIETAIRE 
                Route::any('{id}/delete', 'DeleteProprietor'); # SUPPRESSION D'UN PROPRIETAIRE  
                Route::any('search', 'SearchProprietor'); #RECHERCHER UN PROPRIO
            });
        });
        ##___

        ###========== HOUSE ========###
        Route::prefix("house")->group(function () {
            Route::prefix("type")->group(function () {
                Route::controller(HouseTypeController::class)->group(function () {
                    Route::any('add', 'AddHouseType'); #AJOUT D'UN TYPE DE MAISON
                    Route::any('all', 'HouseTypes'); #RECUPERATION DE TOUT LES TYPES DE MAISONS
                    Route::any('{id}/retrieve', '_RetrieveHouseType'); #RECUPERATION D'UN TYPE DE MAISON
                });
            });

            Route::controller(HouseController::class)->group(function () {
                Route::any('add', '_AddHouse'); #AJOUT D'UNE MAISON
                Route::any('all', 'Houses'); #RECUPERATION DES MAISONS
                Route::any('{agencyId}/all', 'AgenciesHouses'); #RECUPERATION DES MAISONS D'UNE AGENCE
                Route::any('{agencyId}/last_state/all', 'AgenciesHousesForTheLastState'); #RECUPERATION DES MAISONS D'UNE AGENCE en considerant son dernier arre^t d'etat
                Route::any('{id}/retrieve', 'RetrieveHouse'); #RECUPERATION D'UNE MAISON
                Route::any('{id}/update', 'UpdateHouse'); # MODIFICATION D'UNE MAISON  
                Route::any('{id}/delete', 'DeleteHouse'); # SUPPRESSION D'UNE MAISON  

                Route::any('search', 'SearchHouse'); #RECHERCHER UNE HOUSE

                Route::any('{agencyId}/{supervisor}/{house}/{action}/performance', "HousePerformance"); # La performance dans une maison 
                Route::any('{houseId}/imprime_last_state', "ImprimeHouseLastState"); # DERNIER ETAT D' une maison 
            });
        });
        ##___

        ##=========__ ARRETER LES ETATS DES MAISON ======
        Route::prefix("house_state")->group(function () {
            Route::controller(HouseStopStateController::class)->group(function () {
                Route::any('stop', '_StopStatsOfHouse');
                Route::any('house/{houseId}/all', 'RetrieveHouseStates');
                Route::any('{id}/retrieve', 'RetrieveState');
                Route::any('all', 'GetAllStates');
            });
        });


        ###========== ROOM ========###
        Route::prefix("room")->group(function () {
            Route::prefix("type")->group(function () {
                Route::controller(RoomTypeController::class)->group(function () {
                    Route::any('add', 'AddType'); ##__AJOUT D'UN TYPE DE CHAMBRE
                    Route::any('all', 'RoomTypes'); #RECUPERATION DE TOUT LES TYPES DE CHAMBRES
                    Route::any('{id}/retrieve', '_RetrieveRoomType'); #RECUPERATION D'UN TYPE DE CHAMBRE
                });
            });

            Route::prefix("nature")->group(function () {
                Route::controller(IMMORoomNatureController::class)->group(function () {
                    Route::any('add', 'AddNature'); ##__AJOUT DE NATURE
                    Route::any('all', 'RoomNatures'); #RECUPERATION DE TOUTES LES NATURES DE CHAMBRES
                    Route::any('{id}/retrieve', '_RetrieveRoomNature'); #RECUPERATION D'UNE NATURE DE CHAMBRE
                });
            });

            Route::controller(RoomController::class)->group(function () {
                Route::any('add', '_AddRoom'); #AJOUT D'UNE CHAMBRE
                Route::any('all', 'Rooms'); #RECUPERATION D'UNE CHAMBRE
                Route::any('{id}/retrieve', 'RetrieveRoom'); #RECUPERATION D'UNE CHAMBRE
                Route::any('{id}/update', 'UpdateRoom'); #RECUPERATION D'UNE CHAMBRE 
                Route::any('{id}/delete', 'DeleteRoom'); #SUPPRESSION D'UNE CHAMBRE 

                Route::any('search', 'SearchRoom'); #RECHERCHER UNE ROOM
            });
        });
        ##___

        ###========== LOCATAIRE ========###
        Route::prefix("locataire")->group(function () {
            Route::controller(LocataireController::class)->group(function () {
                Route::any('add', '_AddLocataire'); #AJOUT D'UN LOCATAIRE
                Route::any('all', 'Locataires'); #RECUPERATION D'UN LOCATAIRE
                Route::any('{agency}/all', 'AgencyLocataires'); #RECUPERATION DES LOCATAIRES D'UNE AGENCE
                Route::any('{agency}/{action}/{supervisor}/{house}/paid', 'PaidLocataires'); #RECUPERATION D'UN LOCATAIRE
                Route::any('{agency}/{action}/{supervisor}/{house}/unpaid', 'UnPaidLocataires'); #RECUPERATION D'UN LOCATAIRE
                Route::any('{id}/retrieve', 'RetrieveLocataire'); #RECUPERATION D'UN LOCATAIRE
                Route::any('{id}/update', 'UpdateLocataire'); #RECUPERATION D'UN LOCATAIRE 
                Route::any('{id}/delete', 'DeleteLocataire'); #SUPPRESSION D'UN LOCATAIRE
                Route::any('search', 'SearchLocataire'); #RECHERCHER UN LOCATAIRE

                Route::any('{agencyId}/recovery_05_to_echeance_date', 'Recovery05ToEcheanceDate'); ###_______
                Route::any('{agencyId}/recovery_10_to_echeance_date', 'Recovery10ToEcheanceDate'); ###_______
                Route::any('{agencyId}/recovery_qualitatif', 'RecoveryQualitatif'); ###_______

                // imprimer les taux de recouvrement 05
                Route::any("{agencyId}/imprime_taux_05_agency", "_ImprimeAgencyTaux05");
                Route::any("{agencyId}/{supervisor}/imprime_taux_05_supervisor", "_ImprimeAgencyTaux05_Supervisor");
                Route::any("{agencyId}/{house}/imprime_taux_05_house", "_ImprimeAgencyTaux05_House");

                // imprimer les taux de recouvrement 10
                Route::any("{agencyId}/imprime_taux_10_agency", "_ImprimeAgencyTaux10");
                Route::any("{agencyId}/{supervisor}/imprime_taux_10_supervisor", "_ImprimeAgencyTaux10_Supervisor");
                Route::any("{agencyId}/{house}/imprime_taux_10_house", "_ImprimeAgencyTaux10_House");

                // imprimer les taux de recouvrement qualitatif
                Route::any("{agencyId}/imprime_taux_qualitatif_agency", "_ImprimeAgencyTauxQualitatif");
                Route::any("{agencyId}/{supervisor}/imprime_taux_qualitatif_supervisor", "_ImprimeAgencyTauxQualitatif_Supervisor");
                Route::any("{agencyId}/{house}/imprime_taux_qualitatif_house", "_ImprimeAgencyTauxQualitatif_House");
            });
        });
        ##___

        ###========== LOCATION ========###
        Route::prefix("location")->group(function () {
            Route::prefix("type")->group(function () {
                Route::controller(LocationTypeController::class)->group(function () {
                    Route::any('add', 'AddType'); ##__AJOUT DE TYPE DE LOCATION
                    Route::any('all', 'LocationTypes'); #RECUPERATION DE TOUT LES TYPES DE LOCATIONS
                    Route::any('{id}/retrieve', '_RetrieveLocationType'); #RECUPERATION D'UN TYPE DE LOCATION
                });
            });

            Route::controller(LocationController::class)->group(function () {
                Route::any('add', '_AddLocation'); #AJOUT D'UNE LOCATION
                Route::any('all', 'Locations'); #RECUPERATION DE TOUTES LES LOCATIONS
                Route::any('{id}/retrieve', 'RetrieveLocation'); #RECUPERATION D'UNE LOCATION
                Route::any('{id}/update', 'UpdateLocation'); #RECUPERATION D'UNE LOCATION
                Route::any('{id}/delete', 'DeleteLocation'); #SUPPRESSION D'UNE LOCATION 
                Route::any('{id}/demenage', 'DemenageLocation'); #DEMENAGEMENT D'UNE LOCATION 
                Route::any('search', 'SearchLocation'); #RECHERCHER UNE LOCATION

                ###___electricite
                Route::any('agency/{agencyId}/electricity', 'ElectricityLocations'); ##__RECUPERATION DES LOCATIONS AYANT D'ELECTRICITE
                ###___eau
                Route::any('agency/{agencyId}/water', 'WaterLocations'); ##__RECUPERATION DES LOCATIONS AYANT D'EAU
            });

            Route::controller(LocationController::class)->group(function () {
                Route::any("{agencyId}/generate_cautions", "_ManageCautions"); #GENERATE HOUSE CAUTION 
                Route::any("generate_cautions_by_period", "_ManageCautionsByPeriod");
                Route::any("{houseId}/generate_cautions_by_house", "_ManageCautionsByHouse");
                Route::any("{houseId}/generate_cautions_for_house_by_period", "_ManageCautionsForHouseByPeriod");
                Route::any("{houseId}/generate_cautions_for_house_by_period", "_ManageCautionsForHouseByPeriod");
                Route::get("{agencyId}/prestation_statistique", "_ManagePrestationStatistique");
                Route::get("{agencyId}/{first_date}/{last_date}/prestation_statistique_for_agency_by_period", "_ManagePrestationStatistiqueForAgencyByPeriod");
                Route::any("{agencyId}/{houseId}/{action}/imprime_states", "_ImprimeStates");
                Route::any("{houseId}/{action}/imprime_states_all_system", "_ImprimeStatesForAllSystem");
            });
        });
        ##___

        ###========== CLIENT ========###
        Route::prefix("client")->group(function () {
            Route::prefix("type")->group(function () {
                Route::controller(ClientTypeController::class)->group(function () {
                    Route::any('all', 'ClientTypes'); #RECUPERATION DE TOUT LES TYPES DE CLIENTS
                    Route::any('{id}/retrieve', '_RetrieveClientType'); #RECUPERATION D'UN TYPE DE CLIENTS
                });
            });

            Route::controller(ClientController::class)->group(function () {
                Route::any('all', 'Clients'); #RECUPERATION DES CLIENTS
                Route::any('{id}/retrieve', 'RetrieveClient'); #RECUPERATION D'UN CLIENT
                Route::any('{id}/delete', 'DeleteClient'); # SUPPRESSION D'UN CLIENT
                Route::any('search', 'SearchClient'); #RECHERCHER UN CLIENT
            });
        });
        ##___

        ###========== PAIEMENT ========###
        Route::prefix("paiement")->group(function () {
            Route::prefix("type")->group(function () {
                Route::controller(PaiementTypeController::class)->group(function () {
                    Route::any('all', 'PaiementTypes'); #RECUPERATION DE TOUT LES TYPES DE PAIEMENT
                    Route::any('{id}/retrieve', '_RetrievePaiementType'); #RECUPERATION D'UN TYPE DE PAIEMENT
                });
            });

            Route::prefix("status")->group(function () {
                Route::controller(PaiementStatusController::class)->group(function () {
                    Route::any('all', 'PaiementStatus'); #RECUPERATION DE TOUT LES STATUS DE PAIEMENT
                    Route::any('{id}/retrieve', '_RetrievePaiementStatus'); #RECUPERATION D'UN STATU DE PAIEMENT
                });
            });

            Route::prefix("module")->group(function () {
                Route::controller(PaiementModuleController::class)->group(function () {
                    Route::any('all', 'PaiementModules'); #RECUPERATION DE TOUT LES MODULES DE PAIEMENT
                    Route::any('{id}/retrieve', '_RetrievePaiementModule'); #RECUPERATION D'UN MODULE DE PAIEMENT
                });
            });

            Route::controller(PayementController::class)->group(function () {
                Route::any('add', '_AddPaiement'); #AJOUT D'UN PAIEMENT
                Route::any('all', 'Paiements'); #RECUPERATION DE TOUT LES PAIEMENTS
                Route::any('{id}/retrieve', 'RetrievePaiement'); #RECUPERATION D'UN PAIEMENT
                Route::any('{id}/update', 'UpdatePaiement'); #RECUPERATION D'UN PAIEMENT

                Route::controller(AgencyController::class)->group(function () {
                    Route::any('{agencyId}/add', '_AddAgencyPaiement'); #AJOUT D'UN ENCAISSEMENT POUR UNE AGENCE
                });

                ###___FILTRE
                Route::any('filtre_by_date', '_FiltreByDate'); #FILTRER PAR DATE
                Route::any('{houseId}/filtre_after_stateDate_stoped', 'FiltreAfterStateDateStoped'); #FILTRER LES PAIEMENTS APRES ARRET DES ETATS
                Route::any('{agency}/filtre_after_echeanceDate', 'FiltreAfterEcheanceDate');
                Route::any('{agency}/filtre_at_any_date', 'FiltreAtAnyDate');
            });
        });
        ##___

        Route::controller(AgencyController::class)->group(function () {
            Route::any('agency/{agencyId}/{supervisor}/{action}/bilan', "AgencyBilan"); # Bilan d'une agence
            Route::any('agency/{agencyId}/{supervisor}/{action}/factures', "AgencyFactures"); # Factures d'une agence
        });

        ###========== FACTURE ========###
        Route::prefix("facture")->group(function () {
            Route::prefix("type")->group(function () {
                Route::controller(FactureTypeController::class)->group(function () {
                    Route::any('all', 'FactureTypes'); #RECUPERATION DE TOUT LES TYPES DE FACTURE
                    Route::any('{id}/retrieve', '_RetrieveFactureType'); #RECUPERATION D'UN TYPE DE FACTURE
                });
            });

            Route::prefix("status")->group(function () {
                Route::controller(FactureStatusController::class)->group(function () {
                    Route::any('all', 'FactureStatus'); #RECUPERATION DE TOUT LES STATUS DE FACTURE
                    Route::any('{id}/retrieve', '_RetrieveFactureStatus'); #RECUPERATION D'UN STATU DE FACTURE
                });
            });

            Route::controller(FactureController::class)->group(function () {
                Route::any('all', 'Factures'); #RECUPERATION DE TOUTES LES FACTURES
                Route::any('{id}/retrieve', 'RetrieveFacture'); #RECUPERATION D'UNE FACTURE
                Route::any('{id}/updateStatus', 'UpdateStatus'); #CHANGEMENT DE STATUS
                Route::any('search', 'SearchFacture');
            });

            ###___GESTION DES FACTURES D'ELECTRICITE DANS UNE LOCATION
            Route::prefix("electricity_facture")->group(function () {
                Route::controller(LocationElectrictyFactureController::class)->group(function () {
                    Route::any("generate", "_GenerateFacture"); ##__generer la facture
                    Route::any("location/{locationId}/retrieve", "RetrieveLocationFactures"); ##__recuperer les factures d'electricité d'une location
                    Route::any("{id}/retrieve", "RetrieveFacture"); ##__recuperer une facture d'electricité
                    Route::any("{id}/delete", "_DeleteFacture"); ##__suppression d'une facture d'electricité
                    Route::any("{id}/payement", "_FacturePayement"); ##__suppression d'une facture d'electricité

                    ##=========__ ARRETER LES ETATS D'ELECTRICITE DES MAISON ======
                    Route::prefix("house_state")->group(function () {
                        Route::controller(StopHouseElectricityStateController::class)->group(function () {
                            Route::any('stop', '_StopStatsOfHouse');
                            Route::any('house/{houseId}/all', 'RetrieveHouseStates');
                            Route::any('{id}/retrieve', 'RetrieveState');
                            Route::any('all', 'GetAllStates');
                            Route::any('house/{houseId}/electricity_imprime', 'ImprimeElectricityHouseState');
                        });
                    });
                });
            });

            ###___GESTION DES FACTURES D'EAU DANS UNE LOCATION
            Route::prefix("water_facture")->group(function () {
                Route::controller(LocationWaterFactureController::class)->group(function () {
                    Route::any("generate", "_GenerateFacture"); ##__generer la facture
                    Route::any("location/{locationId}/retrieve", "RetrieveLocationFactures"); ##__recuperer les factures d'eau d'une location
                    Route::any("{id}/retrieve", "RetrieveFacture"); ##__recuperer une facture d'eau
                    Route::any("{id}/delete", "_DeleteFacture"); ##__suppression d'une facture d'eau
                    Route::any("{id}/payement", "_FacturePayement"); ##__suppression d'une facture d'eau

                    ##=========__ ARRETER LES ETATS D'EAU DES MAISON ======
                    Route::prefix("house_state")->group(function () {
                        Route::controller(StopHouseWaterStateController::class)->group(function () {
                            Route::any('stop', '_StopStatsOfHouse');
                            Route::any('house/{houseId}/all', 'RetrieveHouseStates');
                            Route::any('{id}/retrieve', 'RetrieveState');
                            Route::any('all', 'GetAllStates');
                            Route::any('house/{houseId}/water_imprime', 'ImprimeWaterHouseState');
                        });
                    });
                });
            });
        });
        ##___
    });
    ######################## FIN MODULE IMMO ##############################
});
