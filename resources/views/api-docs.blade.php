<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Dokumentasi API</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-stone-400">Jelajahi endpoint PerpusKu dan contoh request/response.</p>
            </div>
            <a href="{{ route('api.openapi') }}" target="_blank" rel="noopener"
               class="inline-flex w-fit items-center gap-2 rounded-xl bg-violet-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-violet-500">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                Buka kontrak OpenAPI (JSON)
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-stone-950 px-4 py-8 text-white sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 rounded-2xl border border-stone-800 bg-stone-900 p-5">
                <p class="text-sm leading-6 text-stone-300">
                    Gunakan tampilan interaktif di bawah untuk melihat detail endpoint. Endpoint API selain register
                    dan login memerlukan Bearer token. Kontrak mentah tersedia melalui tombol
                    <span class="font-semibold text-violet-300">Buka kontrak OpenAPI (JSON)</span>.
                </p>
            </div>

            <div id="api-docs-error" class="hidden mb-6 rounded-xl border border-red-900 bg-red-950/60 p-4 text-sm text-red-200">
                Dokumentasi interaktif gagal dimuat. Periksa koneksi internet untuk memuat Swagger UI, atau buka kontrak OpenAPI (JSON).
            </div>

            <div class="overflow-hidden rounded-2xl bg-white p-2 shadow-xl">
                <div id="swagger-ui"></div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css">
    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const error = document.getElementById('api-docs-error');

            try {
                if (typeof SwaggerUIBundle === 'undefined') {
                    throw new Error('Swagger UI failed to load.');
                }

                const response = await fetch(@json(route('api.openapi')), {
                    headers: { Accept: 'application/json' },
                });

                if (!response.ok) {
                    throw new Error(`OpenAPI request failed with status ${response.status}.`);
                }

                const specification = await response.json();
                specification.servers = [{
                    url: `${window.location.origin}/api/v1`,
                    description: 'Server aplikasi ini',
                }];

                SwaggerUIBundle({
                    spec: specification,
                    dom_id: '#swagger-ui',
                    deepLinking: true,
                    displayRequestDuration: true,
                    filter: true,
                    persistAuthorization: false,
                });
            } catch (exception) {
                error.classList.remove('hidden');
                console.error('Failed to load API documentation.', exception);
            }
        });
    </script>
</x-app-layout>
