<?php

namespace Database\Seeders;

use App\Models\Map;
use Illuminate\Database\Seeder;

class MapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maps = [
            [
                'id' => 'mli-regions-national',
                'id_thematique' => 'admin-limits',
                'title' => 'Limites Administratives (National)',
                'description' => 'Découpage administratif de niveau 1 couvrant l\'ensemble du territoire national (mli_admin01).',
                'url' => 'http://192.162.68.122:8090/geoserver/Mymap/wms?service=WMS&version=1.1.0&request=GetMap&layers=Mymap%3Amli_admin01&bbox=-12.23924%2C10.14137%2C4.24467%2C24.99951&width=768&height=692&srs=EPSG%3A4326&styles=&format=application/openlayers',
                'downloadUrl' => '#',
                'thumbnail' => '/images/maps/segouRegion.jpg',
            ],
            [
                'id' => 'regions-fier2',
                'id_thematique' => 'fier2-zones',
                'title' => 'Régions FIER II',
                'description' => 'Focus sur les régions administratives spécifiquement couvertes par le projet FIER II.',
                'url' => 'http://192.162.68.122:8090/geoserver/Mymap/wms?service=WMS&version=1.1.0&request=GetMap&layers=Mymap%3Aregions_fierii&bbox=-12.23924%2C10.18993%2C4.24284%2C23.55346&width=768&height=622&srs=EPSG%3A4326&styles=&format=application/openlayers',
                'downloadUrl' => '#',
                'thumbnail' => '/images/maps/mopti.png',
            ],
            [
                'id' => 'fier2-cercles-12',
                'id_thematique' => 'fier2-zones',
                'title' => "Les cercles d'intervention du projet FIER II",
                'description' => 'Cartographie détaillée des zones d\'intervention prioritaires (Cercles) du projet FIER II.',
                'url' => 'http://192.162.68.122:8090/geoserver/CercleFIERII/wms?service=WMS&version=1.1.0&request=GetMap&layers=CercleFIERII%3Acerclefierii&bbox=-11.97423%2C10.19857%2C-4.39346%2C14.64821&width=768&height=450&srs=EPSG%3A4326&styles=&format=application/openlayers',
                'downloadUrl' => '#',
                'thumbnail' => '/images/maps/zoneDIntervention.png',
            ],
            [
                'id' => 'mli-hydro-network',
                'id_thematique' => 'fier2-zones',
                'title' => 'Réseau Hydrographique',
                'description' => 'Visualisation des cours d\'eau et des points d\'eau stratégiques pour l\'ASPH.',
                'url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3893!2d-7.9!3d12.6!',
                'downloadUrl' => '#',
                'thumbnail' => '/images/maps/fleuveNiger.jpg',
            ],
            [
                'id' => 'mli-villages-population',
                'id_thematique' => 'localities',
                'title' => 'Cartographie des Villages',
                'description' => 'Représentation spatiale de plus de 19 000 localités à travers le Mali.',
                'url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3893!2d-7.9!3d12.6!',
                'downloadUrl' => '#',
                'thumbnail' => '/images/maps/village.png',
            ],
            [
                'id' => 'fier-projets-finances-cercle',
                'id_thematique' => 'projets-emplois',
                'title' => 'Liste des projets financés par cercle - FIER',
                'description' => 'Visualisation des projets financés par le FIER au niveau des différents cercles.',
                'url' => 'http://192.162.68.122:8090/geoserver/JeunesFinancesCercle/wms?service=WMS&version=1.1.0&request=GetMap&layers=JeunesFinancesCercle%3AJF_Aggrega&bbox=-11.49479%2C10.18993%2C-4.36742%2C14.42&width=768&height=455&srs=EPSG%3A4326&styles=&format=application/openlayers',
                'downloadUrl' => '#',
                'thumbnail' => '/images/maps/zoneDIntervention.png',
            ],
        ];

        foreach ($maps as $map) {
            Map::updateOrCreate(
                ['id' => $map['id']],
                $map
            );
        }
    }
}
