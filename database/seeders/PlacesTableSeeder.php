<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('places')->insert([
            [
                'name' => 'La Comté',
                'shortDescription' => 'Une région verdoyante habitée par les Hobbits.',
                'longDescription' => 'La Comté est une région de prairies et de collines verdoyantes, avec de nombreux lacs, rivières et forêts, et est connue pour ses trous de hobbit confortables et ses jardins bien entretenus.',
                'locationType' => 'Rural/Campagne',
                'restrictions' => 'Aucune restriction particulière.',
                'travelAdvice' => 'Apportez vos meilleurs habits de fête et préparez-vous à de nombreux repas!',
                'picture' => 'shire.jpg', // Assurez-vous que cette image existe dans votre dossier public/storage
                'story' => 'La Comté est célèbre pour être le point de départ de nombreux aventuriers, y compris Frodon et Bilbon Sacquet.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rivendell',
                'shortDescription' => 'Une vallée cachée abritant les Elfes sages et immortels.',
                'longDescription' => "Rivendell, ou Imladris en sindarin, est un refuge paisible et un bastion de savoir. C'est ici que les conseils contre les forces de Sauron sont souvent tenus et que les voyageurs peuvent trouver repos et conseils.",
                'locationType' => 'Vallée/Refuge',
                'restrictions' => "L'accès est caché aux yeux des étrangers et n'est ouvert qu'à ceux qui sont en mission ou qui ont l'approbation des Elfes.",
                'travelAdvice' => 'Respectez les traditions elfiques et profitez de la sérénité de la vallée pour vous reposer.',
                'picture' => 'rivendell.jpg', // Remplacez par le chemin d'accès à votre image
                'story' => "Rivendell a été fondée par Elrond au deuxième âge de la Terre du Milieu et a survécu aux guerres et aux épreuves en tant que sanctuaire de la sagesse.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mines de la Moria',
                'shortDescription' => "Anciennes mines naines, désormais peuplées par des créatures de l'ombre.",
                'longDescription' => "Situées sous les Montagnes de Brume, les Mines de la Moria sont un réseau complexe de tunnels, de salles et de puits d'exploitation. Jadis prospères, elles sont désormais un lieu de danger et de ténèbres.",
                'locationType' => 'Mine/Caverne',
                'restrictions' => "Extrêmement dangereux. Il est conseillé de ne pas s'y aventurer sans une bonne raison.",
                'travelAdvice' => "Portez une lumière, soyez sur vos gardes et ne réveillez pas le Balrog.",
                'picture' => 'moria.jpg', // Remplacez par le chemin d'accès à votre image
                'story' => "La Moria était le plus grand royaume des Nains jusqu'à ce que le mal s'y répande et que les Nains soient chassés de leurs demeures ancestrales.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mordor',
                'shortDescription' => "Le domaine de Sauron, marqué par la désolation et la Montagne du Destin.",
                'longDescription' => "Mordor est une terre de feu et de cendres encerclée par de massives montagnes noires. En son centre se trouve la Montagne du Destin où l'Unique Anneau peut être détruit.",
                'locationType' => 'Région/Volcanique',
                'restrictions' => "L'entrée est gardée par la tour de Barad-dûr et les terres sont patrouillées par les orcs et autres serviteurs de Sauron.",
                'travelAdvice' => "N'entreprenez pas ce voyage seul. Seuls ceux qui portent un lourd fardeau doivent tenter de traverser ces terres.",
                'picture' => 'mordor.jpg', // Remplacez par le chemin d'accès à votre image
                'story' => "Mordor est l'épicentre du mal en Terre du Milieu, d'où Sauron lance ses armées pour conquérir les terres libres.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Isengard',
                'shortDescription' => 'Forteresse de Saruman, située au pied des Montagnes de Brume.',
                'longDescription' => "Isengard est une forteresse entourée d'une muraille de pierre, avec en son centre la tour d'Orthanc. Autrefois un bastion des forces du bien, elle est maintenant corrompue par Saruman.",
                'locationType' => 'Forteresse',
                'restrictions' => "Contrôlée par les forces de Saruman, l'accès est limité aux alliés de l'Istari déchu.",
                'travelAdvice' => "Soyez prudent, car les troupes de Saruman patrouillent la région et la trahison est dans l'air.",
                'picture' => 'isengard.jpg', // Assurez-vous d'avoir une image correspondante
                'story' => "Isengard est devenu un lieu de guerre et d'industrie, où les machines de Saruman détruisent la nature pour forger des armes.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lothlórien',
                'shortDescription' => "Terre des Elfes, préservée et protégée par la magie de Galadriel.",
                'longDescription' => "Lothlórien est une forêt d'or et un refuge elfique, célèbre pour ses arbres mallorn sous lesquels les Elfes construisent leurs demeures.",
                'locationType' => 'Forêt/Refuge Elfique',
                'restrictions' => "Les frontières sont gardées et seuls ceux qui ont reçu la bénédiction de Galadriel peuvent entrer.",
                'travelAdvice' => "Approchez avec respect et émerveillez-vous devant l'une des dernières enclaves de la grandeur elfique en Terre du Milieu.",
                'picture' => 'lothlorien.jpg', // Assurez-vous d'avoir une image correspondante
                'story' => "Lothlórien résiste au temps et à l'ombre, un vestige de l'âge des arbres et de la lumière.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Helms Deep',
                'shortDescription' => "Un bastion de la résistance des Hommes contre les forces de Saruman pendant la Guerre de l'Anneau.",
                'longDescription' => "Le Gouffre de Helm est une forteresse imprenable creusée dans la roche elle-même, dernier refuge des Rohirrim face à l'assaut de l'Isengard.",
                'locationType' => 'Forteresse',
                'restrictions' => "Pendant les périodes de guerre, seul un nombre limité d'alliés sont admis à l'intérieur pour la défense.",
                'travelAdvice' => "En temps de paix, les visiteurs peuvent explorer les murailles massives et les salles écho de l'histoire de Rohan.",
                'picture' => 'helms_deep.jpg', // Assurez-vous d'avoir une image correspondante
                'story' => "Le Gouffre de Helm a été le site de nombreuses batailles, notamment la célèbre Bataille du Gouffre de Helm.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ... Ajoutez d'autres lieux ici
        ]);
    }
}
