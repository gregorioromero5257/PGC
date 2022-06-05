<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StackeholderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stackeholders = [
            [
                'id' => 1,
                'user_id'=> 1,
                'name'=> 'ABT México',
                'sh_type_id'=> 1,
                'sh_description_id'=> 1
            ],
            [
              'id'=> 2,
              'user_id'=> 1,
              'name'=> 'AMGEN',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1,
            ],
            [
              'id'=> 3,
              'user_id'=> 1,
              'name'=> 'Axtel',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1,
            ],
            [
              'id'=> 4,
              'user_id'=> 1,
              'name'=> 'Bayer',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 5,
              'user_id'=> 1,
              'name'=> 'CANIETI',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 6,
              'user_id'=> 1,
              'name'=> 'Chemonics',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 7,
              'user_id'=> 1,
              'name'=> 'Comercios Afiliados',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 8,
              'user_id'=> 1,
              'name'=> 'Currie Brown Mexico',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 9,
              'user_id'=> 1,
              'name'=> 'DuPont México',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 10,
              'user_id'=> 1,
              'name'=> 'Edelman',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 11,
              'user_id'=> 1,
              'name'=> 'Eli Lilly y Compañía De México',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 12,
              'user_id'=> 1,
              'name'=> 'Empresa multinacional de software',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 13,
              'user_id'=> 1,
              'name'=> 'Empresa Privada',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 14,
              'user_id'=> 1,
              'name'=> 'Endeavor',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 15,
              'user_id'=> 1,
              'name'=> 'Ethylene Xxi Contractors',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 16,
              'user_id'=> 1,
              'name'=> 'Facebook',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 17,
              'user_id'=> 1,
              'name'=> 'GILEAD',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 18,
              'user_id'=> 1,
              'name'=> 'Google',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 19,
              'user_id'=> 1,
              'name'=> 'IDOM',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 20,
              'user_id'=> 1,
              'name'=> 'Instituto VIF',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 21,
              'user_id'=> 1,
              'name'=> 'London School of Economics Enterprise',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 22,
              'user_id'=> 1,
              'name'=> 'McKinsey',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 23,
              'user_id'=> 1,
              'name'=> 'N/A',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 24,
              'user_id'=> 1,
              'name'=> 'NIssan',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 25,
              'user_id'=> 1,
              'name'=> 'Odebrecht',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 26,
              'user_id'=> 1,
              'name'=> 'OMIDYAR',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 27,
              'user_id'=> 1,
              'name'=> 'Pfizer',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 28,
              'user_id'=> 1,
              'name'=> 'Pharmaceutical Research and Manufacturers of America',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 29,
              'user_id'=> 1,
              'name'=> 'Procter & Gamble',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 30,
              'user_id'=> 1,
              'name'=> 'Startup México',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 31,
              'user_id'=> 1,
              'name'=> 'Travesías Media',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 32,
              'user_id'=> 1,
              'name'=> 'Volpi Ventures',
              'sh_type_id'=> 1,
              'sh_description_id'=> 1
            ],
            [
              'id'=> 33,
              'user_id'=> 1,
              'name'=> 'AMIIF',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 34,
              'user_id'=> 1,
              'name'=> 'AMNU',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 35,
              'user_id'=> 1,
              'name'=> 'Asociación Programa Lazos',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 36,
              'user_id'=> 1,
              'name'=> 'CIVIX',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 37,
              'user_id'=> 1,
              'name'=> 'Consejo de la Comunicación',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 38,
              'user_id'=> 1,
              'name'=> 'Environmental Defense Fund de México',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 39,
              'user_id'=> 1,
              'name'=> 'Fundación Carlos Slim',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 40,
              'user_id'=> 1,
              'name'=> 'Fundación IDEA',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2,
            ],
            [
              'id'=> 41,
              'user_id'=> 1,
              'name'=> "Fundación Kellogg's",
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 42,
              'user_id'=> 1,
              'name'=> 'Fundación Plan',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 43,
              'user_id'=> 1,
              'name'=> 'Fundación Proacceso',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 44,
              'user_id'=> 1,
              'name'=> 'Fundación Saldarriaga Concha',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 45,
              'user_id'=> 1,
              'name'=> 'Fundación Televisa',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 46,
              'user_id'=> 1,
              'name'=> 'Geneva Network',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 47,
              'user_id'=> 1,
              'name'=> 'Innovations for Poverty Action',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 48,
              'user_id'=> 1,
              'name'=> 'Kellogg Foundation',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 49,
              'user_id'=> 1,
              'name'=> 'Mexicanos Primero',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 50,
              'user_id'=> 1,
              'name'=> 'NESTA',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 51,
              'user_id'=> 1,
              'name'=> 'Promotora Social',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 52,
              'user_id'=> 1,
              'name'=> 'Rockefeller Foundation',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 53,
              'user_id'=> 1,
              'name'=> 'Urban Institute',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 54,
              'user_id'=> 1,
              'name'=> 'Wilson Center',
              'sh_type_id'=> 1,
              'sh_description_id'=> 2
            ],
            [
              'id'=> 55,
              'user_id'=> 1,
              'name'=> 'CIDE',
              'sh_type_id'=> 1,
              'sh_description_id'=> 3
            ],
            [
              'id'=> 56,
              'user_id'=> 1,
              'name'=> 'Fondo de Fomento y Desarrollo de la Investigación Científica y Técnológica de la Universidad Autónoma del Estado de México',
              'sh_type_id'=> 1,
              'sh_description_id'=> 3
            ],
            [
              'id'=> 57,
              'user_id'=> 1,
              'name'=> 'Instituto México del Woodrow Wilson Center',
              'sh_type_id'=> 1,
              'sh_description_id'=> 3
            ],
            [
              'id'=> 58,
              'user_id'=> 1,
              'name'=> 'Instituto Tecnológico y de Estudios Superiores de Monterrey',
              'sh_type_id'=> 1,
              'sh_description_id'=> 3
            ],
            [
              'id'=> 59,
              'user_id'=> 1,
              'name'=> 'Universidad Autónoma de Chapingo',
              'sh_type_id'=> 1,
              'sh_description_id'=> 3
            ],
            [
              'id'=> 60,
              'user_id'=> 1,
              'name'=> 'Universidad Autónoma del Estado de México',
              'sh_type_id'=> 1,
              'sh_description_id'=> 3
            ],
            [
              'id'=> 61,
              'user_id'=> 1,
              'name'=> 'Universidad Autónoma Metropolitana',
              'sh_type_id'=> 1,
              'sh_description_id'=> 3
            ],
            [
              'id'=> 62,
              'user_id'=> 1,
              'name'=> 'Universidad Juárez del Estado de Durango',
              'sh_type_id'=> 1,
              'sh_description_id'=> 3
            ],
            [
              'id'=> 63,
              'user_id'=> 1,
              'name'=> 'Universidad Tecnológica de Nezahualcóyotl',
              'sh_type_id'=> 1,
              'sh_description_id'=> 3
            ],
            [
              'id'=> 64,
              'user_id'=> 1,
              'name'=> 'Woodrow Wilson International Center for Scholars',
              'sh_type_id'=> 1,
              'sh_description_id'=> 3
            ],
            [
              'id'=> 65,
              'user_id'=> 1,
              'name'=> 'Bureau of International Narcotics and Law Enforcement Affairs (INL)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 4
            ],
            [
              'id'=> 66,
              'user_id'=> 1,
              'name'=> 'Deutsche Gesellschaft Für Internationale Zusammenarbeit',
              'sh_type_id'=> 1,
              'sh_description_id'=> 4
            ],
            [
              'id'=> 67,
              'user_id'=> 1,
              'name'=> 'Embajada Británica',
              'sh_type_id'=> 1,
              'sh_description_id'=> 4
            ],
            [
              'id'=> 68,
              'user_id'=> 1,
              'name'=> 'Embajada de Estados Unidos en México',
              'sh_type_id'=> 1,
              'sh_description_id'=> 4
            ],
            [
              'id'=> 69,
              'user_id'=> 1,
              'name'=> 'USAID',
              'sh_type_id'=> 1,
              'sh_description_id'=> 4
            ],
            [
              'id'=> 70,
              'user_id'=> 1,
              'name'=> 'Banca de las Oportunidades',
              'sh_type_id'=> 1,
              'sh_description_id'=> 5
            ],
            [
              'id'=> 71,
              'user_id'=> 1,
              'name'=> 'Banco de Comercio Exterior de Colombia',
              'sh_type_id'=> 1,
              'sh_description_id'=> 5
            ],
            [
              'id'=> 72,
              'user_id'=> 1,
              'name'=> 'Bancóldex',
              'sh_type_id'=> 1,
              'sh_description_id'=> 5
            ],
            [
              'id'=> 73,
              'user_id'=> 1,
              'name'=> 'Comisión Intersectorial para la Primera Infancia',
              'sh_type_id'=> 1,
              'sh_description_id'=> 5
            ],
            [
              'id'=> 74,
              'user_id'=> 1,
              'name'=> 'Observatorio Colombiano de Ciencia y Tecnología',
              'sh_type_id'=> 1,
              'sh_description_id'=> 5
            ],
            [
              'id'=> 75,
              'user_id'=> 1,
              'name'=> 'Comisión de Agua del Estado de México (CAEM)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 6
            ],
            [
              'id'=> 76,
              'user_id'=> 1,
              'name'=> 'Comisión de la Cuenca del Río Lerma',
              'sh_type_id'=> 1,
              'sh_description_id'=> 6
            ],
            [
              'id'=> 77,
              'user_id'=> 1,
              'name'=> 'Comité de Planeación para el Desarrollo del Estado de México (COPLADEM)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 6
            ],
            [
              'id'=> 78,
              'user_id'=> 1,
              'name'=> 'DIF Oaxaca',
              'sh_type_id'=> 1,
              'sh_description_id'=> 6
            ],
            [
              'id'=> 79,
              'user_id'=> 1,
              'name'=> 'Instituto Jalisciense de la Juventud',
              'sh_type_id'=> 1,
              'sh_description_id'=> 6
            ],
            [
              'id'=> 80,
              'user_id'=> 1,
              'name'=> 'Secretaría de Comunicaciones del Estado de México',
              'sh_type_id'=> 1,
              'sh_description_id'=> 6
            ],
            [
              'id'=> 81,
              'user_id'=> 1,
              'name'=> 'Secretaría de Desarrollo Económico del Estado de México',
              'sh_type_id'=> 1,
              'sh_description_id'=> 6
            ],
            [
              'id'=> 82,
              'user_id'=> 1,
              'name'=> 'Secretaría de Desarrollo Urbano y Metropolitano del Estado de México',
              'sh_type_id'=> 1,
              'sh_description_id'=> 6
            ],
            [
              'id'=> 83,
              'user_id'=> 1,
              'name'=> 'Secretaría de Finanzas Oaxaca',
              'sh_type_id'=> 1,
              'sh_description_id'=> 6
            ],
            [
              'id'=> 84,
              'user_id'=> 1,
              'name'=> 'Secretaría de la Función Pública del Estado de Chihuahua',
              'sh_type_id'=> 1,
              'sh_description_id'=> 6
            ],
            [
              'id'=> 85,
              'user_id'=> 1,
              'name'=> 'Secretaría de Obra Pública del Estado de México',
              'sh_type_id'=> 1,
              'sh_description_id'=> 6
            ],
            [
              'id'=> 86,
              'user_id'=> 1,
              'name'=> 'SEDECO Hidalgo',
              'sh_type_id'=> 1,
              'sh_description_id'=> 6
            ],
            [
              'id'=> 87,
              'user_id'=> 1,
              'name'=> 'Subsecretaría de Planeación e Inversión Pública (SPIP)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 6
            ],
            [
              'id'=> 88,
              'user_id'=> 1,
              'name'=> 'Asociación Mexicana de Secretarios de Desarrollo Económico',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 89,
              'user_id'=> 1,
              'name'=> 'Banco de México',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 90,
              'user_id'=> 1,
              'name'=> 'Banjército',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 91,
              'user_id'=> 1,
              'name'=> 'Comisión de vivienda de la Cámara de Senadores',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 92,
              'user_id'=> 1,
              'name'=> 'Comisión Federal de Competencia Económica (COFECE)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 93,
              'user_id'=> 1,
              'name'=> 'Comisión Nacional de la Vivienda',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 94,
              'user_id'=> 1,
              'name'=> 'Comisión Nacional de Seguridad (CNS)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 95,
              'user_id'=> 1,
              'name'=> 'Comisión Nacional para Prevenir y Erradicar la Violencia contra las Mujeres (CONAVIM)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 96,
              'user_id'=> 1,
              'name'=> 'CONACYT',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 97,
              'user_id'=> 1,
              'name'=> 'CONAFE',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 98,
              'user_id'=> 1,
              'name'=> 'CONEVAL',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 99,
              'user_id'=> 1,
              'name'=> 'Consejo Mexicano de la Energía (COMENER)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 100,
              'user_id'=> 1,
              'name'=> 'Consejo Nacional de Evaluación (CONEVAL)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 101,
              'user_id'=> 1,
              'name'=> 'Consejo Nacional de Normalización y Certificación de Competencias Laborales',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 102,
              'user_id'=> 1,
              'name'=> 'Equipo de transición Estratégica Digital Nacional',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 103,
              'user_id'=> 1,
              'name'=> 'Finrural',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 104,
              'user_id'=> 1,
              'name'=> 'Fundación de Premio Nacional Tecnología',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 105,
              'user_id'=> 1,
              'name'=> 'IMSS',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 106,
              'user_id'=> 1,
              'name'=> 'INADEM',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 107,
              'user_id'=> 1,
              'name'=> 'INE',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 108,
              'user_id'=> 1,
              'name'=> 'INEA',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 109,
              'user_id'=> 1,
              'name'=> 'INECC',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 110,
              'user_id'=> 1,
              'name'=> 'INFONAVIT',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 111,
              'user_id'=> 1,
              'name'=> 'INFOTEC',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 112,
              'user_id'=> 1,
              'name'=> 'INMUJERES',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 113,
              'user_id'=> 1,
              'name'=> 'Instituto Nacional de Salud Pública de México',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 114,
              'user_id'=> 1,
              'name'=> 'Instituto Nacional del Emprendedor',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 115,
              'user_id'=> 1,
              'name'=> 'Procuraduría General de la República',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 116,
              'user_id'=> 1,
              'name'=> 'Registro Agrario Nacional',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 117,
              'user_id'=> 1,
              'name'=> 'SAGARPA',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 118,
              'user_id'=> 1,
              'name'=> 'SCT',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 119,
              'user_id'=> 1,
              'name'=> 'SE',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 120,
              'user_id'=> 1,
              'name'=> 'Secretaría de Desarrollo Social (SEDESOL)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 121,
              'user_id'=> 1,
              'name'=> 'Secretaría de Educación Pública (SEP)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 122,
              'user_id'=> 1,
              'name'=> 'Secretaría de Hacienda y Crédito Público',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 123,
              'user_id'=> 1,
              'name'=> 'Secretariado Ejecutivo del Sistema Nacional de Seguridad Pública (SESNSP)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 124,
              'user_id'=> 1,
              'name'=> 'SEDATU',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 125,
              'user_id'=> 1,
              'name'=> 'SEGOB',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 126,
              'user_id'=> 1,
              'name'=> 'SEMARNAT',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 127,
              'user_id'=> 1,
              'name'=> 'Senado de la República',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 128,
              'user_id'=> 1,
              'name'=> 'SENER',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 129,
              'user_id'=> 1,
              'name'=> 'Subsecretaría de Prevención y Participación Ciudadana',
              'sh_type_id'=> 1,
              'sh_description_id'=> 7
            ],
            [
              'id'=> 130,
              'user_id'=> 1,
              'name'=> 'Ayuntamiento de Mérida',
              'sh_type_id'=> 1,
              'sh_description_id'=> 8
            ],
            [
              'id'=> 131,
              'user_id'=> 1,
              'name'=> 'Conferencia Nacional de Seguridad Pública Municipal',
              'sh_type_id'=> 1,
              'sh_description_id'=> 8
            ],
            [
              'id'=> 132,
              'user_id'=> 1,
              'name'=> 'Municipio de General Escobedo',
              'sh_type_id'=> 1,
              'sh_description_id'=> 8
            ],
            [
              'id'=> 133,
              'user_id'=> 1,
              'name'=> 'Municipio de Los Cabos',
              'sh_type_id'=> 1,
              'sh_description_id'=> 8
            ],
            [
              'id'=> 134,
              'user_id'=> 1,
              'name'=> 'Municipio de Monterrey',
              'sh_type_id'=> 1,
              'sh_description_id'=> 8
            ],
            [
              'id'=> 135,
              'user_id'=> 1,
              'name'=> 'Municipio de Morelia',
              'sh_type_id'=> 1,
              'sh_description_id'=> 8
            ],
            [
              'id'=> 136,
              'user_id'=> 1,
              'name'=> 'Municipio de Zapopan',
              'sh_type_id'=> 1,
              'sh_description_id'=> 8
            ],
            [
              'id'=> 137,
              'user_id'=> 1,
              'name'=> 'APEC',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 138,
              'user_id'=> 1,
              'name'=> 'Banco Asiático de Desarrollo (ADB)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 139,
              'user_id'=> 1,
              'name'=> 'Banco Interamericano de Desarrollo',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 140,
              'user_id'=> 1,
              'name'=> 'Banco Mundial',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 141,
              'user_id'=> 1,
              'name'=> 'BID',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 142,
              'user_id'=> 1,
              'name'=> 'Corporación Financiera Internacional',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 143,
              'user_id'=> 1,
              'name'=> 'FAO',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 144,
              'user_id'=> 1,
              'name'=> 'IFC',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 145,
              'user_id'=> 1,
              'name'=> 'Laureate Education, Inc',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 146,
              'user_id'=> 1,
              'name'=> 'OECD',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 147,
              'user_id'=> 1,
              'name'=> 'Organización Internacional del Trabajo',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 148,
              'user_id'=> 1,
              'name'=> 'PNUD',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 149,
              'user_id'=> 1,
              'name'=> 'Transparency International',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 150,
              'user_id'=> 1,
              'name'=> 'UNFPA',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 151,
              'user_id'=> 1,
              'name'=> 'UNICEF',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 152,
              'user_id'=> 1,
              'name'=> 'United Nations Office on Drugs and Crime (UNODC)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 153,
              'user_id'=> 1,
              'name'=> "Women's World Banking",
              'sh_type_id'=> 1,
              'sh_description_id'=> 9
            ],
            [
              'id'=> 154,
              'user_id'=> 1,
              'name'=> 'Comisión Nacional de los Derechos Humanos (CNDH)',
              'sh_type_id'=> 1,
              'sh_description_id'=> 8
            ],
            [
              'id'=> 155,
              'user_id'=> 1,
              'name'=> 'Ricardo Gonzáles',
              'sh_type_id'=> 1,
              'sh_description_id'=> 10
            ]
        ];

        DB::table('stackeholders')->insert($stackeholders);
    }
}
