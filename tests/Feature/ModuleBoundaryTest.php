<?php

namespace Tests\Feature;

use App\Modules\EventModule\Contracts\EventLookupContract;
use App\Modules\EventModule\Providers\EventModuleServiceProvider;
use App\Modules\EventModule\Services\EventLookupService;
use App\Modules\RegistrationModule\Providers\RegistrationModuleServiceProvider;
use App\Modules\TicketModule\Contracts\TicketInventoryContract;
use App\Modules\TicketModule\Providers\TicketModuleServiceProvider;
use App\Modules\TicketModule\Services\TicketInventoryService;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Tests\TestCase;

class ModuleBoundaryTest extends TestCase
{
    /** Tabel milik tiap modul. */
    private const MODUL = [
        'EventModule' => ['events', 'categories', 'category_event'],
        'TicketModule' => ['ticket_types', 'tickets'],
        'RegistrationModule' => ['participants', 'registrations'],
    ];

    public function test_modul_tidak_mengakses_model_service_http_atau_tabel_modul_lain(): void
    {
        foreach (array_keys(self::MODUL) as $modul) {
            foreach ($this->fileKode($modul) as $file) {
                $kode = file_get_contents($file);

                foreach (self::MODUL as $lain => $tabelLain) {
                    if ($lain === $modul) {
                        continue;
                    }

                    foreach (['Models', 'Services', 'Http'] as $bagian) {
                        $this->assertStringNotContainsString(
                            "App\\Modules\\{$lain}\\{$bagian}\\",
                            $kode,
                            "{$file} memakai {$bagian} milik {$lain}."
                        );
                    }

                    foreach ($tabelLain as $tabel) {
                        $this->assertStringNotContainsString(
                            "'{$tabel}'",
                            $kode,
                            "{$file} menyebut tabel {$tabel} milik {$lain}."
                        );
                    }
                }
            }
        }
    }

    public function test_service_provider_modul_dimuat_dan_contract_terikat(): void
    {
        foreach ([
            EventModuleServiceProvider::class,
            TicketModuleServiceProvider::class,
            RegistrationModuleServiceProvider::class,
        ] as $provider) {
            $this->assertTrue($this->app->providerIsLoaded($provider), "{$provider} belum terdaftar.");
        }

        $this->assertInstanceOf(EventLookupService::class, $this->app->make(EventLookupContract::class));
        $this->assertInstanceOf(TicketInventoryService::class, $this->app->make(TicketInventoryContract::class));
    }

    /**
     * @return list<string>
     */
    private function fileKode(string $modul): array
    {
        $hasil = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(app_path("Modules/{$modul}"))
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && str_ends_with($file->getFilename(), '.php')) {
                $hasil[] = $file->getPathname();
            }
        }

        return $hasil;
    }
}