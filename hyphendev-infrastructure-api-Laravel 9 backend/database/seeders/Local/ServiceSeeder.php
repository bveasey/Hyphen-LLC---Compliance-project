<?php

namespace Database\Seeders\Local;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the User seeds for local environment.
     * @return void
     */
    public function run(): void
    {
        Service::create([
            'name' => 'Slack - Hyphen',
            'service_slug' => 'slack',
            'base_url' => 'https://slack.com/api',
            'token' => 'xoxb-257682180128-3959045666306-9Rtb4q90JVU6d3dj8ln8sX3f',
        ]);

        Service::create([
            'name' => 'Slack - Loop',
            'service_slug' => 'slack',
            'base_url' => 'https://slack.com/api',
            'token' => 'xoxb-1304959993345-3973152194561-TcvZM5sQGzS3R1EAQBX8xcUj',
        ]);

        Service::create([
            'name' => 'Slack - Vault',
            'service_slug' => 'slack',
            'base_url' => 'https://slack.com/api',
            'token' => 'xoxb-1737636619059-3962992928100-AfvlOry2mB48sTB0zdRjArQc',
        ]);

        Service::create([
            'name' => 'Slack - Raven',
            'service_slug' => 'slack',
            'base_url' => 'https://slack.com/api',
            'token' => 'xoxb-3448434701938-3973170788321-ZzH4eg7MXvcQD0Vg59VChvB8',
        ]);

        Service::create([
            'name' => 'Slack - Atlas',
            'service_slug' => 'slack',
            'base_url' => 'https://slack.com/api',
            'token' => 'xoxb-3887744079029-3945982449879-1AE8tHfEshgzyQTkJo5MSGe9',
        ]);

        Service::create([
            'name' => 'Slack - Helix',
            'service_slug' => 'slack',
            'base_url' => 'https://slack.com/api',
            'token' => 'xoxb-1819979650770-3957611193221-yOGnE2XwFmLwbftc1JUmfe4G',
        ]);

        Service::create([
            'name' => 'Slack - HyphenFi',
            'service_slug' => 'slack',
            'base_url' => 'https://slack.com/api',
            'token' => 'xoxb-1825796881511-3965076845571-ZHsIRvvh6AumSoPLsF4mgczE',
        ]);

        Service::create([
            'name' => 'Slack - HyphenDev',
            'service_slug' => 'slack',
            'base_url' => 'https://slack.com/api',
            'token' => 'xoxb-3163889269190-3978100682321-w6dmYdGh8H81kabZTimJXCwH',
        ]);

        Service::create([
            'service_slug' => 'perimeter_81',
            'name' => 'Perimeter 81',
            'base_url' => 'https://api.perimeter81.com/api/rest/v1',
            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI0NWRiMmZiZmNlYWNhMTEzZjhmZTliNTc2Y2I3NGE2MCIsImlkIjoiR2ZJM1V6c1JWZyIsInNjb3BlcyI6WyJVU0VSX1JFQUQiXSwidGVuYW50SWQiOiJoeXBoZW4iLCJpYXQiOjE2NjI0ODc4MjYsImV4cCI6NDc4NDU1MTgyNiwiYXVkIjoic2FmZXItYXV0aCIsImlzcyI6ImF1dGguc2FmZXJzb2Z0d2FyZS5jb20iLCJzdWIiOiJoeXBoZW4ifQ.KVtpIE2a2umF4JWVF6996aD0SEOtlqxCpuzn53B8w-Q',
        ]);
    }
}
