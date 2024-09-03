<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        #======== CREATION DES ACTIONS PAR DEFAUT=========#
        $actions = [
            [
                'name' => "list_houses",
                'description' => "Lister les maisons",
                'visible' => true
            ],
            [
                'name' => "add_house",
                'description' => "Ajouter une maison",
                'visible' => true
            ]
        ];

        foreach ($actions as $action) {
            \App\Models\Action::factory()->create($action);
        }

        #======== CREATION DES PROFILS PAR DEFAUT=========#
        $profils = [
            [
                "name" => "Système",
                "description" => "Gestionnaire du Système",
            ],
            [
                "name" => "Responsable",
                "description" => "Le Responsable du compte",
            ],
            [
                "name" => "Technicien",
                "description" => "Un Technicien de votre structure ou de FRIKLABEL",
            ],
            [
                "name" => "Employe",
                "description" => "Un Employe de votre structure",
            ],
            [
                "name" => "Agency",
                "description" => "Un Distributeur de votre structure",
            ],
            [
                "name" => "Master",
                "description" => "Master distributeur",
            ],
            [
                "name" => "Agent",
                "description" => "Agent commercial",
            ],
            [
                "name" => "Client",
                "description" => "Client",
            ],
            [
                "name" => "Admin",
                "description" => "L'administrateur",
            ],
        ];

        foreach ($profils as $profil) {
            \App\Models\Profil::factory()->create($profil);
        }
        #======== CREATION DES RANGS PAR DEFAUT=========#

        $rangs = [
            [
                "name" => "admin",
                "description" => "L'administrateur général du networking",
            ],
            [
                "name" => "moderator",
                "description" => "Le modérateur du compte",
            ],
            [
                "name" => "user",
                "description" => "Un simple utilisateur du compte",
            ],
        ];
        foreach ($rangs as $rang) {
            \App\Models\Rang::factory()->create($rang);
        }

        #======== CREATION DES RIGHTS  PAR DEFAUT =========#
        $rights = [];

        foreach ($rights as $right) {
            \App\Models\Right::factory()->create($right);
        }

        ###======== CREATION DES USERS PAR DEFAUT==========###
        $users = [
            [
                'name' => 'admin',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => '$2y$10$CI5P59ICr/HOihqlnYUrLeKwCajgMKd34HB66.JsJBrIOQY9fazrG', #admin
                "rang_id" => \App\Models\Rang::find(1),
                "profil_id" => \App\Models\Profil::find(9),
                'is_super_admin' => true,
                'phone' => "22961765591",

                'is_admin' => true,
                'compte_actif' => true,
            ],
            [
                'name' => 'Master Edou',
                'username' => 'edou',
                'email' => 'edou@gmail.com',
                'password' => '$2y$10$CI5P59ICr/HOihqlnYUrLeKwCajgMKd34HB66.JsJBrIOQY9fazrG', #admin
                "rang_id" => \App\Models\Rang::find(1),
                "profil_id" => \App\Models\Profil::find(9),
                // 'is_admin' => true,
                'phone' => "22996095312",

                'is_admin' => true,
                'compte_actif' => true,
                'owner' => 1,
            ]
        ];

        foreach ($users as $user) {
            \App\Models\User::factory()->create($user);
        }

        ##======== CREATION DES ROLES PAR DEFAUT ============####
        $roles = [
            [
                'label' => 'is_supervisor',
                'description' => 'Le rôle d\'un superviseur'
            ],
            [
                'label' => 'is_accountant_agent',
                'description' => 'Le rôle d\'un Gestionnaire de compte'
            ],
            [
                'label' => 'is_accountant_chief',
                'description' => 'Le rôle d\'un chef comptable'
            ],
            [
                'label' => 'is_master',
                'description' => 'Le rôle d\'un master'
            ]
        ];
        foreach ($roles as $role) {
            \App\Models\Role::factory()->create($role);
        };

        ###===== USER ROLE MASTER PAR DEFAUT ======####
        foreach (User::all() as $user) {
            // $roleMaster = Role::find(6);
            $user_role = new UserRole();
            $user_role->user_id = $user->id;
            $user_role->role_id = 4;
            $user_role->save();
        }


        ##======== CREATION DES COUNTRYS ============####
        $countries = [
            [
                "name" => "Afrique du Sud",
                "prefix" => "Président",
            ],
            [
                "name" => "Algérie",
            ],
            [
                "name" => "Angola",
            ],
            [
                "name" => "Bénin",
                "prefix" => "229",
                "code" => "BJ",
            ],
            [
                "name" => "Botswana",
            ],
            [
                "name" => "Burkina Faso",
            ],
            [
                "name" => "Burundi",
            ],
            [
                "name" => "Cameroun",
            ],
            [
                "name" => "Cap-Vert",
            ],
            [
                "name" => "Centrafrique",
            ],
            [
                "name" => "Comores",
            ],
            [
                "name" => "Congo",
            ],
            [
                "name" => "Côte d'Ivoire",
            ],
            [
                "name" => "Djibouti",
            ],
            [
                "name" => "Egypte",
            ],
            [
                "name" => "Erythrée",
            ],
            [
                "name" => "Ethiopie",
            ],
            [
                "name" => "Gabon",
            ],
            [
                "name" => "Gambie",
            ],
            [
                "name" => "Ghana",
            ],
            [
                "name" => "Guinée",
            ],
            [
                "name" => "Guinée-Bissau",
            ],
            [
                "name" => "Guinée Equatoriale",
            ],
            [
                "name" => "Kenya",
            ],
            [
                "name" => "Lesotho",
            ],

            [
                "name" => "Libéria",
            ],
            [
                "name" => "Libye",
            ],
            [
                "name" => "Madagascar",
            ],
            [
                "name" => "Malawi",
            ],
            [
                "name" => "Mali",
            ],
            [
                "name" => "Maroc",
            ],
            [
                "name" => "Maurice",
            ],


            [
                "name" => "Mauritanie",
            ],
            [
                "name" => "Mozambique",
            ],
            [
                "name" => "Namibie",
            ],
            [
                "name" => "Niger",
            ],
            [
                "name" => "Nigéria",
            ],
            [
                "name" => "Ouganda",
            ],
            [
                "name" => "RD du Congo",
            ],
            [
                "name" => "Rwanda",
            ],
            [
                "name" => "Sao Tomé et Principe",
            ],
            [
                "name" => "Sénégal",
            ],
            [
                "name" => "Seychelles",
            ],
            [
                "name" => "Sierra Leone",
            ],
            [
                "name" => "Somalie",
            ],
            [
                "name" => "Soudan",
            ],
            [
                "name" => "Soudan du Sud",
            ],
            [
                "name" => "Swaziland",
            ],
            [
                "name" => "Tanzanie",
            ],
            [
                "name" => "Tchad",
            ],
            [
                "name" => "Togo",
            ],
            [
                "name" => "Tunisie",
            ],
            [
                "name" => "Zambie",
            ],
            [
                "name" => "Zimbabwé",
            ],
        ];
        foreach ($countries as $countrie) {
            \App\Models\Country::factory()->create($countrie);
        };

        ##======== CREATION DES DOMAINES D'ACTIVITES ============####
        // $activities_domains = [
        //     [
        //         "label" => "Agroalimentaire",
        //     ],
        //     [
        //         "label" => "Banque / Assurance",
        //     ],
        //     [
        //         "label" => "Bois / Papier / Carton / Imprimerie",
        //     ],
        //     [
        //         "label" => "BTP / Matériaux de construction",
        //     ],
        //     [
        //         "label" => "Chimie / Parachimie  Commerce / Négoce / Distribution",
        //     ],
        //     [
        //         "label" => "Édition / Communication / Multimédia",
        //     ],
        //     [
        //         "label" => "Électronique / Électricité",
        //     ],

        //     [
        //         "label" => "Études et conseils",
        //     ],
        //     [
        //         "label" => "Industrie pharmaceutique",
        //     ],
        //     [
        //         "label" => "Informatique / Télécoms",
        //     ],
        //     [
        //         "label" => "Machines et équipements / Automobile",
        //     ],
        //     [
        //         "label" => "Métallurgie / Travail du métal",
        //     ],
        //     [
        //         "label" => "Plastique / Caoutchouc",
        //     ],
        //     [
        //         "label" => "Services aux entreprises",
        //     ],
        //     [
        //         "label" => "Textile / Habillement / Chaussure",
        //     ],
        //     [
        //         "label" => "Transports / Logistique",
        //     ],
        //     [
        //         "label" => "Libéral",
        //     ],
        //     [
        //         "label" => "Fonctionnaire",
        //     ],
        //     [
        //         "label" => "Communauté",
        //     ],
        //     [
        //         "label" => "Autre",
        //     ],
        // ];
        // foreach ($activities_domains as $activities_domain) {
        //     \App\Models\ActivityDomain::factory()->create($activities_domain);
        // };

        ##======== CREATION DES CITIES ============####
        $cities = [

            [
                "name" => "Kandi",
                "country" => 4,
            ],
            [
                "name" => "Malanville",
                "country" => 4,
            ],
            [
                "name" => "Karimama",
                "country" => 4,
            ],
            [
                "name" => "Banikoara",
                "country" => 4,
            ],
            [
                "name" => "Gogounou",
                "country" => 4,
            ],
            [
                "name" => "Ségbana",
                "country" => 4,
            ],

            [
                "name" => "Boukoumbé",
                "country" => 4,
            ],
            [
                "name" => "Cobly",
                "country" => 4,
            ],
            [
                "name" => "Matéri",
                "country" => 4,
            ],
            [
                "name" => "Tanguiéta",
                "country" => 4,
            ],
            [
                "name" => "Kérou",
                "country" => 4,
            ],
            [
                "name" => "Kouandé",
                "country" => 4,
            ],
            [
                "name" => "Natitingou",
                "country" => 4,
            ],

            [
                "name" => "Ouassa-Pehunco",
                "country" => 4,
            ],
            [
                "name" => "Toukountouna",
                "country" => 4,
            ],
            [
                "name" => "Allada",
                "country" => 4,
            ],
            [
                "name" => "Kpomassè",
                "country" => 4,
            ],



            [
                "name" => "Ouidah",
                "country" => 4,
            ],
            [
                "name" => "Toffo",
                "country" => 4,
            ],
            [
                "name" => "Tori-Bossito",
                "country" => 4,
            ],
            [
                "name" => "Abomey-Calavi",
                "country" => 4,
            ],
            [
                "name" => "Sô-Ava",
                "country" => 4,
            ],
            [
                "name" => "Bèmbèrèkè",
                "country" => 4,
            ],
            [
                "name" => "Nikki",
                "country" => 4,
            ],
            [
                "name" => "Sinendé",
                "country" => 4,
            ],




            [
                "name" => "Kalalé",
                "country" => 4,
            ],
            [
                "name" => "Parakou",
                "country" => 4,
            ],
            [
                "name" => "N'Dali",
                "country" => 4,
            ],
            [
                "name" => "Pèrèrè",
                "country" => 4,
            ],
            [
                "name" => "Tchaourou",
                "country" => 4,
            ],
            [
                "name" => "Bantè",
                "country" => 4,
            ],
            [
                "name" => "Dassa-Zoumè",
                "country" => 4,
            ],
            [
                "name" => "Savalou",
                "country" => 4,
            ],
            [
                "name" => "Glazoué",
                "country" => 4,
            ],
            [
                "name" => "Ouèssè",
                "country" => 4,
            ],
            [
                "name" => "Savè",
                "country" => 4,
            ],
            [
                "name" => "Aplahoué",
                "country" => 4,
            ],
            [
                "name" => "Djakotomey",
                "country" => 4,
            ],
            [
                "name" => "Klouékanmè",
                "country" => 4,
            ],


            [
                "name" => "Dogbo",
                "country" => 4,
            ],
            [
                "name" => "Lalo",
                "country" => 4,
            ],
            [
                "name" => "Toviklin",
                "country" => 4,
            ],
            [
                "name" => "Djougou",
                "country" => 4,
            ],
            [
                "name" => "Bassila",
                "country" => 4,
            ],

            [
                "name" => "Copargo",
                "country" => 4,
            ],
            [
                "name" => "Ouaké",
                "country" => 4,
            ],
            [
                "name" => "Cotonou",
                "country" => 4,
            ],
            [
                "name" => "Athiémè",
                "country" => 4,
            ],
            [
                "name" => "Comè",
                "country" => 4,
            ],
            [
                "name" => "Grand-Popo",
                "country" => 4,
            ],

            [
                "name" => "Bopa",
                "country" => 4,
            ],
            [
                "name" => "Houeyogbé",
                "country" => 4,
            ],
            [
                "name" => "Lokossa",
                "country" => 4,
            ],
            [
                "name" => "Porto-Novo",
                "country" => 4,
            ],
            [
                "name" => "Adjarra",
                "country" => 4,
            ],
            [
                "name" => "Aguégués",
                "country" => 4,
            ],


            [
                "name" => "Sèmè-Podji",
                "country" => 4,
            ],
            [
                "name" => "Adjohoun",
                "country" => 4,
            ],
            [
                "name" => "Akpro-Misserété",
                "country" => 4,
            ],
            [
                "name" => "Avrankou",
                "country" => 4,
            ],
            [
                "name" => "Aguégués",
                "country" => 4,
            ],
            [
                "name" => "Bonou",
                "country" => 4,
            ],
            [
                "name" => "Dangbo",
                "country" => 4,
            ],
            [
                "name" => "Adja-Ouèrè",
                "country" => 4,
            ],
            [
                "name" => "Ifangni",
                "country" => 4,
            ],
            [
                "name" => "Sakété",
                "country" => 4,
            ],
            [
                "name" => "Kétou",
                "country" => 4,
            ],
            [
                "name" => "Pobè",
                "country" => 4,
            ],
            [
                "name" => "Abomey",
                "country" => 4,
            ],
            [
                "name" => "Agbangnizoun",
                "country" => 4,
            ],
            [
                "name" => "Bohicon",
                "country" => 4,
            ],
            [
                "name" => "Covè",
                "country" => 4,
            ],
            [
                "name" => "Ouinhi",
                "country" => 4,
            ],
            [
                "name" => "Zagnanado",
                "country" => 4,
            ],
            [
                "name" => "Za-Kpota",
                "country" => 4,
            ],
            [
                "name" => "Ouagadougou",
                "country" => 6,
            ],

            [
                "name" => "Bobo-Dioulasso",
                "country" => 6,
            ],
            [
                "name" => "Koudougou",
                "country" => 6,
            ],
            [
                "name" => "Banfora",
                "country" => 6,
            ],
            [
                "name" => "Ouahigouya",
                "country" => 6,
            ],
            [
                "name" => "Dédougou",
                "country" => 6,
            ],
            [
                "name" => "Kaya",
                "country" => 6,
            ],


            [
                "name" => "Tenkodogo",
                "country" => 6,
            ],
            [
                "name" => "Fada N'Gourma",
                "country" => 6,
            ],


            [
                "name" => "Port Louis",
                "country" => 32,
            ],
            [
                "name" => "Beau-Bassin Rose-Hill",
                "country" => 32,
            ],
            [
                "name" => "Vacoas-Phœnix",
                "country" => 32,
            ],
            [
                "name" => "Curepipe",
                "country" => 32,
            ],
            [
                "name" => "Quatre Bornes",
                "country" => 32,
            ],
            [
                "name" => "Trou-aux-Biches",
                "country" => 32,
            ],
            [
                "name" => "Centre de Flacq",
                "country" => 32,
            ],
            [
                "name" => "Bel Air",
                "country" => 32,
            ],
            [
                "name" => "Mahébourg",
                "country" => 32,
            ],
            [
                "name" => "Saint-Pierre",
                "country" => 32,
            ],
            [
                "name" => "Le Hochet",
                "country" => 32,
            ],



            [
                "name" => "Baie du Tombeau",
                "country" => 32,
            ],
            [
                "name" => "Bambous",
                "country" => 32,
            ],
            [
                "name" => "Rose-Belle",
                "country" => 32,
            ],
            [
                "name" => "Chemin Grenier",
                "country" => 32,
            ],
            [
                "name" => "Rivière du Rempart",
                "country" => 32,
            ],
            [
                "name" => "Grand Baie",
                "country" => 32,
            ],
            [
                "name" => "Plaine Magnien",
                "country" => 32,
            ],
            [
                "name" => "Pailles",
                "country" => 32,
            ],


            [
                "name" => "Surinam",
                "country" => 32,
            ],
            [
                "name" => "Lalmatie",
                "country" => 32,
            ],
            [
                "name" => "New Grove",
                "country" => 32,
            ],
            [
                "name" => "Rivière des Anguilles",
                "country" => 32,
            ],
            [
                "name" => "Terre Rouge",
                "country" => 32,
            ],
            [
                "name" => "Petit Raffray",
                "country" => 32,
            ],
            [
                "name" => "Moka",
                "country" => 32,
            ],
            [
                "name" => "Pamplemousses",
                "country" => 32,
            ],
            [
                "name" => "Montagne Blanche",
                "country" => 32,
            ],
            [
                "name" => "L'Escalier",
                "country" => 32,
            ],
            [
                "name" => "Goodlands",
                "country" => 32,
            ],
            [
                "name" => "Rivière Noire",
                "country" => 32,
            ],
            [
                "name" => "Flic en Flac",
                "country" => 32,
            ],
            [
                "name" => "Poste de Flacq",
                "country" => 32,
            ],
            [
                "name" => "Yaoundé",
                "country" => 8,
            ],
            [
                "name" => "Douala",
                "country" => 8,
            ],
            [
                "name" => "Garoua",
                "country" => 8,
            ],
            [
                "name" => "Bamenda",
                "country" => 32,
            ],
            [
                "name" => "Maroua",
                "country" => 8,
            ],
            [
                "name" => "Maroua",
                "country" => 8,
            ],
            [
                "name" => "Nkongsamba",
                "country" => 8,
            ],


            [
                "name" => "Bafoussam",
                "country" => 8,
            ],
            [
                "name" => "Ngaoundéré",
                "country" => 8,
            ],
            [
                "name" => "Bertoua",
                "country" => 8,
            ],
            [
                "name" => "Loum",
                "country" => 8,
            ],
            [
                "name" => "Edéa",
                "country" => 8,
            ],



            [
                "name" => "Niamey",
                "country" => 36,
            ],
            [
                "name" => "Zinder",
                "country" => 36,
            ],
            [
                "name" => "Maradi",
                "country" => 36,
            ],
            [
                "name" => "Tessaoua",
                "country" => 36,
            ],
            [
                "name" => "Birni N'Konni",
                "country" => 36,
            ],
            [
                "name" => "Aguié",
                "country" => 36,
            ],
            [
                "name" => "Tanout",
                "country" => 36,
            ],
            [
                "name" => "Illéla",
                "country" => 36,
            ],
            [
                "name" => "Agadez",
                "country" => 36,
            ],
            [
                "name" => "Tahoua",
                "country" => 36,
            ],



            [
                "name" => "Accra",
                "country" => 20,
            ],
            [
                "name" => "Kumasi",
                "country" => 20,
            ],
            [
                "name" => "Tamale",
                "country" => 20,
            ],
            [
                "name" => "Sekondi-Takoradi",
                "country" => 20,
            ],
            [
                "name" => "Ashaiman",
                "country" => 20,
            ],
            [
                "name" => "Sunyani",
                "country" => 20,
            ],
            [
                "name" => "Cape Coast",
                "country" => 20,
            ],
            [
                "name" => "Obuasi",
                "country" => 20,
            ],
            [
                "name" => "Teshie",
                "country" => 20,
            ],
            [
                "name" => "Tema",
                "country" => 20,
            ],
            [
                "name" => "Madina",
                "country" => 20,
            ],
            [
                "name" => "Koforidua",
                "country" => 20,
            ],
            [
                "name" => "Wa",
                "country" => 20,
            ],
            [
                "name" => "Techiman",
                "country" => 20,
            ],
            [
                "name" => "Ho",
                "country" => 20,
            ],



            [
                "name" => "Nungua",
                "country" => 20,
            ],
            [
                "name" => "Lashibi",
                "country" => 20,
            ],
            [
                "name" => "Dome",
                "country" => 20,
            ],
            [
                "name" => "Tema New Town",
                "country" => 20,
            ],
            [
                "name" => "Gbawe",
                "country" => 20,
            ],
            [
                "name" => "Banjul",
                "country" => 19,
            ],
            [
                "name" => "Brikama",
                "country" => 19,
            ],
            [
                "name" => "Bakau",
                "country" => 19,
            ],
            [
                "name" => "Serrekunda ",
                "country" => 19,
            ],
            [
                "name" => "Farafenni",
                "country" => 19,
            ],
            [
                "name" => "Kinshasa",
                "country" => 39,
            ],
            [
                "name" => "Lubumbashi",
                "country" => 39,
            ],




            [
                "name" => "Mbuji-Mayi",
                "country" => 39,
            ],
            [
                "name" => "Kananga",
                "country" => 39,
            ],
            [
                "name" => "Kisangani",
                "country" => 39,
            ],
            [
                "name" => "Bukavu",
                "country" => 39,
            ],
            [
                "name" => "Tshikapa",
                "country" => 39,
            ],
            [
                "name" => "Kolwezi",
                "country" => 39,
            ],
            [
                "name" => "Likasi",
                "country" => 39,
            ],
            [
                "name" => "Goma",
                "country" => 39,
            ],
            [
                "name" => "Kikwit",
                "country" => 39,
            ],
            [
                "name" => "Uvira",
                "country" => 39,
            ],
            [
                "name" => "Bunia",
                "country" => 39,
            ],
            [
                "name" => "Kalemie",
                "country" => 39,
            ],
            [
                "name" => "Mbandaka",
                "country" => 39,
            ],
            [
                "name" => "Matadi",
                "country" => 39,
            ],


            [
                "name" => "Kabinda",
                "country" => 39,
            ],
            [
                "name" => "Butembo",
                "country" => 39,
            ],
            [
                "name" => "Baraka",
                "country" => 39,
            ],
            [
                "name" => "Mwene-Ditu",
                "country" => 39,
            ],
            [
                "name" => "Isiro",
                "country" => 39,
            ],
            [
                "name" => "Kindu",
                "country" => 39,
            ],
            [
                "name" => "Boma",
                "country" => 39,
            ],
            [
                "name" => "Kamina",
                "country" => 39,
            ],
            [
                "name" => "Gandajika",
                "country" => 39,
            ],
            [
                "name" => "Bandundu",
                "country" => 39,
            ],
            [
                "name" => "Gemena",
                "country" => 39,
            ],
            [
                "name" => "Kipushi",
                "country" => 39,
            ],
            [
                "name" => "Bumba",
                "country" => 39,
            ],
            [
                "name" => "Mbanza-Ngungu",
                "country" => 39,
            ],
            [
                "name" => "Bikoro",
                "country" => 39,
            ],
            [
                "name" => "Boende",
                "country" => 39,
            ],
            [
                "name" => "Gbadolite",
                "country" => 39,
            ],
            [
                "name" => "Beni",
                "country" => 39,
            ],
            [
                "name" => "Zongo",
                "country" => 39,
            ],



            [
                "name" => "N'Djaména",
                "country" => 50,
            ],
            [
                "name" => "Moundou",
                "country" => 50,
            ],
            [
                "name" => "Sarh",
                "country" => 50,
            ],




            [
                "name" => "Bamako",
                "country" => 30,
            ],
            [
                "name" => "Sikasso",
                "country" => 30,
            ],
            [
                "name" => "Mopti",
                "country" => 30,
            ],
            [
                "name" => "Koutiala",
                "country" => 30,
            ],
            [
                "name" => "Kayes",
                "country" => 30,
            ],
            [
                "name" => "Ségou",
                "country" => 30,
            ],



            [
                "name" => "Abidjan",
                "country" => 13,
            ],
            [
                "name" => "Bouaké",
                "country" => 13,
            ],
            [
                "name" => "Daloa",
                "country" => 13,
            ],
            [
                "name" => "Yamoussoukro",
                "country" => 13,
            ],
            [
                "name" => "San-Pédro",
                "country" => 13,
            ],
            [
                "name" => "Divo",
                "country" => 13,
            ],
            [
                "name" => "Korhogo",
                "country" => 13,
            ],
            [
                "name" => "Anyama",
                "country" => 13,
            ],
            [
                "name" => "Abengourou",
                "country" => 13,
            ],
            [
                "name" => "Man",
                "country" => 13,
            ],
            [
                "name" => "Gagnoa",
                "country" => 13,
            ],
            [
                "name" => "Agboville",
                "country" => 13,
            ],
            [
                "name" => "Dabou",
                "country" => 13,
            ],
            [
                "name" => "Grand-Bassam",
                "country" => 13,
            ],
            [
                "name" => "Lagos",
                "country" => 37,
            ],
            [
                "name" => "Kano",
                "country" => 37,
            ],
            [
                "name" => "Ibadan",
                "country" => 37,
            ],
            [
                "name" => "Kaduna",
                "country" => 37,
            ],
            [
                "name" => "Port Harcourt",
                "country" => 37,
            ],
            [
                "name" => "Maiduguri",
                "country" => 37,
            ],
            [
                "name" => "Benin City",
                "country" => 37,
            ],
            [
                "name" => "Zaria",
                "country" => 37,
            ],
            [
                "name" => "Aba",
                "country" => 13,
            ],
            [
                "name" => "Jos",
                "country" => 37,
            ],
            [
                "name" => "Ilorin",
                "country" => 37,
            ],
            [
                "name" => "Enugu",
                "country" => 37,
            ],
            [
                "name" => "Onitsha",
                "country" => 37,
            ],
            [
                "name" => "Warri",
                "country" => 37,
            ],
            [
                "name" => "Oshogbo",
                "country" => 37,
            ],
            [
                "name" => "Akure",
                "country" => 37,
            ],
            [
                "name" => "Ikorodu",
                "country" => 37,
            ],
            [
                "name" => "Umuahia",
                "country" => 37,
            ],
            [
                "name" => "Owerri",
                "country" => 37,
            ],
            [
                "name" => "Nnewi",
                "country" => 37,
            ],



            [
                "name" => "Dakar",
                "country" => 42,
            ],
            [
                "name" => "Pikine",
                "country" => 42,
            ],
            [
                "name" => "Touba",
                "country" => 42,
            ],
            [
                "name" => "Guédiawaye",
                "country" => 42,
            ],
            [
                "name" => "Thiès",
                "country" => 42,
            ],
            [
                "name" => "Kaolack",
                "country" => 42,
            ],
            [
                "name" => "Mbour",
                "country" => 42,
            ],
            [
                "name" => "Saint-Louis",
                "country" => 42,
            ],
            [
                "name" => "Rufisque",
                "country" => 42,
            ],
            [
                "name" => "Ziguinchor",
                "country" => 42,
            ],



            [
                "name" => "Antananarive",
                "country" => 28,
            ],
            [
                "name" => "Tamatave",
                "country" => 28,
            ],
            [
                "name" => "Antsirabé",
                "country" => 28,
            ],
            [
                "name" => "Fianarantsoa",
                "country" => 28,
            ],
            [
                "name" => "Majunga",
                "country" => 28,
            ],
            [
                "name" => "Tuléar",
                "country" => 28,
            ],
            [
                "name" => "Diego-Suarez",
                "country" => 28,
            ],
            [
                "name" => "Antanifotsy",
                "country" => 28,
            ],
            [
                "name" => "Ambovombe",
                "country" => 28,
            ],
            [
                "name" => "Amparafaravola",
                "country" => 28,
            ],
            [
                "name" => "Brazzaville",
                "country" => 12,
            ],
            [
                "name" => "Pointe-Noire",
                "country" => 12,
            ],
            [
                "name" => "Dolisie",
                "country" => 12,
            ],
            [
                "name" => "Nkayi",
                "country" => 12,
            ],
            [
                "name" => "Ouesso",
                "country" => 12,
            ],
            [
                "name" => "Madingou",
                "country" => 12,
            ],
            [
                "name" => "Impfondo",
                "country" => 12,
            ],
            [
                "name" => "Kigali",
                "country" => 40,
            ],
            [
                "name" => "Butare",
                "country" => 40,
            ],
            [
                "name" => "Gitarama",
                "country" => 40,
            ],
            [
                "name" => "Ruhengeri",
                "country" => 40,
            ],
            [
                "name" => "Gisenyi",
                "country" => 40,
            ],
            [
                "name" => "Byumba",
                "country" => 40,
            ],
            [
                "name" => "Cyangugu",
                "country" => 40,
            ],
            [
                "name" => "Nyanza",
                "country" => 40,
            ],
            [
                "name" => "Kabuga",
                "country" => 40,
            ],
            [
                "name" => "Ruhango",
                "country" => 40,
            ],


            [
                "name" => "Addis-Abeba",
                "country" => 17,
            ],
            [
                "name" => "Dire Dawa",
                "country" => 17,
            ],
            [
                "name" => "Adama",
                "country" => 17,
            ],
            [
                "name" => "Gondar",
                "country" => 17,
            ],
            [
                "name" => "Mekele",
                "country" => 17,
            ],
            [
                "name" => "Dessie",
                "country" => 17,
            ],
            [
                "name" => "Baher Dar",
                "country" => 17,
            ],
            [
                "name" => "Jimma",
                "country" => 17,
            ],
            [
                "name" => "Debre Zeit",
                "country" => 17,
            ],
            [
                "name" => "Awasa",
                "country" => 17,
            ],



            [
                "name" => "Libreville",
                "country" => 18,
            ],
            [
                "name" => "Port-Gentil",
                "country" => 18,
            ],
            [
                "name" => "Franceville",
                "country" => 18,
            ],
            [
                "name" => "Oyem",
                "country" => 18,
            ],
            [
                "name" => "Moanda",
                "country" => 18,
            ],
            [
                "name" => "Mouila",
                "country" => 18,
            ],
            [
                "name" => "Lambaréné",
                "country" => 18,
            ],
            [
                "name" => "Tchibanga",
                "country" => 18,
            ],






            [
                "name" => "Nairobi",
                "country" => 24,
            ],
            [
                "name" => "Mombasa",
                "country" => 24,
            ],
            [
                "name" => "Kisumu",
                "country" => 24,
            ],
            [
                "name" => "Nakuru",
                "country" => 24,
            ],
            [
                "name" => "Eldoret",
                "country" => 24,
            ],
            [
                "name" => "Ruiru",
                "country" => 24,
            ],
            [
                "name" => "Machakos",
                "country" => 24,
            ],
            [
                "name" => "Meru",
                "country" => 24,
            ],
            [
                "name" => "Nyeri",
                "country" => 24,
            ],
            [
                "name" => "Kitale",
                "country" => 24,
            ],





            [
                "name" => "Casablanca",
                "country" => 31,
            ],
            [
                "name" => "Fès",
                "country" => 31,
            ],
            [
                "name" => "Salé",
                "country" => 31,
            ],
            [
                "name" => "Tanger",
                "country" => 31,
            ],
            [
                "name" => "Marrakech",
                "country" => 31,
            ],
            [
                "name" => "Meknès",
                "country" => 31,
            ],
            [
                "name" => "Rabat",
                "country" => 31,
            ],
            [
                "name" => "Oujda",
                "country" => 31,
            ],
            [
                "name" => "Kénitra",
                "country" => 31,
            ],
            [
                "name" => "Agadir",
                "country" => 31,
            ],
            [
                "name" => "Tétouan",
                "country" => 31,
            ],
            [
                "name" => "Témara",
                "country" => 31,
            ],
            [
                "name" => "Safi",
                "country" => 31,
            ],
            [
                "name" => "Mohammédia",
                "country" => 31,
            ],
            [
                "name" => "Khouribga",
                "country" => 31,
            ],
            [
                "name" => "El Jadida",
                "country" => 31,
            ],
            [
                "name" => "Béni Mellal",
                "country" => 31,
            ],
            [
                "name" => "Taza",
                "country" => 31,
            ],
            [
                "name" => "Khémisset",
                "country" => 31,
            ],
            [
                "name" => "Taourirt",
                "country" => 31,
            ],




            [
                "name" => "Bangui",
                "country" => 10,
            ],
            [
                "name" => "Bimbo",
                "country" => 10,
            ],
            [
                "name" => "Berbérati",
                "country" => 10,
            ],
            [
                "name" => "Carnot",
                "country" => 10,
            ],
            [
                "name" => "Bambari",
                "country" => 10,
            ],
            [
                "name" => "Bouar",
                "country" => 10,
            ],





            [
                "name" => "Moroni",
                "country" => 11,
            ],
            [
                "name" => "Mutsamudu",
                "country" => 11,
            ],
            [
                "name" => "Domoni",
                "country" => 11,
            ],
            [
                "name" => "Fomboni",
                "country" => 11,
            ],
            [
                "name" => "Tsémbehou",
                "country" => 11,
            ],
            [
                "name" => "Bujumbura",
                "country" => 7,
            ],
            [
                "name" => "Muyinga",
                "country" => 7,
            ],
            [
                "name" => "Ruyigi",
                "country" => 7,
            ],
            [
                "name" => "Gitega",
                "country" => 7,
            ],
            [
                "name" => "Ngozi",
                "country" => 7,
            ],
            [
                "name" => "Makamba",
                "country" => 7,
            ],
            [
                "name" => "Cibitoke",
                "country" => 7,
            ],
            [
                "name" => "Rumonge",
                "country" => 7,
            ],
            [
                "name" => "Gaborone",
                "country" => 5,
            ],
            [
                "name" => "Francistown",
                "country" => 5,
            ],
            [
                "name" => "Molepolole",
                "country" => 5,
            ],
            [
                "name" => "Selebi-Phikwe",
                "country" => 5,
            ],
            [
                "name" => "Maun",
                "country" => 5,
            ],
            [
                "name" => "Djibouti",
                "country" => 14,
            ],
            [
                "name" => "Ali Sabieh",
                "country" => 14,
            ],
            [
                "name" => "Tadjourah",
                "country" => 14,
            ],
            [
                "name" => "Obock",
                "country" => 14,
            ],
            [
                "name" => "Dikhil",
                "country" => 14,
            ],
            [
                "name" => "Monrovia",
                "country" => 26,
            ],
            [
                "name" => "Ganta",
                "country" => 26,
            ],
            [
                "name" => "Buchanan",
                "country" => 26,
            ],
            [
                "name" => "Gbarnga",
                "country" => 26,
            ],
            [
                "name" => "Kakata",
                "country" => 26,
            ],
            [
                "name" => "Victoria",
                "country" => 43,
            ],
            [
                "name" => "De Quincey",
                "country" => 43,
            ],
            [
                "name" => "Anse Boileau",
                "country" => 43,
            ],
            [
                "name" => "Beau Vallon",
                "country" => 43,
            ],
            [
                "name" => "Anse Royale",
                "country" => 43,
            ],
            [
                "name" => "Belombre",
                "country" => 43,
            ],
            [
                "name" => "Cascade",
                "country" => 43,
            ],
            [
                "name" => "Machabee",
                "country" => 43,
            ],
            [
                "name" => "Grand Anse",
                "country" => 43,
            ],
            [
                "name" => "Misere",
                "country" => 43,
            ],
            [
                "name" => "Takamaka",
                "country" => 43,
            ],
            [
                "name" => "Port Glaud",
                "country" => 43,
            ],
            [
                "name" => "La Réunion",
                "country" => 43,
            ],
            [
                "name" => "Cape Town",
                "country" => 1,
            ],
            [
                "name" => "Durban",
                "country" => 1,
            ],
            [
                "name" => "Johannesburg",
                "country" => 1,
            ],
            [
                "name" => "Soweto",
                "country" => 1,
            ],
            [
                "name" => "Pretoria",
                "country" => 1,
            ],
            [
                "name" => "Port Elizabeth",
                "country" => 1,
            ],
            [
                "name" => "Pietermaritzburg",
                "country" => 1,
            ],
            [
                "name" => "Benoni",
                "country" => 1,
            ],
            [
                "name" => "Tembisa",
                "country" => 1,
            ],
            [
                "name" => "Vereeniging",
                "country" => 1,
            ],
            [
                "name" => "Bloemfontein",
                "country" => 1,
            ],
            [
                "name" => "Boksburg",
                "country" => 1,
            ],
            [
                "name" => "Welkom",
                "country" => 1,
            ],
            [
                "name" => "Newcastle",
                "country" => 1,
            ],
            [
                "name" => "East London",
                "country" => 1,
            ],
            [
                "name" => "Maseru",
                "country" => 25,
            ],
            [
                "name" => "Hlotse",
                "country" => 25,
            ],
            [
                "name" => "Mohale's Hoek",
                "country" => 25,
            ],
            [
                "name" => "Manzini",
                "country" => 48,
            ],
            [
                "name" => "Mbabane",
                "country" => 48,
            ],
            [
                "name" => "Big Bend",
                "country" => 48,
            ],
            [
                "name" => "Malkerns",
                "country" => 48,
            ],
            [
                "name" => "Nhlangano",
                "country" => 48,
            ],


            [
                "name" => "São Tomé",
                "country" => 41,
            ],
            [
                "name" => "Santo Amaro",
                "country" => 41,
            ],
            [
                "name" => "Neves",
                "country" => 41,
            ],
            [
                "name" => "Santana",
                "country" => 41,
            ],
            [
                "name" => "Trindade",
                "country" => 41,
            ],


            [
                "name" => "Asmara",
                "country" => 16,
            ],
            [
                "name" => "Assab",
                "country" => 16,
            ],
            [
                "name" => "Keren",
                "country" => 16,
            ],
            [
                "name" => "Massaoua",
                "country" => 16,
            ],
            [
                "name" => "Mendefera",
                "country" => 16,
            ],
            [
                "name" => "Praia",
                "country" => 9,
            ],
            [
                "name" => "Mindelo",
                "country" => 9,
            ],
            [
                "name" => "Santa Maria",
                "country" => 9,
            ],
            [
                "name" => "Assomada",
                "country" => 9,
            ],
            [
                "name" => "Porto Novo",
                "country" => 9,
            ],
            [
                "name" => "Pedra Badejo",
                "country" => 9,
            ],
            [
                "name" => "São Filipe",
                "country" => 9,
            ],
            [
                "name" => "Tarrafal",
                "country" => 9,
            ],
            [
                "name" => "Alger",
                "country" => 2,
            ],
            [
                "name" => "Oran",
                "country" => 2,
            ],
            [
                "name" => "Constantine",
                "country" => 2,
            ],
            [
                "name" => "Annaba",
                "country" => 2,
            ],
            [
                "name" => "Blida",
                "country" => 2,
            ],
            [
                "name" => "Batna",
                "country" => 2,
            ],
            [
                "name" => "Djelfa",
                "country" => 2,
            ],
            [
                "name" => "Sétif",
                "country" => 2,
            ],
            [
                "name" => "Sidi bel Abbès",
                "country" => 2,
            ],
            [
                "name" => "Biskra",
                "country" => 2,
            ],
            [
                "name" => "Tébessa",
                "country" => 2,
            ],
            [
                "name" => "El Oued",
                "country" => 2,
            ],
            [
                "name" => "Skikda",
                "country" => 2,
            ],
            [
                "name" => "Tiaret",
                "country" => 2,
            ],
            [
                "name" => "Béjaïa",
                "country" => 2,
            ],
            [
                "name" => "Bissau",
                "country" => 22,
            ],
            [
                "name" => "Bafatá",
                "country" => 22,
            ],
            [
                "name" => "Bissorã",
                "country" => 22,
            ],
            [
                "name" => "Bolama",
                "country" => 22,
            ],
            [
                "name" => "Mogadiscio",
                "country" => 45,
            ],
            [
                "name" => "Hargeisa",
                "country" => 45,
            ],
            [
                "name" => "Borama",
                "country" => 45,
            ],
            [
                "name" => "Merka",
                "country" => 45,
            ],
            [
                "name" => "Kismaayo",
                "country" => 45,
            ],
            [
                "name" => "Nouakchott",
                "country" => 33,
            ],
            [
                "name" => "Nouâdhibou",
                "country" => 33,
            ],
            [
                "name" => "Adel Bagrou",
                "country" => 33,
            ],
            [
                "name" => "Boghé",
                "country" => 33,
            ],
            [
                "name" => "Kiffa",
                "country" => 33,
            ],
            [
                "name" => "Zouerate",
                "country" => 33,
            ],
            [
                "name" => "Kaédi",
                "country" => 33,
            ],
            [
                "name" => "Freetown",
                "country" => 44,
            ],
            [
                "name" => "Bo",
                "country" => 44,
            ],
            [
                "name" => "Kenema",
                "country" => 44,
            ],
            [
                "name" => "Makeni",
                "country" => 44,
            ],
            [
                "name" => "Malabo",
                "country" => 23,
            ],
            [
                "name" => "Bata",
                "country" => 23,
            ],
            [
                "name" => "Ebebiyín",
                "country" => 23,
            ],
            [
                "name" => "Tunis",
                "country" => 52,
            ],
            [
                "name" => "Sfax",
                "country" => 52,
            ],
            [
                "name" => "Sousse",
                "country" => 52,
            ],
            [
                "name" => "Kairouan",
                "country" => 52,
            ],
            [
                "name" => "Bizerte",
                "country" => 52,
            ],
            [
                "name" => "Gabès",
                "country" => 52,
            ],
            [
                "name" => "La Soukra",
                "country" => 52,
            ],
            [
                "name" => "Ariana",
                "country" => 52,
            ],
            [
                "name" => "Luanda",
                "country" => 3,
            ],
            [
                "name" => "Huambo",
                "country" => 3,
            ],
            [
                "name" => "Lobito",
                "country" => 3,
            ],
            [
                "name" => "Benguela",
                "country" => 3,
            ],
            [
                "name" => "Lucapa",
                "country" => 3,
            ],
            [
                "name" => "Kuito",
                "country" => 3,
            ],
            [
                "name" => "Lubango",
                "country" => 3,
            ],
            [
                "name" => "Malanje",
                "country" => 3,
            ],
            [
                "name" => "Mwanza",
                "country" => 49,
            ],
            [
                "name" => "Zanzibar City",
                "country" => 49,
            ],
            [
                "name" => "Arusha",
                "country" => 49,
            ],
            [
                "name" => "Mbeya",
                "country" => 49,
            ],
            [
                "name" => "Morogoro",
                "country" => 49,
            ],
            [
                "name" => "Tanga",
                "country" => 49,
            ],
            [
                "name" => "Dodoma",
                "country" => 49,
            ],
            [
                "name" => "Kigoma",
                "country" => 49,
            ],
            [
                "name" => "Moshi",
                "country" => 49,
            ],
            [
                "name" => "Le Caire",
                "country" => 15,
            ],
            [
                "name" => "Alexandrie",
                "country" => 15,
            ],
            [
                "name" => "Gizeh",
                "country" => 15,
            ],
            [
                "name" => "Shubra El-Kheima",
                "country" => 15,
            ],
            [
                "name" => "Port-Saïd",
                "country" => 15,
            ],
            [
                "name" => "Suez",
                "country" => 15,
            ],
            [
                "name" => "Louxor",
                "country" => 15,
            ],
            [
                "name" => "Mansourah",
                "country" => 15,
            ],
            [
                "name" => "El-Mahalla El-Kubra",
                "country" => 15,
            ],
            [
                "name" => "Tanta",
                "country" => 15,
            ],
            [
                "name" => "Assiout",
                "country" => 15,
            ],
            [
                "name" => "Ismaïlia",
                "country" => 15,
            ],
            [
                "name" => "Fayoum",
                "country" => 15,
            ],
            [
                "name" => "Zagazig",
                "country" => 15,
            ],
            [
                "name" => "Assouan",
                "country" => 15,
            ],
            [
                "name" => "Damiette",
                "country" => 15,
            ],
            [
                "name" => "Damanhur",
                "country" => 15,
            ],
            [
                "name" => "Al Minya",
                "country" => 15,
            ],
            [
                "name" => "Beni Suef",
                "country" => 15,
            ],
            [
                "name" => "Qena",
                "country" => 15,
            ],
            [
                "name" => "Conakry",
                "country" => 21,
            ],
            [
                "name" => "Nzérékoré",
                "country" => 21,
            ],
            [
                "name" => "Kankan",
                "country" => 21,
            ],
            [
                "name" => "Kindia",
                "country" => 21,
            ],
            [
                "name" => "Boké",
                "country" => 21,
            ],
            [
                "name" => "Kissidougou",
                "country" => 21,
            ],
            [
                "name" => "Kamsar",
                "country" => 21,
            ],
            [
                "name" => "Tripoli",
                "country" => 27,
            ],
            [
                "name" => "Benghazi",
                "country" => 27,
            ],
            [
                "name" => "Misrata",
                "country" => 27,
            ],
            [
                "name" => "El Beïda",
                "country" => 27,
            ],
            [
                "name" => "Tarhounah",
                "country" => 27,
            ],
            [
                "name" => "Khoms",
                "country" => 27,
            ],
            [
                "name" => "Zaouïa",
                "country" => 27,
            ],
            [
                "name" => "Zouara",
                "country" => 27,
            ],
            [
                "name" => "Ajdabiya",
                "country" => 27,
            ],
            [
                "name" => "Syrte",
                "country" => 27,
            ],
            [
                "name" => "Sebha",
                "country" => 27,
            ],
            [
                "name" => "Tobrouk",
                "country" => 27,
            ],
            [
                "name" => "El Azizia",
                "country" => 27,
            ],
            [
                "name" => "Sabratha",
                "country" => 27,
            ],
            [
                "name" => "Zliten",
                "country" => 27,
            ],
            [
                "name" => "Lilongwe",
                "country" => 29,
            ],
            [
                "name" => "Blantyre",
                "country" => 29,
            ],
            [
                "name" => "Mzuzu",
                "country" => 29,
            ],
            [
                "name" => "Zomba",
                "country" => 29,
            ],
            [
                "name" => "Kasungu",
                "country" => 29,
            ],
            [
                "name" => "Mangochi",
                "country" => 29,
            ],
            [
                "name" => "Maputo",
                "country" => 34,
            ],
            [
                "name" => "Matola",
                "country" => 34,
            ],
            [
                "name" => "Beira",
                "country" => 34,
            ],
            [
                "name" => "Nampula",
                "country" => 34,
            ],
            [
                "name" => "Chimoio",
                "country" => 34,
            ],
            [
                "name" => "Nacala",
                "country" => 34,
            ],
            [
                "name" => "Quelimane",
                "country" => 34,
            ],
            [
                "name" => "Mocuba",
                "country" => 34,
            ],
            [
                "name" => "Tete",
                "country" => 34,
            ],
            [
                "name" => "Xai-Xai",
                "country" => 34,
            ],






            [
                "name" => "Windhoek",
                "country" => 35,
            ],
            [
                "name" => "Walvis Bay",
                "country" => 35,
            ],
            [
                "name" => "Swakopmund",
                "country" => 35,
            ],
            [
                "name" => "Rundu",
                "country" => 35,
            ],




            [
                "name" => "Kampala",
                "country" => 38,
            ],
            [
                "name" => "Gulu",
                "country" => 38,
            ],
            [
                "name" => "Lira",
                "country" => 38,
            ],
            [
                "name" => "Jinja",
                "country" => 38,
            ],
            [
                "name" => "Mukono",
                "country" => 38,
            ],
            [
                "name" => "Mbarara",
                "country" => 38,
            ],
            [
                "name" => "Kasese",
                "country" => 38,
            ],
            [
                "name" => "Mbale",
                "country" => 38,
            ],
            [
                "name" => "Kitgum",
                "country" => 38,
            ],
            [
                "name" => "Njeru",
                "country" => 38,
            ],
            [
                "name" => "Khartoum",
                "country" => 46,
            ],
            [
                "name" => "Omdourman",
                "country" => 46,
            ],
            [
                "name" => "Bahri",
                "country" => 46,
            ],
            [
                "name" => "Nyala",
                "country" => 46,
            ],
            [
                "name" => "Port-Soudan",
                "country" => 46,
            ],
            [
                "name" => "Kassala",
                "country" => 46,
            ],
            [
                "name" => "al-Ubayyid",
                "country" => 46,
            ],
            [
                "name" => "Kosti",
                "country" => 46,
            ],
            [
                "name" => "Wad Madani",
                "country" => 46,
            ],
            [
                "name" => "al-Qadarif",
                "country" => 46,
            ],






            [
                "name" => "Djouba",
                "country" => 47,
            ],
            [
                "name" => "Rumbek",
                "country" => 47,
            ],
            [
                "name" => "Malakal",
                "country" => 47,
            ],
            [
                "name" => "Wau",
                "country" => 47,
            ],
            [
                "name" => "Yei",
                "country" => 47,
            ],
            [
                "name" => "Yambio",
                "country" => 47,
            ],
            [
                "name" => "Lusaka",
                "country" => 53,
            ],
            [
                "name" => "Ndola",
                "country" => 53,
            ],
            [
                "name" => "Kitwe",
                "country" => 53,
            ],
            [
                "name" => "Kabwe",
                "country" => 53,
            ],
            [
                "name" => "Chingola",
                "country" => 53,
            ],
            [
                "name" => "Mufulira",
                "country" => 53,
            ],
            [
                "name" => "Livingstone",
                "country" => 53,
            ],
            [
                "name" => "Luanshya",
                "country" => 53,
            ],
            [
                "name" => "Harare",
                "country" => 54,
            ],
            [
                "name" => "Bulawayo",
                "country" => 54,
            ],
            [
                "name" => "Chitungwiza",
                "country" => 54,
            ],
            [
                "name" => "Mutare",
                "country" => 54,
            ],
            [
                "name" => "Gweru",
                "country" => 54,
            ],
            [
                "name" => "Epworth",
                "country" => 54,
            ],
            [
                "name" => "Kwekwe",
                "country" => 54,
            ],
            [
                "name" => "Redcliffe",
                "country" => 54,
            ],
            [
                "name" => "Lomé",
                "country" => 51,
            ],
            [
                "name" => "Sokodé",
                "country" => 51,
            ],
            [
                "name" => "Kara",
                "country" => 51,
            ],
            [
                "name" => "Kpalimé",
                "country" => 51,
            ],
            [
                "name" => "Atakpamé",
                "country" => 51,
            ],
            [
                "name" => "Bassar",
                "country" => 51,
            ],
            [
                "name" => "Tsévié",
                "country" => 51,
            ],
            [
                "name" => "Aného",
                "country" => 51,
            ],
            [
                "name" => "Mango",
                "country" => 51,
            ],
            [
                "name" => "Dapaong",
                "country" => 51,
            ],
            [
                "name" => "Dori",
                "country" => 6,
            ],
            [
                "name" => "Macenta",
                "country" => 21,
            ],
            [
                "name" => "Mamou",
                "country" => 21,
            ],
            [
                "name" => "Guéckédou",
                "country" => 21,
            ],
            [
                "name" => "Gabu",
                "country" => 22,
            ],
            [
                "name" => "Mafefeng",
                "country" => 25,
            ],
            [
                "name" => "Teyateyaneng",
                "country" => 25,
            ],
            [
                "name" => "Otjiwarongo",
                "country" => 35,
            ],
            [
                "name" => "Oshakati",
                "country" => 35,
            ],
            [
                "name" => "Katima Mulilo",
                "country" => 35,
            ],
            [
                "name" => "Grootfontein",
                "country" => 35,
            ],
            [
                "name" => "Rehoboth",
                "country" => 35,
            ],
            [
                "name" => "Okahandja",
                "country" => 35,
            ],
            [
                "name" => "Dosso",
                "country" => 36,
            ],
            [
                "name" => "Arlit",
                "country" => 36,
            ],
            [
                "name" => "Koidu",
                "country" => 44,
            ],
            [
                "name" => "Dar es Salaam",
                "country" => 49,
            ]
        ];

        foreach ($cities as $city) {
            \App\Models\City::factory()->create($city);
        };

        ##======== CREATION DES AREAS ============####
        $areas = [
            [
                "name" => "Akoïtchaou",
            ],
            [
                "name" => "Alfakoara",
            ],
            [
                "name" => "Angaradébou",
            ],
            [
                "name" => "Dogban",
            ],
            [
                "name" => "Fafa",
            ],
            [
                "name" => "Fouet",
            ],
            [
                "name" => "Kpalolo",
            ],
            [
                "name" => "Sékalé",
            ],
            [
                "name" => "Sondo",
            ],
            [
                "name" => "Soundou",
            ],
            [
                "name" => "Tchoka",
            ],
            [
                "name" => "Thuy",
            ],
            [
                "name" => "Thya",
            ],
            [
                "name" => "Bensékou",
            ],
            [
                "name" => "Gogbêdé",
            ],
            [
                "name" => "Koutakroukou",
            ],
            [
                "name" => "Dinin",
            ],
            [
                "name" => "Dinin Peulh",
            ],
            [
                "name" => "Donwari",
            ],
            [
                "name" => "Donwari-Peulh",
            ],
            [
                "name" => "Gambanè",
            ],
            [
                "name" => "Gambanè-Peulh",
            ],
            [
                "name" => "Kpéssarou",
            ],
            [
                "name" => "Mongo",
            ],
            [
                "name" => "Mongo-Peulh",
            ],
            [
                "name" => "Sidérou",
            ],
            [
                "name" => "Tissarou",
            ],
            [
                "name" => "Tissarou-Peulh",
            ],
            [
                "name" => "Touko",
            ],
            [
                "name" => "Damadi",
            ],
            [
                "name" => "Dodopanin",
            ],
            [
                "name" => "Gando-Kossikana",
            ],
            [
                "name" => "Gansosso-Gbiga",
            ],
            [
                "name" => "Gansosso-Yiroussé",
            ],
            [
                "name" => "Kadjèrè",
            ],
            [
                "name" => "Kéféri-Hinkanté",
            ],
            [
                "name" => "Kéféri-Sinté",
            ],
            [
                "name" => "Pédé",
            ],
            [
                "name" => "Al Barika",
            ],
            [
                "name" => "Alékparé",
            ],
            [
                "name" => "Banigourou",
            ],
            [
                "name" => "Baobab",
            ],
            [
                "name" => "Kossarou",
            ],
            [
                "name" => "Madina",
            ],
            [
                "name" => "Zerman-Kouré",
            ],
            [
                "name" => "Bakpara",
            ],
            [
                "name" => "Héboumey",
            ],
            [
                "name" => "Kandi-Fô",
            ],
            [
                "name" => "Kandi-Fô-Peulh",
            ],
            [
                "name" => "Lafiarou",
            ],
            [
                "name" => "Podo",
            ],
            [
                "name" => "Sinikoussou-Béri",
            ],
            [
                "name" => "Firi",
            ],
            [
                "name" => "Gbokoukou",
            ],
            [
                "name" => "Gogoré",
            ],
            [
                "name" => "Kassakou",
            ],
            [
                "name" => "Padé",
            ],
            [
                "name" => "Padé-Peulh",
            ],
            [
                "name" => "Pégon",
            ],
            [
                "name" => "Banikani",
            ],
            [
                "name" => "Fouré",
            ],
            [
                "name" => "Lolo",
            ],
            [
                "name" => "Saah",
            ],
            [
                "name" => "Bikongou",
            ],
            [
                "name" => "Bodérou",
            ],
            [
                "name" => "Bodérou-Peulh",
            ],
            [
                "name" => "Gbindarou",
            ],
            [
                "name" => "Sakatoussa",
            ],
            [
                "name" => "Sam",
            ],
            [
                "name" => "Sam-Gokirou",
            ],
            [
                "name" => "Sam-Peulh",
            ],
            [
                "name" => "Tankongou",
            ],
            [
                "name" => "Téri",
            ],
            [
                "name" => "Wonga",
            ],
            [
                "name" => "Alibori-Yankin",
            ],
            [
                "name" => "Pédigui",
            ],
            [
                "name" => "Sinawongourou",
            ],
            [
                "name" => "Sinawongourou-Peulh",
            ],
            [
                "name" => "Sonsoro",
            ],
            [
                "name" => "Sonsoro-Peulh",
            ],
            [
                "name" => "Djindégabi-Tounga",
            ],
            [
                "name" => "Gaabo",
            ],
            [
                "name" => "Garou-Béri",
            ],
            [
                "name" => "Garou-Tédji-Gorobani",
            ],
            [
                "name" => "Garou-Tédji",
            ],
            [
                "name" => "Garou- Wénou-Kannin",
            ],
            [
                "name" => "Kambouwo-Tounga",
            ],
            [
                "name" => "Monkassa",
            ],
            [
                "name" => "Tounga-Tédji",
            ],
            [
                "name" => "Wanda",
            ],
            [
                "name" => "Bangou",
            ],
            [
                "name" => "Banitè-Koubéri",
            ],
            [
                "name" => "Banitè-Fèrè Kirè ",
            ],
            [
                "name" => "Boïffo",
            ],
            [
                "name" => "Fiafounfoun",
            ],
            [
                "name" => "Goun-Goun",
            ],
            [
                "name" => "Guéné-Guidigo",
            ],
            [
                "name" => "Guéné-Zermé",
            ],
            [
                "name" => "Isséné",
            ],
            [
                "name" => "Kantoro",
            ],
            [
                "name" => "Koara-Tédji",
            ],
            [
                "name" => "Lakali-Kaney",
            ],
            [
                "name" => "Mokollé",
            ],
            [
                "name" => "Sounbey-Gorou",
            ],
            [
                "name" => "Tondi-Banda",
            ],
            [
                "name" => "Toro-Zougou",
            ],
            [
                "name" => "Godjékoara",
            ],
            [
                "name" => "Goroussoundougou",
            ],
            [
                "name" => "Illoua",
            ],
            [
                "name" => "Kassa",
            ],
            [
                "name" => "Koualérou",
            ],
            [
                "name" => "Kouara-tédji",
            ],
            [
                "name" => "Madécali",
            ],
            [
                "name" => "Madécali-Zongo",
            ],
            [
                "name" => "Mélayakouara",
            ],
            [
                "name" => "Sendé",
            ],
            [
                "name" => "Bodjécali",
            ],
            [
                "name" => "Bodjécali-Château",
            ],
            [
                "name" => "Galiel",
            ],
            [
                "name" => "Golobanda",
            ],
            [
                "name" => "Kotchi",
            ],
            [
                "name" => "Tassi-Djindé",
            ],
            [
                "name" => "Tassi-tédji",
            ],
            [
                "name" => "Tassi-Tédji-Banizounbou",
            ],
            [
                "name" => "Tassi-Zénon",
            ],
            [
                "name" => "Wollo",
            ],
            [
                "name" => "Wollo-Château",
            ],
            [
                "name" => "Wouro-Yesso",
            ],
            [
                "name" => "Baniloua",
            ],
            [
                "name" => "Dèguè-Dègué",
            ],
            [
                "name" => "Gorou-Djindé",
            ],
            [
                "name" => "Molla",
            ],
            [
                "name" => "Sakawan-Tédji",
            ],
            [
                "name" => "Sakawan-Zénon",
            ],
            [
                "name" => "Santché",
            ],
            [
                "name" => "Toumboutou",
            ],
            [
                "name" => "Wanzam-Koara",
            ],
            [
                "name" => "Birni Lafia",
            ],
            [
                "name" => "Fadama",
            ],
            [
                "name" => "Goroukambou",
            ],
            [
                "name" => "Kangara-Peulh",
            ],
            [
                "name" => "Karigui",
            ],
            [
                "name" => "Missira",
            ],
            [
                "name" => "Saboula",
            ],
            [
                "name" => "Tondikoaria",
            ],
            [
                "name" => "Tondoobon",
            ],
            [
                "name" => "Banikani",
            ],
            [
                "name" => "Bogo-Bogo",
            ],
            [
                "name" => "Koaratédji",
            ],
            [
                "name" => "Kofounou",
            ],
            [
                "name" => "Mamassy-Gourma",
            ],
            [
                "name" => "Torioh",
            ],
            [
                "name" => "Toura",
            ],
            [
                "name" => "Bello-Tounga",
            ],
            [
                "name" => "Fakara",
            ],
            [
                "name" => "Gourou Béri",
            ],
            [
                "name" => "Karimama-Batouma-Béri",
            ],
            [
                "name" => "Karimama-Dendi-Kouré",
            ],
            [
                "name" => "Mamassy-Peulh",
            ],
            [
                "name" => "Banizoumbou",
            ],
            [
                "name" => "Dangazori",
            ],
            [
                "name" => "Garbey-Koara",
            ],
            [
                "name" => "Goungou-Béri",
            ],
            [
                "name" => "Kéné-Tounga",
            ],
            [
                "name" => "Kompa",
            ],
            [
                "name" => "Kompanti",
            ],
            [
                "name" => "Bako-Maka",
            ],
            [
                "name" => "Bongnami",
            ],
            [
                "name" => "Fandou",
            ],
            [
                "name" => "Goumbitchigoura",
            ],
            [
                "name" => "Loumbou-Loumbou",
            ],
            [
                "name" => "Machayan-Marché",
            ],
            [
                "name" => "Monsey",
            ],
            [
                "name" => "Pétchinga",
            ],
            [
                "name" => "Arbonga",
            ],
            [
                "name" => "Aviation",
            ],
            [
                "name" => "Batran",
            ],
            [
                "name" => "Demanou",
            ],
            [
                "name" => "Dérou Garou",
            ],
            [
                "name" => "Glégbabi",
            ],
            [
                "name" => "Guiguiri",
            ],
            [
                "name" => "Kingarou",
            ],
            [
                "name" => "Kokiré",
            ],
            [
                "name" => "Kommon",
            ],
            [
                "name" => "Kori Guiguiri",
            ],
            [
                "name" => "Kpagaguèdou",
            ],
            [
                "name" => "Orou Gnonrou",
            ],
            [
                "name" => "Samanga",
            ],
            [
                "name" => "Tokey-Banta",
            ],
            [
                "name" => "Wagou",
            ],
            [
                "name" => "Wétérou",
            ],
            [
                "name" => "Yadikparou",
            ],
            [
                "name" => "Bofounou",
            ],
            [
                "name" => "Founougo-Boutèra",
            ],
            [
                "name" => "Founougo-Gorobani",
            ],
            [
                "name" => "Founougo-Gah",
            ],
            [
                "name" => "Gama",
            ],
            [
                "name" => "Gaméré-Zongo",
            ],
            [
                "name" => "Gougnirou",
            ],
            [
                "name" => "Gougnirou-Gah",
            ],
            [
                "name" => "Iboto",
            ],
            [
                "name" => "Igrigou",
            ],
            [
                "name" => "Kandérou",
            ],
            [
                "name" => "Kandérou-Kotchera",
            ],
            [
                "name" => "Koney",
            ],
            [
                "name" => "Kpako-Gbabi",
            ],
            [
                "name" => "Pogoussorou",
            ],
            [
                "name" => "Sampèto",
            ],
            [
                "name" => "Sissianganrou",
            ],
            [
                "name" => "Yanguéri",
            ],
            [
                "name" => "Yinyinpogou",
            ],
            [
                "name" => "Bonhanrou",
            ],
            [
                "name" => "Gnambanou",
            ],
            [
                "name" => "Gomparou-Gokpadou",
            ],
            [
                "name" => "Gomparou-Goussinrou",
            ],
            [
                "name" => "Gomparou",
            ],
            [
                "name" => "Gourè-Edé",
            ],
            [
                "name" => "Kali",
            ],
            [
                "name" => "Kpessanrou",
            ],
            [
                "name" => "Niékoubanta",
            ],
            [
                "name" => "Pampime",
            ],
            [
                "name" => "Sionkpékoka",
            ],
            [
                "name" => "Tiganson",
            ],
            [
                "name" => "Yossinandé",
            ],
            [
                "name" => "Bonni",
            ],
            [
                "name" => "Bontè",
            ],
            [
                "name" => "Dombouré",
            ],
            [
                "name" => "Dombouré-Gah",
            ],
            [
                "name" => "Dondagou",
            ],
            [
                "name" => "Gbassa",
            ],
            [
                "name" => "Ggangbanga",
            ],
            [
                "name" => "Goumori-Gbissarou",
            ],
            [
                "name" => "Goumori-Bayèdou",
            ],
            [
                "name" => "Goumori-Gah",
            ],
            [
                "name" => "Mondoukoka",
            ],
            [
                "name" => "Mondoukoka-Gah",
            ],
            [
                "name" => "Sakassinnou",
            ],
            [
                "name" => "Satouba",
            ],
            [
                "name" => "Tihourè",
            ],
            [
                "name" => "Gamarou",
            ],
            [
                "name" => "Kokey-Sinakparou",
            ],
            [
                "name" => "Kokey-Filo",
            ],
            [
                "name" => "Nimbéré",
            ],
            [
                "name" => "Piguiré",
            ],
            [
                "name" => "Sonwari",
            ],
            [
                "name" => "Yambérou",
            ],
            [
                "name" => "Bonkéré",
            ],
            [
                "name" => "Kokiborou",
            ],
            [
                "name" => "Sounsoun",
            ],
            [
                "name" => "Guinningou-Gah",
            ],
            [
                "name" => "Sirikou",
            ],
            [
                "name" => "Boniki",
            ],
            [
                "name" => "Kihouhou",
            ],
            [
                "name" => "Kpéborogou",
            ],
            [
                "name" => "Ounet-Sinakparou",
            ],
            [
                "name" => "Ounet-Sékogbaourou",
            ],
            [
                "name" => "Ounet-Gah",
            ],
            [
                "name" => "Sonnou",
            ],
            [
                "name" => "Sonnou-Gah",
            ],
            [
                "name" => "Bonyangou",
            ],
            [
                "name" => "Bourin",
            ],
            [
                "name" => "Gnandarou",
            ],
            [
                "name" => "Kégamorou",
            ],
            [
                "name" => "Poto",
            ],
            [
                "name" => "Poto-Gah",
            ],
            [
                "name" => "Simpérou",
            ],
            [
                "name" => "Simpérou-Gah",
            ],
            [
                "name" => "Sompéroukou-Gbessara",
            ],
            [
                "name" => "Sompéroukou-Yorounon",
            ],
            [
                "name" => "Sompéroukou-Gah",
            ],
            [
                "name" => "Gbéniki",
            ],
            [
                "name" => "Mékrou",
            ],
            [
                "name" => "Soroko Yorounon",
            ],
            [
                "name" => "Soroko",
            ],
            [
                "name" => "Soroko Gah",
            ],
            [
                "name" => "Soudou",
            ],
            [
                "name" => "Atabénou",
            ],
            [
                "name" => "Gnambourankorou",
            ],
            [
                "name" => "Guimbagou",
            ],
            [
                "name" => "Kakourogou",
            ],
            [
                "name" => "Siwougourou",
            ],
            [
                "name" => "Tintinmou",
            ],
            [
                "name" => "Tintinmou-Gah",
            ],
            [
                "name" => "Toura-Bio N’Worou",
            ],
            [
                "name" => "Toura-Yokparou",
            ],
            [
                "name" => "Toura Gah",
            ],
            [
                "name" => "Badou",
            ],
            [
                "name" => "Kérou-Bagou",
            ],
            [
                "name" => "Bagou-Sinkparou",
            ],
            [
                "name" => "Bagou-Yagbo",
            ],
            [
                "name" => "Banigouré",
            ],
            [
                "name" => "Bépororo",
            ],
            [
                "name" => "Bouyagourou",
            ],
            [
                "name" => "Diadia",
            ],
            [
                "name" => "Gandobou",
            ],
            [
                "name" => "Garagoro",
            ],
            [
                "name" => "Kali",
            ],
            [
                "name" => "Kangnan",
            ],
            [
                "name" => "Kassirou",
            ],
            [
                "name" => "Kpakaguèrè",
            ],
            [
                "name" => "Nafarou",
            ],
            [
                "name" => "Orou-Bédou",
            ],
            [
                "name" => "Taïti",
            ],
            [
                "name" => "Yankpannou",
            ],
            [
                "name" => "Djinmélé",
            ],
            [
                "name" => "Gogounou-Gbanin",
            ],
            [
                "name" => "Gogounou-Nassabara",
            ],
            [
                "name" => "Goubéra",
            ],
            [
                "name" => "Konsénin",
            ],
            [
                "name" => "Ouèrè-Bani",
            ],
            [
                "name" => "Ouèrè-Sonkérou",
            ],
            [
                "name" => "Sonkorou",
            ],
            [
                "name" => "Sorou",
            ],
            [
                "name" => "Bantansoué",
            ],
            [
                "name" => "Boro",
            ],
            [
                "name" => "Borodarou",
            ],
            [
                "name" => "Dagourou",
            ],
            [
                "name" => "Diguisson",
            ],
            [
                "name" => "Gounarou",
            ],
            [
                "name" => "Lafiarou",
            ],
            [
                "name" => "Pariki",
            ],
            [
                "name" => "Dimdimnou",
            ],
            [
                "name" => "Donwari",
            ],
            [
                "name" => "Gamagou",
            ],
            [
                "name" => "Gasso",
            ],
            [
                "name" => "Gbemoussou",
            ],
            [
                "name" => "Gnindarou",
            ],
            [
                "name" => "Gouré Dantcha",
            ],
            [
                "name" => "Kantakpara-Wokparou",
            ],
            [
                "name" => "Kpigourou",
            ],
            [
                "name" => "Ouessènè-Worou",
            ],
            [
                "name" => "Petit-Paris",
            ],
            [
                "name" => "Sori-Boro Wanrou",
            ],
            [
                "name" => "Sori-Kpankpanou",
            ],
            [
                "name" => "Sori-Peulh",
            ],
            [
                "name" => "Tawali",
            ],
            [
                "name" => "Tchoupounga",
            ],
            [
                "name" => "Binga",
            ],
            [
                "name" => "Gando-Dari",
            ],
            [
                "name" => "Dougoulaye",
            ],
            [
                "name" => "Fanan",
            ],
            [
                "name" => "Gbessa",
            ],
            [
                "name" => "Sougou-Gourou",
            ],
            [
                "name" => "Sougou-Kpantrossi",
            ],
            [
                "name" => "Dassari",
            ],
            [
                "name" => "Dougou",
            ],
            [
                "name" => "Kalé",
            ],
            [
                "name" => "Soukarou",
            ],
            [
                "name" => "Wara",
            ],
            [
                "name" => "Wara-Gbidogo",
            ],
            [
                "name" => "Wara-Gah",
            ],
            [
                "name" => "Bobéna",
            ],
            [
                "name" => "Diapéou",
            ],
            [
                "name" => "Goungbè",
            ],
            [
                "name" => "Kouté",
            ],
            [
                "name" => "Libantè",
            ],
            [
                "name" => "Saonzi",
            ],
            [
                "name" => "Gbéssaka",
            ],
            [
                "name" => "Kambara",
            ],
            [
                "name" => "Lété",
            ],
            [
                "name" => "Liboussou",
            ],
            [
                "name" => "Tounga-Issa",
            ],
            [
                "name" => "Waranzi",
            ],
            [
                "name" => "Boumoussou",
            ],
            [
                "name" => "Gandoloukassa",
            ],
            [
                "name" => "Gbassè",
            ],
            [
                "name" => "Gbèkakarou",
            ],
            [
                "name" => "Guénélaga",
            ],
            [
                "name" => "Kamanan",
            ],
            [
                "name" => "Lougou",
            ],
            [
                "name" => "Niambara",
            ],
            [
                "name" => "Sinwan",
            ],
            [
                "name" => "Zonzi",
            ],
            [
                "name" => "Batazi",
            ],
            [
                "name" => "Fondo",
            ],
            [
                "name" => "Gbessarè",
            ],
            [
                "name" => "Guéné Kouzi",
            ],
            [
                "name" => "Korowi",
            ],
            [
                "name" => "Kpassana",
            ],
            [
                "name" => "Limafrani",
            ],
            [
                "name" => "Mafouta-Waassarè",
            ],
            [
                "name" => "Piami",
            ],
            [
                "name" => "Samtimbara",
            ],
            [
                "name" => "Bèdafou",
            ],
            [
                "name" => "Gbarana",
            ],
            [
                "name" => "Morou",
            ],
            [
                "name" => "Poéla",
            ],
            [
                "name" => "Sèrèbani",
            ],
            [
                "name" => "Sèrèkibè",
            ],
            [
                "name" => "Sokotindji",
            ],
            [
                "name" => "Tchakama",
            ],
            [
                "name" => "Ikounga",
            ],
            [
                "name" => "Kototougou",
            ],
            [
                "name" => "Koudahongou",
            ],
            [
                "name" => "Koukouangou-Boukoumbé",
            ],
            [
                "name" => "Koukouatchiengou",
            ],
            [
                "name" => "Koumaagou",
            ],
            [
                "name" => "Koumontchirgou",
            ],
            [
                "name" => "Koumatié",
            ],
            [
                "name" => "Kounadogou",
            ],
            [
                "name" => "Kountchougou",
            ],
            [
                "name" => "Koupagou",
            ],
            [
                "name" => "Koussayagou",
            ],
            [
                "name" => "Koussocoingou",
            ],
            [
                "name" => "Koutagou",
            ],
            [
                "name" => "Koutchata",
            ],
            [
                "name" => "Koutchatahongou",
            ],
            [
                "name" => "Tatouta",
            ],
            [
                "name" => "Zongo",
            ],
            [
                "name" => "Ditchendia",
            ],
            [
                "name" => "Koutatiégou",
            ],
            [
                "name" => "Dikoumini",
            ],
            [
                "name" => "Dimansouri",
            ],
            [
                "name" => "Dipoli",
            ],
            [
                "name" => "Dissapoli",
            ],
            [
                "name" => "Kpérinkpé",
            ],
            [
                "name" => "Mantchari",
            ],
            [
                "name" => "Natchénté",
            ],
            [
                "name" => "Otanongou",
            ],
            [
                "name" => "Oukounsérihoun",
            ],
            [
                "name" => "Agbontê",
            ],
            [
                "name" => "Kêyordakê",
            ],
            [
                "name" => "Koucongou",
            ],
            [
                "name" => "Koupagou-Korontière",
            ],
            [
                "name" => "Koutchatié",
            ],
            [
                "name" => "Kouya",
            ],
            [
                "name" => "Natiéni",
            ],
            [
                "name" => "Okouaro",
            ],
            [
                "name" => "Tadowonta",
            ],
            [
                "name" => "Tassayota",
            ],
            [
                "name" => "Didompê",
            ],
            [
                "name" => "Kougnangou",
            ],
            [
                "name" => "Koukouankpangou",
            ],
            [
                "name" => "Koussoucoingou",
            ],
            [
                "name" => "Koussounoungou",
            ],
            [
                "name" => "Koutayagou",
            ],
            [
                "name" => "Kouwetakouangou",
            ],
            [
                "name" => "Takpanta",
            ],
            [
                "name" => "Tchapéta",
            ],
            [
                "name" => "Tipaoti",
            ],
            [
                "name" => "Dikon Hein",
            ],
            [
                "name" => "Dikouténi",
            ],
            [
                "name" => "Dimatadoni",
            ],
            [
                "name" => "Dimatima",
            ],
            [
                "name" => "Dipokor",
            ],
            [
                "name" => "Dipokor-Tchaaba",
            ],
            [
                "name" => "Kouhingou",
            ],
            [
                "name" => "Koukouakoumagou",
            ],
            [
                "name" => "Koukouangou",
            ],
            [
                "name" => "Koumadogou",
            ],
            [
                "name" => "Kounatchatiégou",
            ],
            [
                "name" => "Koutangou-Manta",
            ],
            [
                "name" => "Koutchantié",
            ],
            [
                "name" => "Takotiéta",
            ],
            [
                "name" => "Tatchadiéta",
            ],
            [
                "name" => "DipokorFontri",
            ],
            [
                "name" => "Koudogou",
            ],
            [
                "name" => "Koukoua",
            ],
            [
                "name" => "Koukpintiegou",
            ],
            [
                "name" => "Koutcha-Koumagou",
            ],
            [
                "name" => "Kounagnigou",
            ],
            [
                "name" => "Kounakogou",
            ],
            [
                "name" => "Kouporgou",
            ],
            [
                "name" => "Koussakou",
            ],
            [
                "name" => "Koutangou",
            ],
            [
                "name" => "Koutoutougou",
            ],
            [
                "name" => "Kouwonatougou",
            ],
            [
                "name" => "Kouwotchirgou",
            ],
            [
                "name" => "Dikouani",
            ],
            [
                "name" => "Dimatékor",
            ],
            [
                "name" => "Dipintakouani",
            ],
            [
                "name" => "Katchagniga",
            ],
            [
                "name" => "Koubêgou",
            ],
            [
                "name" => "Koubentiégou",
            ],
            [
                "name" => "Koucogou",
            ],
            [
                "name" => "Koudadagou",
            ],
            [
                "name" => "Koukouatougou",
            ],
            [
                "name" => "Koukpêtihagou",
            ],
            [
                "name" => "Tabota",
            ],
            [
                "name" => "Takotchienta",
            ],
            [
                "name" => "Tatouta",
            ],
            [
                "name" => "Yatié",
            ],
            [
                "name" => "Bagapodi",
            ],
            [
                "name" => "Cobly",
            ],
            [
                "name" => "Kanadékè",
            ],
            [
                "name" => "Koukontouga",
            ],
            [
                "name" => "Kpétiénou",
            ],
            [
                "name" => "Nouangou",
            ],
            [
                "name" => "Oukodoo",
            ],
            [
                "name" => "Ouorou",
            ],
            [
                "name" => "Ouyérihoun",
            ],
            [
                "name" => "Tchokita",
            ],
            [
                "name" => "Touga",
            ],
            [
                "name" => "Yimpissiri",
            ],
            [
                "name" => "Gnangou",
            ],
            [
                "name" => "Kolgou",
            ],
            [
                "name" => "Pentinga",
            ],
            [
                "name" => "Siénou",
            ],
            [
                "name" => "Zanniouri",
            ],
            [
                "name" => "Datori",
            ],
            [
                "name" => "Kadiéni",
            ],
            [
                "name" => "Matalè",
            ],
            [
                "name" => "Nagnandé",
            ],
            [
                "name" => "Namatiénou",
            ],
            [
                "name" => "Tchamonga",
            ],
            [
                "name" => "Tokibi",
            ],
            [
                "name" => "Kountori",
            ],
            [
                "name" => "Kpetissohoun",
            ],
            [
                "name" => "Namoutchaga",
            ],
            [
                "name" => "Oroukouaré",
            ],
            [
                "name" => "Oukpètouhoun",
            ],
            [
                "name" => "Oukpintihoun",
            ],
            [
                "name" => "Outanonhoun",
            ],
            [
                "name" => "Serhounguè",
            ],
            [
                "name" => "Sinni",
            ],
            [
                "name" => "Tarpingou",
            ],
            [
                "name" => "Coupiani",
            ],
            [
                "name" => "Dassari",
            ],
            [
                "name" => "Firihoun",
            ],
            [
                "name" => "Koundri",
            ],
            [
                "name" => "Kourou-Koualou",
            ],
            [
                "name" => "Nagassega",
            ],
            [
                "name" => "Niéhoun-Laloga",
            ],
            [
                "name" => "Nouari",
            ],
            [
                "name" => "Ouriyori",
            ],
            [
                "name" => "Porga",
            ],
            [
                "name" => "Pouri",
            ],
            [
                "name" => "Sétchindika",
            ],
            [
                "name" => "Tankouari",
            ],
            [
                "name" => "Tankouayokouhoun",
            ],
            [
                "name" => "Tétonga",
            ],
            [
                "name" => "Tigninga",
            ],
            [
                "name" => "Tihoun",
            ],
            [
                "name" => "Tinwéga",
            ],
            [
                "name" => "Bahoun",
            ],
            [
                "name" => "Doga",
            ],
            [
                "name" => "Gouandé",
            ],
            [
                "name" => "Kandeguehoun",
            ],
            [
                "name" => "Kouantiéni",
            ],
            [
                "name" => "Kouforpissiga",
            ],
            [
                "name" => "Sindori-Toni",
            ],
            [
                "name" => "Tassahoun",
            ],
            [
                "name" => "Tcharikouanga",
            ],
            [
                "name" => "Tchassaga",
            ],
            [
                "name" => "Tiari",
            ],
            [
                "name" => "Toubougnini",
            ],
            [
                "name" => "Bourporga",
            ],
            [
                "name" => "Boutouhounpingou",
            ],
            [
                "name" => "Kankini-Séri",
            ],
            [
                "name" => "Matéri",
            ],
            [
                "name" => "Merhoun",
            ],
            [
                "name" => "Mihihoun",
            ],
            [
                "name" => "Nagassega-Kani",
            ],
            [
                "name" => "Pingou",
            ],
            [
                "name" => "Sèkanou",
            ],
            [
                "name" => "Souomou",
            ],
            [
                "name" => "Tampinti-Yerou",
            ],
            [
                "name" => "Tantouri",
            ],
            [
                "name" => "Tintonsi",
            ],
            [
                "name" => "Toussari",
            ],
            [
                "name" => "Yondisseri",
            ],
            [
                "name" => "Yopiaka",
            ],
            [
                "name" => "Borifiéri",
            ],
            [
                "name" => "Holli",
            ],
            [
                "name" => "Kotari",
            ],
            [
                "name" => "Kouarhoun",
            ],
            [
                "name" => "Kpéréhoun",
            ],
            [
                "name" => "Mahontika",
            ],
            [
                "name" => "N’ Tchiéga",
            ],
            [
                "name" => "Nodi",
            ],
            [
                "name" => "Tampouré-Pogué",
            ],
            [
                "name" => "Yédékahoun",
            ],
            [
                "name" => "Bampora",
            ],
            [
                "name" => "Bogodori",
            ],
            [
                "name" => "Dabogohoun",
            ],
            [
                "name" => "Kandjo",
            ],
            [
                "name" => "Konéandri",
            ],
            [
                "name" => "Kousséga",
            ],
            [
                "name" => "Madoga",
            ],
            [
                "name" => "Nambouli",
            ],
            [
                "name" => "Pourniari",
            ],
            [
                "name" => "Tambogou-Kondri",
            ],
            [
                "name" => "Tampanga",
            ],
            [
                "name" => "Tanhoun",
            ],
            [
                "name" => "Tantega",
            ],
            [
                "name" => "Tébiwogou",
            ],
            [
                "name" => "Féhoun",
            ],
            [
                "name" => "Fékérou",
            ],
            [
                "name" => "Koutoukondiga",
            ],
            [
                "name" => "Sakonou",
            ],
            [
                "name" => "Tchanhouncossi",
            ],
            [
                "name" => "Yanga",
            ],
            [
                "name" => "Yansaga",
            ],
            [
                "name" => "Bounta",
            ],
            [
                "name" => "Coroncoré",
            ],
            [
                "name" => "Cotiakou",
            ],
            [
                "name" => "Daguimagninni",
            ],
            [
                "name" => "Manougou",
            ],
            [
                "name" => "Nowêrèrè",
            ],
            [
                "name" => "Parabou",
            ],
            [
                "name" => "Pémombou",
            ],
            [
                "name" => "Penitingou",
            ],
            [
                "name" => "Tanféré",
            ],
            [
                "name" => "Toriconconé",
            ],
            [
                "name" => "Tora",
            ],
            [
                "name" => "Dondongou",
            ],
            [
                "name" => "Kougnieri",
            ],
            [
                "name" => "N’Dahonta",
            ],
            [
                "name" => "Natagata",
            ],
            [
                "name" => "Nignèri",
            ],
            [
                "name" => "Sammongou",
            ],
            [
                "name" => "Sonta",
            ],
            [
                "name" => "Tahinkou",
            ],
            [
                "name" => "Tapèkou",
            ],
            [
                "name" => "Tchaéta",
            ],
            [
                "name" => "Bongou",
            ],
            [
                "name" => "Douani",
            ],
            [
                "name" => "Finta",
            ],
            [
                "name" => "Hantèkou",
            ],
            [
                "name" => "Kogniga",
            ],
            [
                "name" => "Kotchekongou",
            ],
            [
                "name" => "Kouayoti",
            ],
            [
                "name" => "Koutchoutchougou",
            ],
            [
                "name" => "Matanrgui",
            ],
            [
                "name" => "Nafayoti",
            ],
            [
                "name" => "Nontingou",
            ],
            [
                "name" => "Ouankou",
            ],
            [
                "name" => "Tahongou",
            ],
            [
                "name" => "Taïacou",
            ],
            [
                "name" => "Yehongou",
            ],
            [
                "name" => "Yéyédi",
            ],
            [
                "name" => "Youakou",
            ],
            [
                "name" => "Biacou",
            ],
            [
                "name" => "Bourgniéssou",
            ],
            [
                "name" => "Djidjiré-Beri",
            ],
            [
                "name" => "Goro-bani",
            ],
            [
                "name" => "Mamoussa",
            ],
            [
                "name" => "Nanébou",
            ],
            [
                "name" => "Porhoum",
            ],
            [
                "name" => "Porka",
            ],
            [
                "name" => "Sépounga",
            ],
            [
                "name" => "Tchoutchoubou",
            ],
            [
                "name" => "Tiélé",
            ],
            [
                "name" => "Yarka",
            ],
            [
                "name" => "Batia",
            ],
            [
                "name" => "Kayarika",
            ],
            [
                "name" => "Sangou",
            ],
            [
                "name" => "Tanongou",
            ],
            [
                "name" => "Tchafarga",
            ],
            [
                "name" => "Tchatingou",
            ],
            [
                "name" => "Tchawassaka",
            ],
            [
                "name" => "Yangou",
            ],
            [
                "name" => "Bagoubagou",
            ],
            [
                "name" => "Bambaba",
            ],
            [
                "name" => "Bassini",
            ],
            [
                "name" => "Bérékossou",
            ],
            [
                "name" => "Brignamaro",
            ],
            [
                "name" => "Gando baka",
            ],
            [
                "name" => "Kongourou",
            ],
            [
                "name" => "Kossou",
            ],
            [
                "name" => "Kossou-Ouinra",
            ],
            [
                "name" => "Tchoukagnin",
            ],
            [
                "name" => "Yakrigorou",
            ],
            [
                "name" => "Baténin",
            ],
            [
                "name" => "Djoléni",
            ],
            [
                "name" => "Gori",
            ],
            [
                "name" => "Gorobani",
            ],
            [
                "name" => "Kabongourou",
            ],
            [
                "name" => "Sokoungourou",
            ],
            [
                "name" => "Yiroubara",
            ],
            [
                "name" => "Gnampoli",
            ],
            [
                "name" => "Gnampoli",
            ],
            [
                "name" => "Kaobagou",
            ],
            [
                "name" => "Yinsiga",
            ],
            [
                "name" => "Bakoussarou",
            ],
            [
                "name" => "Bipotoko",
            ],
            [
                "name" => "Boukoubourou",
            ],
            [
                "name" => "Fêtêkou",
            ],
            [
                "name" => "Fêtêkou -Alaga",
            ],
            [
                "name" => "Gamboré",
            ],
            [
                "name" => "Gantodo",
            ],
            [
                "name" => "Gnangnanou",
            ],
            [
                "name" => "Gougninnou",
            ],
            [
                "name" => "Karigourou",
            ],
            [
                "name" => "Kédarou",
            ],
            [
                "name" => "Kérou Wirou",
            ],
            [
                "name" => "Kokokou",
            ],
            [
                "name" => "Kparatégui",
            ],
            [
                "name" => "Manou",
            ],
            [
                "name" => "Ouoré",
            ],
            [
                "name" => "Pikiré-Adaga",
            ],
            [
                "name" => "Pikiré",
            ],
            [
                "name" => "Sinagourou",
            ],
            [
                "name" => "Toudakou Banyirou",
            ],
            [
                "name" => "Warou N’Gourou",
            ],
            [
                "name" => "Yakin-Motoko",
            ],
            [
                "name" => "Birni Maro",
            ],
            [
                "name" => "Birni-Kankoulka",
            ],
            [
                "name" => "Birni-Kpébirou",
            ],
            [
                "name" => "Gorgoba",
            ],
            [
                "name" => "Goufanrou",
            ],
            [
                "name" => "Hongon",
            ],
            [
                "name" => "Kouboro",
            ],
            [
                "name" => "Tamandé",
            ],
            [
                "name" => "Tassigourou",
            ],
            [
                "name" => "Yakabissi",
            ],
            [
                "name" => "Chabi Couma",
            ],
            [
                "name" => "Gantiéco",
            ],
            [
                "name" => "Gbéniki",
            ],
            [
                "name" => "Papatia",
            ],
            [
                "name" => "Sakasson-Ditamari",
            ],
            [
                "name" => "Sakasson-Dompago",
            ],
            [
                "name" => "Wémè",
            ],
            [
                "name" => "Boroyindé",
            ],
            [
                "name" => "Danri",
            ],
            [
                "name" => "Foo",
            ],
            [
                "name" => "Kabaré",
            ],
            [
                "name" => "Maka",
            ],
            [
                "name" => "Orouboussoukou",
            ],
            [
                "name" => "Tancé",
            ],
            [
                "name" => "Tikou",
            ],
            [
                "name" => "Boro",
            ],
            [
                "name" => "Damouti",
            ],
            [
                "name" => "Foo-mama",
            ],
            [
                "name" => "Gora -Peulh",
            ],
            [
                "name" => "Goutéré",
            ],
            [
                "name" => "Guilmaro-Bounkossorou",
            ],
            [
                "name" => "Guilmaro-Garkousson",
            ],
            [
                "name" => "Guilmaro-Sinakpagourou",
            ],
            [
                "name" => "Kèdékou",
            ],
            [
                "name" => "Kpakou-Tankonga",
            ],
            [
                "name" => "Kpikiré  koka",
            ],
            [
                "name" => "Nassoukou",
            ],
            [
                "name" => "Ouroufina",
            ],
            [
                "name" => "Séri",
            ],
            [
                "name" => "Sonnougobérou",
            ],
            [
                "name" => "Bassilou",
            ],
            [
                "name" => "Becket-Bouramè",
            ],
            [
                "name" => "Becket-Peulh",
            ],
            [
                "name" => "Boré",
            ],
            [
                "name" => "Darou-Wirou",
            ],
            [
                "name" => "Kpessinin",
            ],
            [
                "name" => "Makrou-Gourou",
            ],
            [
                "name" => "Maro",
            ],
            [
                "name" => "Mary",
            ],
            [
                "name" => "Sakabou",
            ],
            [
                "name" => "Sékogourou",
            ],
            [
                "name" => "Sékogourou-Baïla",
            ],
            [
                "name" => "Sinakpaworou",
            ],
            [
                "name" => "Sowa",
            ],
            [
                "name" => "Tokoro",
            ],
            [
                "name" => "Zongo",
            ],
            [
                "name" => "Boroukou-Peulh",
            ],
            [
                "name" => "Dèkèrou",
            ],
            [
                "name" => "Ganikpérou",
            ],
            [
                "name" => "Poupouré",
            ],
            [
                "name" => "Niarissinra",
            ],
            [
                "name" => "Niaro-Gninon",
            ],
            [
                "name" => "Orougbéni",
            ],
            [
                "name" => "Niarosson",
            ],
            [
                "name" => "Kètéré",
            ],
            [
                "name" => "Nièkènè-Bansou",
            ],
            [
                "name" => "Somboko",
            ],
            [
                "name" => "Kpankpankou",
            ],
            [
                "name" => "Oroukayo",
            ],
            [
                "name" => "Yinkènè",
            ],
            [
                "name" => "Pélima",
            ],
            [
                "name" => "Kpéssourou",
            ],
            [
                "name" => "Dikouan",
            ],
            [
                "name" => "Katanginka",
            ],
            [
                "name" => "Kouaba",
            ],
            [
                "name" => "Koukouabirgou",
            ],
            [
                "name" => "Kounitchangou",
            ],
            [
                "name" => "Koutanongou",
            ],
            [
                "name" => "Kouwanwangou",
            ],
            [
                "name" => "Moussansamou",
            ],
            [
                "name" => "Tagahei",
            ],
            [
                "name" => "Tedonté",
            ],
            [
                "name" => "Tipéti",
            ],
            [
                "name" => "Kouandata",
            ],
            [
                "name" => "Kouatidabirgou",
            ],
            [
                "name" => "Kounadorgou",
            ],
            [
                "name" => "Koutie",
            ],
            [
                "name" => "Tigninti",
            ],
            [
                "name" => "Bangrétamou",
            ],
            [
                "name" => "Dokondé",
            ],
            [
                "name" => "Fayouré",
            ],
            [
                "name" => "Kampouya",
            ],
            [
                "name" => "Kota-Monnongou",
            ],
            [
                "name" => "Kotopounga",
            ],
            [
                "name" => "Onsikoto",
            ],
            [
                "name" => "Pouya",
            ],
            [
                "name" => "Souroukou",
            ],
            [
                "name" => "Tampèdèma",
            ],
            [
                "name" => "Tchantangou",
            ],
            [
                "name" => "Wètipounga",
            ],
            [
                "name" => "Yakpangoutingou",
            ],
            [
                "name" => "Yarikou",
            ],
            [
                "name" => "Ditawan",
            ],
            [
                "name" => "Doyakou",
            ],
            [
                "name" => "Koudengou",
            ],
            [
                "name" => "Péporiyakou",
            ],
            [
                "name" => "Tétanté",
            ],
            [
                "name" => "Tikouani",
            ],
            [
                "name" => "Toroubou",
            ],
            [
                "name" => "Gnagnammou",
            ],
            [
                "name" => "Koka",
            ],
            [
                "name" => "Koubirgou",
            ],
            [
                "name" => "Kouètèna",
            ],
            [
                "name" => "Kounapèigou",
            ],
            [
                "name" => "Koupéico",
            ],
            [
                "name" => "Koussigou",
            ],
            [
                "name" => "Pam-pam",
            ],
            [
                "name" => "Perma Centre",
            ],
            [
                "name" => "Sinaïciré",
            ],
            [
                "name" => "Tènounkontè",
            ],
            [
                "name" => "Tèpéntè",
            ],
            [
                "name" => "Tignanpéti",
            ],
            [
                "name" => "Bagri",
            ],
            [
                "name" => "Djindjiré-béri",
            ],
            [
                "name" => "Kantchagoutamou",
            ],
            [
                "name" => "Sountchirantikou",
            ],
            [
                "name" => "Tchirimina",
            ],
            [
                "name" => "Yokossi",
            ],
            [
                "name" => "Bokoro",
            ],
            [
                "name" => "Boriyouré",
            ],
            [
                "name" => "Dassakaté",
            ],
            [
                "name" => "Ouroubonna",
            ],
            [
                "name" => "Ourkparbou",
            ],
            [
                "name" => "Santa",
            ],
            [
                "name" => "Bérécingou",
            ],
            [
                "name" => "Didapoumbor",
            ],
            [
                "name" => "Kantaborifa",
            ],
            [
                "name" => "Koussantikou",
            ],
            [
                "name" => "Ourbouga",
            ],
            [
                "name" => "Winkè",
            ],
            [
                "name" => "Yétapo",
            ],
            [
                "name" => "Yimporima",
            ],
            [
                "name" => "Koutié Tchatido",
            ],
            [
                "name" => "Kouwa n’pongou",
            ],
            [
                "name" => "Moupémou",
            ],


            [
                "name" => "Takonta",
            ],
            [
                "name" => "Tchoumi-tchoumi",
            ],
            [
                "name" => "Wimmou",
            ],
            [
                "name" => "Bonigourou",
            ],
            [
                "name" => "Dôh",
            ],
            [
                "name" => "Gnémasson",
            ],
            [
                "name" => "Gnémasson-Gando",
            ],
            [
                "name" => "Sayakrou",
            ],
            [
                "name" => "Sayakrou – Gah",
            ],
            [
                "name" => "Bêket",
            ]
        ];
        foreach ($areas as $area) {
            \App\Models\Area::factory()->create($area);
        };

        ##======== CREATION DES TYPES DE MAISONS ============####
        $houseTypes = [
            [
                "name" => "MNDCCom",
                "description" => "Maison non dallée cours communne",
            ],
            [
                "name" => "R1",
                "description" => "Maison R+1",
            ],
            [
                "name" => "R2",
                "description" => "Maison R+2",
            ],
            [
                "name" => "MEUBLEE",
                "description" => "Meublée",
            ],
        ];

        foreach ($houseTypes as $houseType) {
            \App\Models\HouseType::factory()->create($houseType);
        };

        ##======== CREATION DES TYPES DE COMPTEUR ============####
        $counterTypes = [
            [
                "name" => "Elestronique",
                "description" => "compteurs électroniques",
            ],
            [
                "name" => "Electromagnetiques",
                "description" => "compteurs électromécaniques",
            ]
        ];

        foreach ($counterTypes as $counterType) {
            \App\Models\CounterType::factory()->create($counterType);
        };

        ##======== CREATION DES CURRENCIES ============####
        $currencies = [
            [
                "name" => "(South) Korean Won",
                "iso" => "KRW",
            ],
            [
                "name" => "Afghanistan Afghani",
                "iso" => "AFA",
            ],
            [
                "name" => "Albanian Lek",
                "iso" => "ALL",
            ],
            [
                "name" => "Algerian Dinar",
                "iso" => "DZD",
            ],
            [
                "name" => "Andorran Peseta",
                "iso" => "ADP",
            ],
            [
                "name" => "Angolan Kwanza",
                "iso" => "AOK",
            ],
            [
                "name" => "Argentine Peso",
                "iso" => "ARS",
            ],
            [
                "name" => "Armenian Dram",
                "iso" => "AMD",
            ],
            [
                "name" => "Aruban Florin",
                "iso" => "AWG",
            ],
            [
                "name" => "Australian Dollar",
                "iso" => "AUD",
            ],
            [
                "name" => "Bahamian Dollar",
                "iso" => "BSD",
            ],
            [
                "name" => "Bahraini Dinar",
                "iso" => "BHD",
            ],
            [
                "name" => "Bangladeshi Taka",
                "iso" => "BDT",
            ],
            [
                "name" => "Barbados Dollar",
                "iso" => "BBD",
            ],
            [
                "name" => "Belize Dollar",
                "iso" => "BMD",
            ],
            [
                "name" => "Bhutan Ngultrum",
                "iso" => "BTN",
            ],
            [
                "name" => "Bhutan Ngultrum",
                "iso" => "BOB",
            ],
            [
                "name" => "Botswanian Pula",
                "iso" => "BWP",
            ],
            [
                "name" => "Brazilian Real",
                "iso" => "BRL",
            ],
            [
                "name" => "British Pound",
                "iso" => "GBP",
            ],
            [
                "name" => "Brunei Dollar",
                "iso" => "BND",
            ],
            [
                "name" => "Bulgarian Lev",
                "iso" => "BGN",
            ],
            [
                "name" => "Burma Kyat",
                "iso" => "BUK",
            ],
            [
                "name" => "Burundi Franc",
                "iso" => "BIF",
            ],
            [
                "name" => "Canadian Dollar",
                "iso" => "CAD",
            ],
            [
                "name" => "Cape Verde Escudo",
                "iso" => "CVE",
            ],
            [
                "name" => "Cayman Islands Dollar",
                "iso" => "KYD",
            ],
            [
                "name" => "Chilean Peso",
                "iso" => "CLP",
            ],
            [
                "name" => "Chilean Unidades de Fomento",
                "iso" => "CLF",
            ],
            [
                "name" => "Colombian Peso",
                "iso" => "COP",
            ],
            [
                "name" => "Communauté Financière Africaine BCEAO - Francs",
                "iso" => "XOF",
            ],
            [
                "name" => "Communauté Financière Africaine BEAC, Francs",
                "iso" => "XAF",
            ],
            [
                "name" => "Comoros Franc",
                "iso" => "KMF",
            ],
            [
                "name" => "Comptoirs Français du Pacifique Francs",
                "iso" => "XPF",
            ],
            [
                "name" => "Costa Rican Colon",
                "iso" => "CRC",
            ],
            [
                "name" => "Cuban Peso",
                "iso" => "CUP",
            ],
            [
                "name" => "Cyprus Pound",
                "iso" => "CYP",
            ],
            [
                "name" => "Czech Republic Koruna",
                "iso" => "CZK",
            ],
            [
                "name" => "Danish Krone",
                "iso" => "DKK",
            ],
            [
                "name" => "Democratic Yemeni Dinar",
                "iso" => "YDD",
            ],
            [
                "name" => "Dominican Peso",
                "iso" => "DOP",
            ],
            [
                "name" => "East Caribbean Dollar",
                "iso" => "XCD",
            ],
            [
                "name" => "East Timor Escudo",
                "iso" => "TPE",
            ],
            [
                "name" => "Ecuador Sucre",
                "iso" => "ECS",
            ],
            [
                "name" => "Egyptian Pound",
                "iso" => "EGP",
            ],
            [
                "name" => "El Salvador Colon",
                "iso" => "SVC",
            ],
            [
                "name" => "Estonian Kroon (EEK)",
                "iso" => "EEK",
            ],
            [
                "name" => "Ethiopian Birr",
                "iso" => "ETB",
            ],
            [
                "name" => "Euro",
                "iso" => "EUR",
            ],
            [
                "name" => "Falkland Islands Pound",
                "iso" => "FKP",
            ],
            [
                "name" => "Fiji Dollar",
                "iso" => "FJD",
            ],
            [
                "name" => "Gambian Dalasi",
                "iso" => "GMD",
            ],
            [
                "name" => "Ghanaian Cedi",
                "iso" => "GHC",
            ],
            [
                "name" => "Gibraltar Pound",
                "iso" => "GIP",
            ],
            [
                "name" => "Gold, Ounces",
                "iso" => "XAU",
            ],
            [
                "name" => "Guatemalan Quetzal",
                "iso" => "GTQ",
            ],
            [
                "name" => "Guinea Franc",
                "iso" => "GNF",
            ],
            [
                "name" => "Guinea-Bissau Peso",
                "iso" => "GWP",
            ],
            [
                "name" => "Guyanan Dollar",
                "iso" => "GYD",
            ],
            [
                "name" => "Haitian Gourde",
                "iso" => "HTG",
            ],
            [
                "name" => "Honduran Lempira",
                "iso" => "HNL",
            ],
            [
                "name" => "Hong Kong Dollar",
                "iso" => "HKD",
            ],
            [
                "name" => "Hungarian Forint",
                "iso" => "HUF",
            ],
            [
                "name" => "Indian Rupee",
                "iso" => "INR",
            ],
            [
                "name" => "Indonesian Rupiah",
                "iso" => "IDR",
            ],
            [
                "name" => "International Monetary Fund (IMF) Special Drawing Rights",
                "iso" => "XDR",
            ],
            [
                "name" => "Iranian Rial",
                "iso" => "IRR",
            ],
            [
                "name" => "Iraqi Dinar",
                "iso" => "IQD",
            ],
            [
                "name" => "Irish Punt",
                "iso" => "IEP",
            ],
            [
                "name" => "Israeli Shekel",
                "iso" => "ILS",
            ],
            [
                "name" => "Jamaican Dollar",
                "iso" => "JMD",
            ],
            [
                "name" => "Japanese Yen",
                "iso" => "JPY",
            ],
            [
                "name" => "Jordanian Dinar",
                "iso" => "JOD",
            ],
            [
                "name" => "Kampuchean (Cambodian) Riel",
                "iso" => "KHR",
            ],
            [
                "name" => "Kenyan Schilling",
                "iso" => "KES",
            ],
            [
                "name" => "Kuwaiti Dinar",
                "iso" => "KWD",
            ],
            [
                "name" => "Lao Kip",
                "iso" => "LAK",
            ],
            [
                "name" => "Lebanese Pound",
                "iso" => "LBP",
            ],
            [
                "name" => "Lesotho Loti",
                "iso" => "LSL",
            ],
            [
                "name" => "Liberian Dollar",
                "iso" => "LRD",
            ],
            [
                "name" => "Libyan Dinar",
                "iso" => "LYD",
            ],
            [
                "name" => "Macau Pataca",
                "iso" => "MOP",
            ],
            [
                "name" => "Malagasy Franc",
                "iso" => "MGF",
            ],
            [
                "name" => "Malawi Kwacha",
                "iso" => "MWK",
            ],
            [
                "name" => "Malaysian Ringgit",
                "iso" => "MYR",
            ],
            [
                "name" => "Maldive Rufiyaa",
                "iso" => "MVR",
            ],
            [
                "name" => "Maltese Lira",
                "iso" => "MTL",
            ],
            [
                "name" => "Mauritanian Ouguiya",
                "iso" => "MRO",
            ],
            [
                "name" => "Mauritius Rupee",
                "iso" => "MUR",
            ],
            [
                "name" => "Mexican Peso",
                "iso" => "MXP",
            ],
            [
                "name" => "Mongolian Tugrik",
                "iso" => "MNT",
            ],
            [
                "name" => "Moroccan Dirham",
                "iso" => "MAD",
            ],
            [
                "name" => "Mozambique Metical",
                "iso" => "MZM",
            ],
            [
                "name" => "Namibian Dollar",
                "iso" => "NAD",
            ],
            [
                "name" => "Nepalese Rupee",
                "iso" => "NPR",
            ],
            [
                "name" => "Netherlands Antillian Guilder",
                "iso" => "ANG",
            ],
            [
                "name" => "New Yugoslavia Dinar",
                "iso" => "YUD",
            ],
            [
                "name" => "New Zealand Dollar",
                "iso" => "NZD",
            ],
            [
                "name" => "Nicaraguan Cordoba",
                "iso" => "NIO",
            ],
            [
                "name" => "Nigerian Naira",
                "iso" => "NGN",
            ],
            [
                "name" => "North Korean Won",
                "iso" => "KPW",
            ],
            [
                "name" => "Norwegian Kroner",
                "iso" => "NOK",
            ],
            [
                "name" => "Omani Rial",
                "iso" => "OMR",
            ],
            [
                "name" => "Pakistan Rupee",
                "iso" => "PKR",
            ],
            [
                "name" => "Palladium Ounces",
                "iso" => "XPD",
            ],
            [
                "name" => "Panamanian Balboa",
                "iso" => "PAB",
            ],
            [
                "name" => "Papua New Guinea Kina",
                "iso" => "PGK",
            ],
            [
                "name" => "Paraguay Guarani",
                "iso" => "PYG",
            ],
            [
                "name" => "Peruvian Nuevo Sol",
                "iso" => "PEN",
            ],
            [
                "name" => "Philippine Peso",
                "iso" => "PHP",
            ],
            [
                "name" => "Platinum, Ounces",
                "iso" => "XPT",
            ],
            [
                "name" => "Polish Zloty",
                "iso" => "PLN",
            ],
            [
                "name" => "Qatari Rial",
                "iso" => "QAR",
            ],
            [
                "name" => "Romanian Leu",
                "iso" => "RON",
            ],
            [
                "name" => "Russian Ruble",
                "iso" => "RUB",
            ],
            [
                "name" => "Rwanda Franc",
                "iso" => "RWF",
            ],
            [
                "name" => "Samoan Tala",
                "iso" => "WST",
            ],
            [
                "name" => "Sao Tome and Principe Dobra",
                "iso" => "STD",
            ],
            [
                "name" => "Saudi Arabian Riyal",
                "iso" => "SAR",
            ],
            [
                "name" => "Seychelles Rupee",
                "iso" => "SCR",
            ],
            [
                "name" => "Sierra Leone Leone",
                "iso" => "SLL",
            ],
            [
                "name" => "Silver, Ounces",
                "iso" => "XAG",
            ],
            [
                "name" => "Singapore Dollar",
                "iso" => "SGD",
            ],
            [
                "name" => "Slovak Koruna",
                "iso" => "SKK",
            ],
            [
                "name" => "Solomon Islands Dollar",
                "iso" => "SBD",
            ],
            [
                "name" => "Somali Schilling",
                "iso" => "SOS",
            ],
            [
                "name" => "South African Rand",
                "iso" => "ZAR",
            ],
            [
                "name" => "Sri Lanka Rupee",
                "iso" => "LKR",
            ],
            [
                "name" => "St. Helena Pound",
                "iso" => "SHP",
            ],
            [
                "name" => "Sudanese Pound",
                "iso" => "SDP",
            ],
            [
                "name" => "Suriname Guilder",
                "iso" => "SRG",
            ],
            [
                "name" => "Swaziland Lilangeni",
                "iso" => "SZL",
            ],
            [
                "name" => "Swedish Krona",
                "iso" => "SEK",
            ],
            [
                "name" => "Swiss Franc",
                "iso" => "CHF",
            ],
            [
                "name" => "Syrian Potmd",
                "iso" => "SYP",
            ],
            [
                "name" => "Taiwan Dollar",
                "iso" => "TWD",
            ],
            [
                "name" => "Tanzanian Schilling",
                "iso" => "TZS",
            ],
            [
                "name" => "Thai Baht",
                "iso" => "THB",
            ],
            [
                "name" => "Tongan Paanga",
                "iso" => "TOP",
            ],
            [
                "name" => "Trinidad and Tobago Dollar",
                "iso" => "TTD",
            ],
            [
                "name" => "Tunisian Dinar",
                "iso" => "TND",
            ],
            [
                "name" => "Turkish Lira",
                "iso" => "TRY",
            ],
            [
                "name" => "Uganda Shilling",
                "iso" => "UGX",
            ],
            [
                "name" => "United Arab Emirates Dirham",
                "iso" => "AED",
            ],
            [
                "name" => "Uruguayan Peso",
                "iso" => "UYU",
            ],
            [
                "name" => "US Dollar",
                "iso" => "USD",
            ],
            [
                "name" => "Vanuatu Vatu",
                "iso" => "VUV",
            ],
            [
                "name" => "Venezualan Bolivar",
                "iso" => "VEF",
            ],
            [
                "name" => "Vietnamese Dong",
                "iso" => "VND",
            ],
            [
                "name" => "Yemeni Rial",
                "iso" => "YER",
            ],
            [
                "name" => "Yuan (Chinese) Renminbi",
                "iso" => "CNY",
            ],
            [
                "name" => "Zaire Zaire",
                "iso" => "ZRZ",
            ],
            [
                "name" => "Zambian Kwacha",
                "iso" => "ZMK",
            ],
            [
                "name" => "Zimbabwe Dollar",
                "iso" => "ZWD",
            ]
        ];

        foreach ($currencies as $currencie) {
            \App\Models\Currency::factory()->create($currencie);
        };

        ##======== CREATION DES TYPES DE CARD ============####
        $cardTypes = [
            [
                "name" => "CIN",
            ],
            [
                "name" => "LEPI",
            ],
            [
                "name" => "PASSPORT",
            ]
        ];

        foreach ($cardTypes as $cardType) {
            \App\Models\CardType::factory()->create($cardType);
        };

        ##======== CREATION DES DEPARTEMENTS ============####
        $departements = [
            [
                "name" => "Ouémé",
            ],
            [
                "name" => "Plateau",
            ],
            [
                "name" => "Atlantique",
            ],
            [
                "name" => "Littoral",
            ],
            [
                "name" => "Atlantique",
            ],
            [
                "name" => "Mono",
            ],
            [
                "name" => "Couffo",
            ],
            [
                "name" => "Zou",
            ],
            [
                "name" => "Collines",
            ],
            [
                "name" => "Donga",
            ],
            [
                "name" => "Borgou",
            ],
            [
                "name" => "Alibori",
            ],
            [
                "name" => "Atacora",
            ]
        ];

        foreach ($departements as $departement) {
            \App\Models\Departement::factory()->create($departement);
        };

        ##======== CREATION DES TYPES DE CHAMBRE ============####
        $roomTypes = [
            [
                "name" => "2CH1S",
                "description" => "2 Chambres, 1 Salon",
            ],
            [
                "name" => "1CH1S",
                "description" => "1 Chambre, 1 Salon",
            ],
            [
                "name" => "3CH1S",
                "description" => "3 Chambres, 1 Salon",
            ],
            [
                "name" => "MAGASIN",
                "description" => "Magasin",
            ],
            [
                "name" => "STUDIO",
                "description" => "Studio",
            ],
            [
                "name" => "BOUTIQUE",
                "description" => "Boutique",
            ],
            [
                "name" => "PARCELLE NUE",
                "description" => "Parcelle nue",
            ]
        ];

        foreach ($roomTypes as $roomType) {
            \App\Models\RoomType::factory()->create($roomType);
        };

        ##======== CREATION DES NATURES DE CHAMBRE ============####
        $roomNatures = [
            [
                "name" => "SANITAIRE",
                "description" => "Sanitaire non meublée",
            ],
            [
                "name" => "ORDINANIRE",
                "description" => "Ordinaire",
            ],
            [
                "name" => "SEMI",
                "description" => "Semi Sanitaire",
            ],
            [
                "name" => "SANITAIRE-MEUBLEE",
                "description" => "Sanitaire meublée",
            ]
        ];

        foreach ($roomNatures as $roomNature) {
            \App\Models\RoomNature::factory()->create($roomNature);
        };


        ##======== CREATION DES ZONES ============####
        $zones = [
            [
                "name" => "Angaradebou",
                "city" => 1,
            ],
            [
                "name" => "Bensekou",
                "city" => 1,
            ],
            [
                "name" => "Donwari",
                "city" => 1,
            ],
            [
                "name" => "Kandi 1",
                "city" => 1,
            ],
            [
                "name" => "Kandi 2",
                "city" => 1,
            ],
            [
                "name" => "Kandi 3",
                "city" => 1,
            ],
            [
                "name" => "Kassakou",
                "city" => 1,
            ],
            [
                "name" => "Saah",
                "city" => 1,
            ],
            [
                "name" => "Sam",
                "city" => 1,
            ],
            [
                "name" => "Sonsoro",
                "city" => 1,
            ],
            [
                "name" => "Garou",
                "city" => 2,
            ],
            [
                "name" => "Guéné",
                "city" => 2,
            ],
            [
                "name" => "Madécali",
                "city" => 2,
            ],
            [
                "name" => "Malanville",
                "city" => 2,
            ],
            [
                "name" => "Tomboutou",
                "city" => 2,
            ],
            [
                "name" => "Birni Lafia",
                "city" => 3,
            ],
            [
                "name" => "Bogo-Bogo",
                "city" => 3,
            ],
            [
                "name" => "Karimama",
                "city" => 3,
            ],
            [
                "name" => "Kompa",
                "city" => 3,
            ],
            [
                "name" => "Monsey",
                "city" => 3,
            ],
            [
                "name" => "Banikoara",
                "city" => 4,
            ],
            [
                "name" => "Founougo",
                "city" => 4,
            ],
            [
                "name" => "Gomparou",
                "city" => 4,
            ],
            [
                "name" => "Goumori",
                "city" => 4,
            ],
            [
                "name" => "Kokey",
                "city" => 4,
            ],
            [
                "name" => "Kokiborou",
                "city" => 4,
            ],
            [
                "name" => "Ounet",
                "city" => 4,
            ],
            [
                "name" => "Sompéroukou",
                "city" => 4,
            ],
            [
                "name" => "Soroko",
                "city" => 4,
            ],
            [
                "name" => "Toura",
                "city" => 4,
            ],



            [
                "name" => "Bagou",
                "city" => 5,
            ],
            [
                "name" => "Gogounou",
                "city" => 5,
            ],
            [
                "name" => "Gounarou",
                "city" => 5,
            ],
            [
                "name" => "Sori",
                "city" => 5,
            ],
            [
                "name" => "Sougou-Kpan-Trossi",
                "city" => 5,
            ],
            [
                "name" => "Wara",
                "city" => 5,
            ],
            [
                "name" => "Libante",
                "city" => 6,
            ],
            [
                "name" => "Liboussou",
                "city" => 6,
            ],
            [
                "name" => "Liboussou",
                "city" => 6,
            ],
            [
                "name" => "Lougou",
                "city" => 6,
            ],
            [
                "name" => "Ségbana",
                "city" => 6,
            ],
            [
                "name" => "Sokotindji",
                "city" => 6,
            ],
            [
                "name" => "Boukoumbé",
                "city" => 7,
            ],
            [
                "name" => "Dipoli",
                "city" => 7,
            ],
            [
                "name" => "Korontiere",
                "city" => 7,
            ],
            [
                "name" => "Koussoucoingou",
                "city" => 7,
            ],
            [
                "name" => "Manta",
                "city" => 7,
            ],
            [
                "name" => "Nata",
                "city" => 7,
            ],
            [
                "name" => "Tabota",
                "city" => 7,
            ],
            [
                "name" => "Cobly",
                "city" => 8,
            ],
            [
                "name" => "Tapoga",
                "city" => 8,
            ],
            [
                "name" => "Datori",
                "city" => 8,
            ],
            [
                "name" => "Kountori",
                "city" => 8,
            ],
            [
                "name" => "Dassari",
                "city" => 9,
            ],
            [
                "name" => "Gouandé",
                "city" => 9,
            ],
            [
                "name" => "Matéri",
                "city" => 9,
            ],
            [
                "name" => "Nodi	",
                "city" => 9,
            ],
            [
                "name" => "Tantega",
                "city" => 9,
            ],
            [
                "name" => "Tchanhouncossi",
                "city" => 9,
            ],
            [
                "name" => "Cotiakou",
                "city" => 10,
            ],
            [
                "name" => "N'Dahonta",
                "city" => 10,
            ],
            [
                "name" => "Taiacou",
                "city" => 10,
            ],
            [
                "name" => "Tanguiéta",
                "city" => 10,
            ],
            [
                "name" => "Tanongou",
                "city" => 10,
            ],
            [
                "name" => "Brignamaro",
                "city" => 11,
            ],
            [
                "name" => "Firou",
                "city" => 11,
            ],
            [
                "name" => "Kaobagou	",
                "city" => 11,
            ],
            [
                "name" => "Kérou",
                "city" => 11,
            ],
            [
                "name" => "Birni",
                "city" => 12,
            ],
            [
                "name" => "Foo-Tance",
                "city" => 12,
            ],
            [
                "name" => "Guilmaro",
                "city" => 12,
            ],
            [
                "name" => "Kouandé",
                "city" => 12,
            ],
            [
                "name" => "Oroukayo",
                "city" => 12,
            ],
            [
                "name" => "Kouaba",
                "city" => 13,
            ],
            [
                "name" => "Kouandata",
                "city" => 13,
            ],
            [
                "name" => "Kotopounga",
                "city" => 13,
            ],
            [
                "name" => "Peporiyakou",
                "city" => 13,
            ],
            [
                "name" => "Perma",
                "city" => 13,
            ],
            [
                "name" => "Natitingou 1",
                "city" => 13,
            ],
            [
                "name" => "Natitingou 2",
                "city" => 13,
            ],
            [
                "name" => "Natitingou 3",
                "city" => 13,
            ],
            [
                "name" => "Tchoumi-Tchoumi",
                "city" => 13,
            ],
            [
                "name" => "Gnemasson",
                "city" => 14,
            ],
            [
                "name" => "Pehunco",
                "city" => 14,
            ],
            [
                "name" => "Tobré",
                "city" => 14,
            ],
            [
                "name" => "Kouarfa",
                "city" => 15,
            ],
            [
                "name" => "Tampégré",
                "city" => 15,
            ],
            [
                "name" => "Toukountouna",
                "city" => 15,
            ],
            [
                "name" => "Agbanou",
                "city" => 15,
            ],
            [
                "name" => "Allada Centre",
                "city" => 16,
            ],
            [
                "name" => "Attogon",
                "city" => 16,
            ],
            [
                "name" => "Avakpa",
                "city" => 16,
            ],
            [
                "name" => "Ayou",
                "city" => 16,
            ],
            [
                "name" => "Hinvi",
                "city" => 16,
            ],
            [
                "name" => "Lissègazoun",
                "city" => 16,
            ],
            [
                "name" => "Lon-Agonmey",
                "city" => 16,
            ],
            [
                "name" => "Sékou",
                "city" => 16,
            ],
            [
                "name" => "Togoudo",
                "city" => 16,
            ],
            [
                "name" => "Tokpa",
                "city" => 16,
            ],
            [
                "name" => "Agbanto",
                "city" => 17,
            ],
            [
                "name" => "Agonkanmè",
                "city" => 17,
            ],
            [
                "name" => "Dékanmè",
                "city" => 17,
            ],
            [
                "name" => "Dédomè",
                "city" => 17,
            ],
            [
                "name" => "Kpomassè Centre",
                "city" => 17,
            ],
            [
                "name" => "Sègbèya",
                "city" => 17,
            ],
            [
                "name" => "Sègbohouè",
                "city" => 17,
            ],
            [
                "name" => "Tokpa-Domè",
                "city" => 17,
            ],
            [
                "name" => "Avlékété",
                "city" => 18,
            ],
            [
                "name" => "Djègbadji",
                "city" => 18,
            ],
            [
                "name" => "Gakpé",
                "city" => 18,
            ],
            [
                "name" => "Houakpè-Daho",
                "city" => 18,
            ],
            [
                "name" => "Pahou",
                "city" => 18,
            ],
            [
                "name" => "Ouidah 1",
                "city" => 18,
            ],
            [
                "name" => "Ouidah 2",
                "city" => 18,
            ],
            [
                "name" => "Ouidah 3",
                "city" => 18,
            ],
            [
                "name" => "Ouidah 4",
                "city" => 18,
            ],
            [
                "name" => "Savi",
                "city" => 18,
            ],
            [
                "name" => "Agué",
                "city" => 19,
            ],
            [
                "name" => "Colli",
                "city" => 19,
            ],
            [
                "name" => "Coussi",
                "city" => 19,
            ],
            [
                "name" => "Damè",
                "city" => 19,
            ],
            [
                "name" => "Djanglanmè",
                "city" => 19,
            ],
            [
                "name" => "Kpomè",
                "city" => 19,
            ],
            [
                "name" => "Houègbo",
                "city" => 19,
            ],
            [
                "name" => "Houègbo",
                "city" => 19,
            ],
            [
                "name" => "Sèhouè",
                "city" => 19,
            ],
            [
                "name" => "Sey",
                "city" => 19,
            ],
            [
                "name" => "Toffo",
                "city" => 19,
            ],
            [
                "name" => "Avamè",
                "city" => 20,
            ],
            [
                "name" => "Azohouè-Aliho",
                "city" => 20,
            ],
            [
                "name" => "Tori-Cada",
                "city" => 20,
            ],
            [
                "name" => "	Tori-Gare",
                "city" => 20,
            ],
            [
                "name" => "	Tori-Bossito",
                "city" => 20,
            ],
            [
                "name" => "Abomey-Calavi",
                "city" => 21,
            ],
            [
                "name" => "Akassato",
                "city" => 21,
            ],
            [
                "name" => "Golo-Djigbé",
                "city" => 21,
            ],
            [
                "name" => "Godomey",
                "city" => 21,
            ],
            [
                "name" => "Hèvié",
                "city" => 21,
            ],
            [
                "name" => "Kpanroun",
                "city" => 21,
            ],
            [
                "name" => "Ouèdo",
                "city" => 21,
            ],
            [
                "name" => "Togba",
                "city" => 21,
            ],
            [
                "name" => "Zinvié",
                "city" => 21,
            ],
            [
                "name" => "Ahomey-Lokpo",
                "city" => 22,
            ],
            [
                "name" => "Dékanmey",
                "city" => 22,
            ],
            [
                "name" => "Ganvié 1",
                "city" => 22,
            ],
            [
                "name" => "Ganvié 2",
                "city" => 22,
            ],
            [
                "name" => "Houedo-Aguekon",
                "city" => 22,
            ],
            [
                "name" => "Sô-Ava",
                "city" => 22,
            ]
        ];

        foreach ($zones as $zone) {
            \App\Models\Zone::factory()->create($zone);
        };


        ##======== CREATION DES QUARTIERS ============####
        $quartiers = [
            [
                "name" => "Ambatta",
            ],
            [
                "name" => "Kilwiti",
            ],
            [
                "name" => "14 Villas",
            ],
            [
                "name" => "15 ans 1",
            ],
            [
                "name" => "15 ans 2",
            ],
            [
                "name" => "Abadago",
            ],
            [
                "name" => "Aballa",
            ],
            [
                "name" => "Abato",
            ],
            [
                "name" => "Abatta",
            ],
            [
                "name" => "Abayahoué",
            ],
            [
                "name" => "Abba",
            ],
            [
                "name" => "Abéokouta",
            ],
            [
                "name" => "Abiadji-Sogoudo",
            ],
            [
                "name" => "Abidomey",
            ],
            [
                "name" => "Abigo",
            ],
            [
                "name" => "Abikouholi",
            ],
            [
                "name" => "Abintaga",
            ],
            [
                "name" => "Ablodé",
            ],
            [
                "name" => "Abloganmè",
            ],
            [
                "name" => "Ablomey",
            ],
            [
                "name" => "Abobokomè",
            ],
            [
                "name" => "Abogomè",
            ],
            [
                "name" => "Abogomè-Hlihouè",
            ],
            [
                "name" => "Abokicodji Centre",
            ],
            [
                "name" => "Abokicodji Lagune",
            ],
            [
                "name" => "Abolou",
            ],
            [
                "name" => "Aboloumè",
            ],
            [
                "name" => "Aboti",
            ],
            [
                "name" => "Abovey",
            ],
            [
                "name" => "Acadjamè",
            ],
            [
                "name" => "Acclohoué",
            ],
            [
                "name" => "Accron-Gogankomey",
            ],
            [
                "name" => "Achawayil",
            ],
            [
                "name" => "Achitou",
            ],
            [
                "name" => "Aclonmè",
            ],
            [
                "name" => "Ada-Kpané",
            ],
            [
                "name" => "Adagamè-Lisèzou",
            ],
            [
                "name" => "Adahoué",
            ],
            [
                "name" => "Adakplamè",
            ],
            [
                "name" => "Adamè",
            ],
            [
                "name" => "Adamè Adato",
            ],
            [
                "name" => "Adamè Ahito",
            ],
            [
                "name" => "Adamè Houeglo",
            ],
            [
                "name" => "Adamou-Kpara",
            ],
            [
                "name" => "Adandéhoué",
            ],
            [
                "name" => "Adandopkodji",
            ],
            [
                "name" => "Adandéhoué",
            ],
            [
                "name" => "Adandro-Akodé",
            ],
            [
                "name" => "Adanhondjigon",
            ],
            [
                "name" => "Adankpé",
            ],
            [
                "name" => "Adankpossi",
            ],
            [
                "name" => "Adanlopké",
            ],
            [
                "name" => "Adanmayi",
            ],
            [
                "name" => "Adanminakougon",
            ],
            [
                "name" => "Adawémè",
            ],
            [
                "name" => "Adawlato",
            ],
            [
                "name" => "Adédéwo",
            ],
            [
                "name" => "Adétikopé",
            ],
            [
                "name" => "Adhamè",
            ],
            [
                "name" => "Adidevo",
            ],
            [
                "name" => "Adido",
            ],
            [
                "name" => "Adihinlidji",
            ],
            [
                "name" => "Adikogon",
            ],
            [
                "name" => "Adikogon",
            ],
            [
                "name" => "Adimado",
            ],
            [
                "name" => "Adimalé",
            ],
            [
                "name" => "Adingnigon",
            ],
            [
                "name" => "Adja",
            ],
            [
                "name" => "Adjacomè",
            ],
            [
                "name" => "Adjadangan",
            ],
            [
                "name" => "Adjadji-Atinkousa",
            ],
            [
                "name" => "Adjadji-Bata",
            ],
            [
                "name" => "Adjadji-Cossoé",
            ],
            [
                "name" => "Adjadji-Zoungbom",
            ],
            [
                "name" => "Adjagbo",
            ],
            [
                "name" => "Adjagbo-Aidjèdo",
            ],
            [
                "name" => "Adjaglimey",
            ],
            [
                "name" => "Adjaglo",
            ],
            [
                "name" => "Adjaha",
            ],
            [
                "name" => "Adjaha-Cité",
            ],
            [
                "name" => "Adjahassa",
            ],
            [
                "name" => "Adjaho",
            ],
            [
                "name" => "Adjahonmè",
            ],
            [
                "name" => "Adjahigbonou",
            ],
            [
                "name" => "Adjakamè",
            ],
            [
                "name" => "Adjamè",
            ],
            [
                "name" => "Adjan",
            ],
            [
                "name" => "Adjan-Gla",
            ],
            [
                "name" => "Adjan-Houéta",
            ],
            [
                "name" => "Adjantè",
            ],
            [
                "name" => "Adjassagnon",
            ],
            [
                "name" => "Adjassinhoun - Condji",
            ]
        ];

        foreach ($quartiers as $quartier) {
            \App\Models\Quarter::factory()->create($quartier);
        };


        ##======== CREATION DES TYPES DE LOCATIONS PAR DEFAUT ============####
        $locationTypes = [
            [
                "name" => "ORDINAIRE",
                "description" => "Location simple",
            ],
            [
                "name" => "BAIL",
                "description" => "Location longue durée",
            ]
        ];

        foreach ($locationTypes as $locationType) {
            \App\Models\LocationType::factory()->create($locationType);
        };

        ##======== CREATION DES MODULES DE PAIEMENT PAR DEFAUT ============####
        $paiementModules = [
            [
                "name" => "FAI",
                "description" => "Fournisseur d'Accès Internet",
            ],
            [
                "name" => "IMMO",
                "description" => "Agence Immobilière",
            ],
            [
                "name" => "GCAD",
                "description" => "Gestionnaire Courrier Administratif",
            ],
            [
                "name" => "FINANCE",
                "description" => "Gestionnaire Financière",
            ],
            [
                "name" => "TICKETING",
                "description" => "Gestion ticketing",
            ]
        ];

        foreach ($paiementModules as $paiementModule) {
            \App\Models\PaiementModule::factory()->create($paiementModule);
        };

        ##======== CREATION DES STATUS DE PAIEMENT PAR DEFAUT ============####
        $paiementStatus = [
            [
                "name" => "PENDING",
                "description" => "PENDING - ATTENTE",
            ],
            [
                "name" => "CANCEL",
                "description" => "CANCEL - ANNULE",
            ],
            [
                "name" => "SUCCESS",
                "description" => "SUCCESS - SUCCES",
            ],
            [
                "name" => "PROCESSING",
                "description" => "PROCESSING - EN COURS DE TRAITEMENT",
            ]
        ];

        foreach ($paiementStatus as $paiementStatu) {
            \App\Models\PaiementStatus::factory()->create($paiementStatu);
        };

        ##======== CREATION DES TYPES DE PAIEMENT PAR DEFAUT ============####
        $paiementTypes = [
            [
                "name" => "MTNMoMo",
                "description" => "Mobile Money MTN",
            ],
            [
                "name" => "MoovMoney",
                "description" => "Mobile Money MOOV",
            ],
            [
                "name" => "VISA",
                "description" => "Carte Prépayées VISA",
            ],
            [
                "name" => "Chèque",
                "description" => "Paiement par chèqure",
            ],
            [
                "name" => "Virement",
                "description" => "Paiement par virement bancaire",
            ],
            [
                "name" => "Espèce",
                "description" => "Paiement espèce",
            ]
        ];

        foreach ($paiementTypes as $paiementType) {
            \App\Models\PaiementType::factory()->create($paiementType);
        };


        ##======== CREATION DES COMPTES PAR DEFAUT ============####
        $immoAcounts = [
            [
                "name" => "BANK",
                "description" => "Banque Loyer",
                "phone" => "22967410929",
                "email" => "info@hard-soft.solution",
                "status" => 1,
                // "client" => 1,
                "type" => 1,
                "plafond_max" => 1000000000,
            ],
            [
                "name" => "CMDE",
                "description" => "Menu dépenses",
                "phone" => "22996805252",
                "email" => "donatien.akpo@jeny.bj",
                "status" => 1,
                "type" => 7,
                "plafond_max" => 1000000000,
            ],
            [
                "name" => "CDR",
                "description" => "Compte Dépense Récuperable",
                "phone" => "22960082727",
                "email" => "pay@frikpay.digital",
                "status" => 1,
                // "client" => 0,
                "type" => 8,
                "plafond_max" => 1000000000,
            ],
            [
                "name" => "Caisse Loyer",
                "description" => "Compte de la Caisse Loyer",
                "phone" => "22967410929",
                "email" => "info@hard-soft.solution",
                "status" => 1,
                // "client" => 1,
                "type" => 1,
                "plafond_max" => 1000000000,
            ],
            [
                "name" => "CPP",
                "description" => "Caisse Propriétaire",
                "phone" => "22967410929",
                "email" => "info@hard-soft.solution",
                "status" => 1,
                // "client" => 1,
                "type" => 1,
                "plafond_max" => 1000000000,
            ],
            [
                "name" => "CC",
                "description" => "Caisse Caution",
                "phone" => "22960082727",
                "email" => "info@hard-soft.solution",
                "status" => 1,
                // "client" => 0,
                "type" => 8,
                "plafond_max" => 1000000000,
            ],
            [
                "name" => "REGULARISATION",
                "description" => "Compte de Régularisation",
                "phone" => "22960082727",
                "email" => "info@hard-soft.solution",
                "status" => 1,
                // "client" => 0,
                "type" => 8,
                "plafond_max" => 1000000000,
            ],
            [
                "name" => "CRCF",
                "description" => "Caisse de Renforcement de Capacité Financière",
                "phone" => "22960082727",
                "email" => "info@hard-soft.solution",
                "status" => 1,
                // "client" => 0,
                "type" => 8,
                "plafond_max" => 1000000000,
            ],
            [
                "name" => "EAU ELECTRICITE",
                "description" => "Caisse de consommation eau-électricité",
                "phone" => "22960082727",
                "email" => "info@hard-soft.solution",
                "status" => 1,
                // "client" => 0,
                "type" => 8,
                "plafond_max" => 1000000000,
            ],
            [
                "name" => "Prestation",
                "description" => "Caisse de prestation",
                "phone" => "22960082727",
                "email" => "info@hard-soft.solution",
                "status" => 1,
                "type" => 8,
                "plafond_max" => 1000000000,
            ]
        ];

        foreach ($immoAcounts as $immoAcount) {
            \App\Models\ImmoAccount::factory()->create($immoAcount);
        };

        ##======== CREATION DES STATUS DE LOCATION PAR DEFAUT ============####
        $locationStatus = [
            [
                "name" => "ACTIVE",
                "description" => "ACTIVE",
            ],
            [
                "name" => "SUSPEND",
                "description" => "IMPAYE",
            ],
            [
                "name" => "MOVED",
                "description" => "DEMENAGEMENT",
            ],
            [
                "name" => "STOP",
                "description" => "ARRETE",
            ],
            [
                "name" => "TERMINATE",
                "description" => "RESILIER",
            ]
        ];

        foreach ($locationStatus as $locationStatu) {
            \App\Models\LocationStatus::factory()->create($locationStatu);
        };

        ##======== CREATION DES TYPES DE FACTURE PAR DEFAUT ============####
        $factureTypes = [
            [
                "name" => "PROFORMA",
                "description" => "Une facture pro forma",
            ],
            [
                "name" => "FACTURE",
                "description" => "Une facture",
            ],
            [
                "name" => "COMPTEUR",
                "description" => "Facture eau ou électricité",
            ]
        ];

        foreach ($factureTypes as $factureType) {
            \App\Models\FactureType::factory()->create($factureType);
        };

        ##======== CREATION DES STATUS DE FACTURE PAR DEFAUT ============####
        $factureStatus = [
            [
                "name" => "VERIFICATION_PENDING",
                "description" => "En attente de vérification",
            ],
            [
                "name" => "VERIFICATION_SUCCESS",
                "description" => "Vérifiée et valider",
            ],
            [
                "name" => "VERIFICATION_REJECT",
                "description" => "Rejeter",
            ],
            [
                "name" => "VERIFICATION_CANCEL",
                "description" => "Annuler",
            ]
        ];

        foreach ($factureStatus as $factureStatu) {
            \App\Models\FactureStatus::factory()->create($factureStatu);
        };

        ##======== CREATION DES TYPES DE CLIENT PAR DEFAUT ============####
        $clientTypes = [
            [
                "name" => "Particulier",
                "description" => "Client Particulier",
            ],
            [
                "name" => "Entreprise",
                "description" => "Entreprise ou société privée",
            ],
            [
                "name" => "Etat",
                "description" => "Entreprise ou société étatique",
            ]
        ];

        foreach ($clientTypes as $clientType) {
            \App\Models\ClientType::factory()->create($clientType);
        };

        ##======== CREATION DES STATUS D'INITIATION DE PAIEMENT PAR DEFAUT ============####
        $payementInitiationStatus = [
            [
                "name" => "Initié",
                "description" => "Initiation de paiement",
            ],
            [
                "name" => "Validé",
                "description" => "Initiation de paiement validée",
            ],
            [
                "name" => "Réjeté",
                "description" => "Initiation de paiement rejeté",
            ]
        ];

        foreach ($payementInitiationStatus as $payementInitiationStatu) {
            \App\Models\PaiementInitiationStatus::factory()->create($payementInitiationStatu);
        };
    }
}
