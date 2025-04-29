<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use App\Models\Author;
use App\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Laravel\Passport\Passport;
use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\File;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $createdAuthToken = $this->createdAuthToken();
        if($createdAuthToken){
            $this->setEnv('PASSPORT_CLIENT_ID',$createdAuthToken->id);
            $this->setEnv('PASSPORT_CLIENT_SECRET',$createdAuthToken->secret);
        }
        
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'example@gmail.com',
        ]);
        
        // User::factory(1000)->create();

        $parents = Category::factory(5)->create();

        $parents->each(function ($parent) {
            Category::factory(2)->create([
                'parent_id' => $parent->id,
            ]);
        });

        Category::factory(3)->create();

        Author::factory(10)->create();

        Book::factory(1000)->create([
            'category_id' => Category::all()->random()->id,
            'author_id' => Author::all()->random()->id,
        ]);
    }

    protected function createdAuthToken() {
        $accessClient = Passport::personalAccessClient();
        $name = config('app.name').' Password Grant Client';
        $providers = array_keys(config('auth.providers'));
        $provider = in_array('users', $providers) ? 'users' : null;
        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPasswordGrantClient(
            null, $name, 'http://localhost', $provider
        );
        return $client;
    }

    protected function setEnv($key,$value){
        $envFile = base_path('.env');
    
        if (File::exists($envFile)) {
            $envContents = File::get($envFile);
                if (preg_match('/^' . preg_quote($key) . '=/m', $envContents)) {
                $envContents = preg_replace('/^' . preg_quote($key) . '=(.*)$/m', $key . '=' . $value, $envContents);
            } else {
                $envContents .= "\n" . $key . '=' . $value;
            }
    
            File::put($envFile, $envContents);
        }
    
    }
}
