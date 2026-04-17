<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(){
         $this->call([
              AppStylingSeeder::class,
              CurrencyTableSeeder::class,
              CountryTableSeeder::class,
              RoleSeeder::class,
              TypeSeeder::class,
              PaymentOptionSeeder::class,
              LanguageTableSeeder::class,
              NotificationSeeder::class,
              MapProviderSeeder::class,
              SmsProviderSeeder::class,
              TemplateSeeder::class,
              PromoTypeSeeder::class,
              CommonDataSeeder::class,
              BannerDataSeeder::class,
              TimezoneSeeder::class,
              PermissionSeeder::class,
              ReturnReasonSeeder::class,
              LuxuryOptionsSeeder::class,
              OrderStatusSeeder::class,
              OrderStatusSeeder::class,
              VendorProductTempleteSeeder::class,
              DispatcherStatusOptionSeeder::class,
              SubscriptionFeaturesListUserSeeder::class,
              SubscriptionFeaturesListVendorSeeder::class,
              SubscriptionStatusOptionsSeeder::class,
              HomePageLabelSeeder::class,
              HomePageLabelSeederDefault::class,
              EmailTemplateSeeder::class,
              NotificationTemplateSeeder::class,
              WebStylingSeeder::class
          ]);
        // $this->call(UsersTableSeeder::class);
       // $this->call(AppStylingOptionsTableSeeder::class);
    }
}