<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Thematique;

class ThematiqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $thematiques = [
            [
                'id' => 'admin-limits',
                'title' => 'Limites Administratives',
                'description' => 'Cartographie des divisions administratives du Mali : Régions, Cercles et Communes nationales.',
                'icon' => 'Map',
                'color' => '#1565C0',
            ],
            [
                'id' => 'fier2-zones',
                'title' => 'Zones d\'Intervention',
                'description' => 'Ensemble des cartes FIER II présentant les limites d\'intervention, les cours d\'eau et les zones évolutives du projet.',
                'icon' => 'MapPin',
                'color' => '#2E7D32',
            ],
            [
                'id' => 'offre-formation-fp',
                'title' => 'Offre de Formation Professionnelle',
                'description' => 'Cartographie présentant les CFP, les dispositifs régionaux, la répartition des tuteurs et des UMF par zone.',
                'icon' => 'GraduationCap',
                'color' => '#1A237E',
            ],
            [
                'id' => 'renforcement-capacites',
                'title' => 'Renforcement des Capacités',
                'description' => 'Visualisation complète de l\'ensemble des cartes relatives aux personnes formées dans le cadre du projet.',
                'icon' => 'Users',
                'color' => '#F57C00',
            ],
            [
                'id' => 'infrastructures',
                'title' => 'Infrastructures réalisées',
                'description' => 'Cartographie des infrastructures physiques (PIV, PPM, Parcs, Kits, étangs, Centres de formation) filtrable par zone.',
                'icon' => 'Construction',
                'color' => '#C62828',
            ],
            [
                'id' => 'projets-emplois',
                'title' => 'Projets & Emplois',
                'description' => 'Évaluation spatialisée valorisant le nombre de projets réalisés et le volume d\'emplois créés par zone.',
                'icon' => 'Briefcase',
                'color' => '#7B1FA2',
            ],
            [
                'id' => 'developpement-agricole',
                'title' => 'Développement agricole',
                'description' => 'Regroupe les cartes de réalisations et indicateurs en lien avec l\'agriculture, l\'élevage et la pêche.',
                'icon' => 'Sprout',
                'color' => '#689F38',
            ],
            [
                'id' => 'localities',
                'title' => 'Localités & Villages',
                'description' => 'Répertoire géo-référencé des villages du Mali et données de population.',
                'icon' => 'Home',
                'color' => '#FB8C00',
            ],
        ];

        foreach ($thematiques as $thematique) {
            Thematique::updateOrCreate(
                ['id' => $thematique['id']],
                $thematique
            );
        }
    }
}
