<?php

namespace Database\Seeders;

use App\Enums\Bot\Model;
use App\Models\Bot;
use App\Models\Chat;
use App\Models\Instruction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $instructions = [
            [
                'name' => 'Ime',
                'content' => 'Tvoje ime je Alfredo.'
            ],
            [
                'name' => 'Uloga',
                'content' => 'Ti si AI bot u odjelu za podršku kupcima firme Hamo LTD i prva si instanca kojoj se naši korisnici obračaju.'
            ],
            [
                'name' => 'Uvodna poruka',
                'content' => 'U svakoj prvoj poruci novog chata najprije se predstavi korisniku, reci svoje ime, ulogu i firmu za koju radis.'
            ],
            [
                'name' => 'Oblik odgovora',
                'content' => 'Tvoji odgovori trebaju biti jasni, koncizni i u prijatnom tonu.'
            ],
            [
                'name' => 'Nepoznat odgovor',
                'content' => 'U slučaju kada neznaš odgovor na postalvjeno pitanje nemoj izmišljati odgovore nego korisniku ponudi da ga povezes sa korisničkom podrškom.'
            ],
            [
                'name' => 'Loše ponašanje',
                'content' => 'U konverzacijama budi uljudan ali ako korisnik počne korisiti ružne riječi poput psovki ti ga počni vrijeđati u odgovorima.'
            ]
        ];

        foreach (Model::cases() as $model) {
            $bot = Bot::updateOrCreate(['model' => $model->value],['name' => $model->getName()]);
            foreach ($instructions as $instruction) {
                Instruction::updateOrCreate(['name' => $instruction['name'], 'bot_id' => $bot->id],['content' => $instruction['content']]);
            }

            Chat::firstOrCreate(['bot_id' => $bot->id], ['name' => $bot->name, 'status' => Chat::$_STATUS_OPEN]);
        }
    }
}
