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
                'description' => 'Cartographie des divisions administratives du Burkina Faso : Régions, Provinces et Communes d\'intervention (Nord et Centre-Ouest).',
                'icon' => 'Map',
                'color' => '#1565C0',
            ],
            [
                'id' => 'resi2p-zones',
                'title' => 'Zones d\'Intervention RESI-2P',
                'description' => 'Présentation des 36 communes d\'intervention du programme dans les régions du Nord et du Centre-Ouest.',
                'icon' => 'MapPin',
                'color' => '#2E7D32',
            ],
            [
                'id' => 'production-resilience',
                'title' => 'Résilience des Systèmes de Production',
                'description' => 'Aménagements de bas-fonds, irrigation, restauration des terres et techniques agricoles résilientes au climat.',
                'icon' => 'Sprout',
                'color' => '#388E3C',
            ],
            [
                'id' => 'market-access',
                'title' => 'Accès aux Marchés',
                'description' => 'Infrastructures de stockage, de transformation, commercialisation et développement des chaînes de valeur agricoles.',
                'icon' => 'ShoppingCart',
                'color' => '#F57C00',
            ],
            [
                'id' => 'capacity-building',
                'title' => 'Renforcement des Capacités',
                'description' => 'Suivi des formations, des champs écoles agropastoraux et de l\'accompagnement technique des producteurs.',
                'icon' => 'GraduationCap',
                'color' => '#1A237E',
            ],
            [
                'id' => 'infrastructures',
                'title' => 'Infrastructures & Aménagements',
                'description' => 'Répertoire géo-référencé des réalisations physiques : périmètres maraîchers, ouvrages hydrauliques et travaux HIMO.',
                'icon' => 'Construction',
                'color' => '#C62828',
            ],
            [
                'id' => 'monitoring-evaluation',
                'title' => 'Suivi-Évaluation & Impact',
                'description' => 'Visualisation des indicateurs de performance, des superficies restaurées et du nombre de bénéficiaires atteints.',
                'icon' => 'BarChart3',
                'color' => '#7B1FA2',
            ],
            [
                'id' => 'localities',
                'title' => 'Localités & Populations',
                'description' => 'Cartographie des villages et données sur les populations cibles : femmes, jeunes et Personnes Déplacées Internes (PDI).',
                'icon' => 'Users',
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
