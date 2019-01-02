<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('resellers')->insert([
            'api_token' => str_random(60),
            'name' => 'Landing Framework',
            'support_email' => 'support@example.com',
            'domain' => '*',
            'active' => true,
            'logo' => '/assets/branding/icon-light.svg',
            'logo_square' => '/assets/branding/square.svg',
            'favicon' => '/assets/branding/favicon.ico',
            'settings' => '{
              "website_active": false
            }',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('plans')->insert([
            'order' => 1,
            'reseller_id' => 1,
            'name' => 'full_access',
            'currency' => 'USD',
            'monthly_price' => '0',
            'annual_price' => '0',
            'active' => false,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('plans')->insert([
            'order' => 2,
            'reseller_id' => 1,
            'name' => 'Trial',
            'currency' => 'USD',
            'monthly_price' => '0',/*
            'monthly_price_currencies' => '{
              "EUR": "0"
            }',*/
            'active' => true,
            'default' => true,
            'limitations' => '{
              "forms": {"max": "2", "visible": "1", "edit_html": "1", "max_entries": "10"}, 
              "media": {"visible": "1"}, "account": {"plan_visible": "1"}, 
              "modals": {"max": "2", "visible": "1"}, 
              "eddystones": {"max": "1", "visible": "1"}, 
              "landingpages": {"max": "2", "visible": "1", "edit_html": "1", "custom_domain": "0"}, 
              "emailcampaigns": {"max": "2", "visible": "1"}
            }',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('plans')->insert([
            'order' => 3,
            'reseller_id' => 1,
            'name' => 'Quick Start',
            'currency' => 'USD',
            'monthly_price' => '9',
            'monthly_remote_product_id' => '4714689',
            'monthly_order_url' => 'https://secure.avangate.com/order/checkout.php?PRODS=4714689&QTY=1&SHORT_FORM=1',
            'active' => true,
            'limitations' => '{
              "forms": {"max": "5", "visible": "1", "edit_html": "1", "max_entries": "500"}, 
              "media": {"visible": "1"}, "account": {"plan_visible": "1"}, 
              "modals": {"max": "5", "visible": "1"}, 
              "eddystones": {"max": "5", "visible": "1"}, 
              "landingpages": {"max": "5", "visible": "1", "edit_html": "1", "custom_domain": "1"}, 
              "emailcampaigns": {"max": "5", "visible": "1"}
            }',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('plans')->insert([
            'order' => 4,
            'reseller_id' => 1,
            'name' => 'Basic',
            'currency' => 'USD',
            'monthly_price' => '19',
            'monthly_remote_product_id' => '4714691',
            'monthly_order_url' => 'https://secure.avangate.com/order/checkout.php?PRODS=4714691&QTY=1&SHORT_FORM=1',
            'active' => true,
            'limitations' => '{
              "forms": {"max": "10", "visible": "1", "edit_html": "1", "max_entries": "1200"}, 
              "media": {"visible": "1"}, "account": {"plan_visible": "1"}, 
              "modals": {"max": "10", "visible": "1"}, 
              "eddystones": {"max": "10", "visible": "1"}, 
              "landingpages": {"max": "10", "visible": "1", "edit_html": "1", "custom_domain": "1"}, 
              "emailcampaigns": {"max": "10", "visible": "1"}
            }',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('plans')->insert([
            'order' => 5,
            'reseller_id' => 1,
            'name' => 'Enhanced',
            'currency' => 'USD',
            'monthly_price' => '39',
            'monthly_remote_product_id' => '4714693',
            'monthly_order_url' => 'https://secure.avangate.com/order/checkout.php?PRODS=4714693&QTY=1&SHORT_FORM=1',
            'active' => true,
            'limitations' => '{
              "forms": {"max": "30", "visible": "1", "edit_html": "1", "max_entries": "2500"}, 
              "media": {"visible": "1"}, "account": {"plan_visible": "1"}, 
              "modals": {"max": "30", "visible": "1"}, 
              "eddystones": {"max": "30", "visible": "1"}, 
              "landingpages": {"max": "30", "visible": "1", "edit_html": "1", "custom_domain": "1"}, 
              "emailcampaigns": {"max": "30", "visible": "1"}
            }',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('plans')->insert([
            'order' => 6,
            'reseller_id' => 1,
            'name' => 'Advanced',
            'currency' => 'USD',
            'monthly_price' => '59',
            'monthly_remote_product_id' => '4714695',
            'monthly_order_url' => 'https://secure.avangate.com/order/checkout.php?PRODS=4714695&QTY=1&SHORT_FORM=1',
            'active' => true,
            'limitations' => '{
              "forms": {"max": "50", "visible": "1", "edit_html": "1", "max_entries": "5000"}, 
              "media": {"visible": "1"}, "account": {"plan_visible": "1"}, 
              "modals": {"max": "50", "visible": "1"}, 
              "eddystones": {"max": "50", "visible": "1"}, 
              "landingpages": {"max": "50", "visible": "1", "edit_html": "1", "custom_domain": "1"}, 
              "emailcampaigns": {"max": "50", "visible": "1"}
            }',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('plans')->insert([
            'order' => 7,
            'reseller_id' => 1,
            'name' => 'Major',
            'currency' => 'USD',
            'monthly_price' => '99',
            'monthly_remote_product_id' => '4714697',
            'monthly_order_url' => 'https://secure.avangate.com/order/checkout.php?PRODS=4714697&QTY=1&SHORT_FORM=1',
            'active' => true,
            'limitations' => '{
              "forms": {"max": "90", "visible": "1", "edit_html": "1", "max_entries": "10000"}, 
              "media": {"visible": "1"}, "account": {"plan_visible": "1"}, 
              "modals": {"max": "90", "visible": "1"}, 
              "eddystones": {"max": "90", "visible": "1"}, 
              "landingpages": {"max": "90", "visible": "1", "edit_html": "1", "custom_domain": "1"}, 
              "emailcampaigns": {"max": "90", "visible": "1"}
            }',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('plans')->insert([
            'order' => 8,
            'reseller_id' => 1,
            'name' => 'Extreme',
            'currency' => 'USD',
            'monthly_price' => '149',
            'monthly_remote_product_id' => '4714699',
            'monthly_order_url' => 'https://secure.avangate.com/order/checkout.php?PRODS=4714699&QTY=1&SHORT_FORM=1',
            'active' => true,
            'limitations' => '{
              "forms": {"max": "150", "visible": "1", "edit_html": "1", "max_entries": "15000"}, 
              "media": {"visible": "1"}, "account": {"plan_visible": "1"}, 
              "modals": {"max": "150", "visible": "1"}, 
              "eddystones": {"max": "150", "visible": "1"}, 
              "landingpages": {"max": "150", "visible": "1", "edit_html": "1", "custom_domain": "1"}, 
              "emailcampaigns": {"max": "150", "visible": "1"}
            }',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('plans')->insert([
            'order' => 9,
            'reseller_id' => 1,
            'name' => 'Ultimate',
            'currency' => 'USD',
            'monthly_price' => '199',
            'monthly_remote_product_id' => '4714701',
            'monthly_order_url' => 'https://secure.avangate.com/order/checkout.php?PRODS=4714701&QTY=1&SHORT_FORM=1',
            'active' => true,
            'limitations' => '{
              "forms": {"max": "200", "visible": "1", "edit_html": "1", "max_entries": "20000"}, 
              "media": {"visible": "1"}, "account": {"plan_visible": "1"}, 
              "modals": {"max": "200", "visible": "1"}, 
              "eddystones": {"max": "200", "visible": "1"}, 
              "landingpages": {"max": "200", "visible": "1", "edit_html": "1", "custom_domain": "1"}, 
              "emailcampaigns": {"max": "200", "visible": "1"}
            }',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('plans')->insert([
            'order' => 10,
            'reseller_id' => 1,
            'name' => 'Enterprise',
            'currency' => 'USD',
            'monthly_price' => '379',
            'monthly_remote_product_id' => '4714703',
            'monthly_order_url' => 'https://secure.avangate.com/order/checkout.php?PRODS=4714703&QTY=1&SHORT_FORM=1',
            'active' => true,
            'limitations' => '{
              "forms": {"max": "400", "visible": "1", "edit_html": "1", "max_entries": "40000"}, 
              "media": {"visible": "1"}, "account": {"plan_visible": "1"}, 
              "modals": {"max": "400", "visible": "1"}, 
              "eddystones": {"max": "400", "visible": "1"}, 
              "landingpages": {"max": "400", "visible": "1", "edit_html": "1", "custom_domain": "1"}, 
              "emailcampaigns": {"max": "400", "visible": "1"}
            }',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('users')->insert([
            'is_reseller_id' => 1,
            'reseller_id' => 1,
            'plan_id' => 1,
            'name' => 'System Owner',
            'email' => 'info@example.com',
            'password' => bcrypt('welcome'),
            'api_token' => str_random(60),
            'confirmed' => 1,
            'role' => 'owner',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
