<?php

use Illuminate\Database\Seeder;


class OAuthClientsSeeder extends Seeder {
    public function run(){
        DB::table('oauth_clients')->insert(array(
			'client_id' => "testclienttt",
			'client_secret' => "testpassss",
			'redirect_uri' => "http://fake/",
		));
    }
}