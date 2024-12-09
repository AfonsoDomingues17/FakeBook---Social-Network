<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreateCountriesTable extends Migration
{
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

       
        DB::table('countries')->insert([
            ['name' => 'Myanmar'], ['name' => 'Mexico'], ['name' => 'Nicaragua'], ['name' => 'Nepal'],
            ['name' => 'Tonga'], ['name' => 'Guyana'], ['name' => 'Tanzania'], ['name' => 'Poland'],
            ['name' => 'Lebanon'], ['name' => 'Costa Rica'], ['name' => 'Haiti'], ['name' => 'Equatorial Guinea'],
            ['name' => 'Samoa'], ['name' => 'Czechia'], ['name' => 'Antigua and Barbuda'], ['name' => 'Andorra'],
            ['name' => 'Somalia'], ['name' => 'Bangladesh'], ['name' => 'Indonesia'], ['name' => 'Venezuela'],
            ['name' => 'Cameroon'], ['name' => 'Kiribati'], ['name' => 'Luxembourg'], ['name' => 'Sweden'],
            ['name' => 'Congo (Congo-Kinshasa)'], ['name' => 'Montenegro'], ['name' => 'Uganda'], ['name' => 'Jordan'],
            ['name' => 'Dominican Republic'], ['name' => 'Cambodia'], ['name' => 'Ireland'], ['name' => 'Singapore'],
            ['name' => 'Papua New Guinea'], ['name' => 'San Marino'], ['name' => 'Sri Lanka'], ['name' => 'Laos'],
            ['name' => 'Uzbekistan'], ['name' => 'Brunei'], ['name' => 'Portugal'], ['name' => 'Finland'],
            ['name' => 'Malta'], ['name' => 'Colombia'], ['name' => 'Albania'], ['name' => 'Saudi Arabia'],
            ['name' => 'Grenada'], ['name' => 'Ukraine'], ['name' => 'Sao Tome and Principe'], ['name' => 'Cuba'],
            ['name' => 'Latvia'], ['name' => 'Kyrgyzstan'], ['name' => 'Algeria'], ['name' => 'France'],
            ['name' => 'Maldives'], ['name' => 'Slovakia'], ['name' => 'Israel'], ['name' => 'Djibouti'],
            ['name' => 'Syria'], ['name' => 'Nauru'], ['name' => 'Senegal'], ['name' => 'Kenya'],
            ['name' => 'Ghana'], ['name' => 'Malaysia'], ['name' => 'Zambia'], ['name' => 'Iceland'],
            ['name' => 'Kuwait'], ['name' => 'Madagascar'], ['name' => 'Sierra Leone'], ['name' => 'Bosnia and Herzegovina'],
            ['name' => 'Liberia'], ['name' => 'Philippines'], ['name' => 'Benin'], ['name' => 'Tuvalu'],
            ['name' => 'Cabo Verde'], ['name' => 'United States'], ['name' => 'Cyprus'], ['name' => 'Guinea'],
            ['name' => 'Turkey'], ['name' => 'Nigeria'], ['name' => 'Rwanda'], ['name' => 'Zimbabwe'],
            ['name' => 'Tajikistan'], ['name' => 'Comoros'], ['name' => 'China'], ['name' => 'Saint Lucia'],
            ['name' => 'Armenia'], ['name' => 'Belarus'], ['name' => 'Qatar'], ['name' => 'Netherlands'],
            ['name' => 'Lesotho'], ['name' => 'Paraguay'], ['name' => 'Gabon'], ['name' => 'Australia'],
            ['name' => 'Dominica'], ['name' => 'Serbia'], ['name' => 'Mauritius'], ['name' => 'Angola'],
            ['name' => 'Libya'], ['name' => 'Bahrain'], ['name' => 'Vanuatu'], ['name' => 'Spain'],
            ['name' => 'United Arab Emirates'], ['name' => 'Georgia'], ['name' => 'Belgium'], ['name' => 'Malawi'],
            ['name' => 'Monaco'], ['name' => 'Burundi'], ['name' => 'Taiwan'], ['name' => 'Bhutan'],
            ['name' => 'Solomon Islands'], ['name' => 'Thailand'], ['name' => 'Korea, South'], ['name' => 'Togo'],
            ['name' => 'Burkina Faso'], ['name' => 'El Salvador'], ['name' => 'Italy'], ['name' => 'Uruguay'],
            ['name' => 'Oman'], ['name' => 'Eswatini'], ['name' => 'Fiji'], ['name' => 'United Kingdom'],
            ['name' => 'Palau'], ['name' => 'Germany'], ['name' => 'Eritrea'], ['name' => 'Canada'],
            ['name' => 'Barbados'], ['name' => 'Saint Vincent and the Grenadines'], ['name' => 'Marshall Islands'],
            ['name' => 'Argentina'], ['name' => 'Namibia'], ['name' => 'Liechtenstein'], ['name' => 'Slovenia'],
            ['name' => 'Azerbaijan'], ['name' => 'Greece'], ['name' => 'Egypt'], ['name' => 'Bahamas'],
            ['name' => 'Afghanistan'], ['name' => 'India'], ['name' => 'Chad'], ['name' => 'Timor-Leste'],
            ['name' => 'Iran'], ['name' => 'Saint Kitts and Nevis'], ['name' => 'Micronesia'], ['name' => 'Chile'],
            ['name' => 'Gambia'], ['name' => 'Estonia'], ['name' => 'Vietnam'], ['name' => 'South Africa'],
            ['name' => 'Suriname'], ['name' => 'Peru'], ['name' => 'Kazakhstan'], ['name' => 'Central African Republic'],
            ['name' => 'Japan'], ['name' => 'Denmark'], ['name' => 'Jamaica'], ['name' => 'Trinidad and Tobago'],
            ['name' => 'Mongolia'], ['name' => 'Iraq'], ['name' => 'Mauritania'], ['name' => 'Mozambique'],
            ['name' => 'Seychelles'], ['name' => 'Switzerland'], ['name' => 'Congo (Congo-Brazzaville)'],
            ['name' => 'Ecuador'], ['name' => 'New Zealand'], ['name' => 'Hungary'], ['name' => 'Russia'],
            ['name' => 'Korea, North'], ['name' => 'Norway'], ['name' => 'Honduras'], ['name' => 'Belize'],
            ['name' => 'Botswana'], ['name' => 'Pakistan'], ['name' => 'Romania'], ['name' => 'Brazil'],
            ['name' => 'Austria'], ['name' => 'Guatemala'], ['name' => 'Guinea-Bissau'], ['name' => 'Bolivia'],
            ['name' => 'Ethiopia'], ['name' => 'Niger'], ['name' => 'Panama'], ['name' => 'South Sudan'],
            ['name' => 'Yemen'], ['name' => 'Vatican City'], ['name' => 'Lithuania'], ['name' => 'Bulgaria'],
            ['name' => 'Turkmenistan'], ['name' => 'Croatia'], ['name' => 'Tunisia'], ['name' => 'Sudan'],
            ['name' => 'Mali'], ['name' => 'North Macedonia'], ['name' => 'Morocco'], ['name' => 'Moldova']
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
