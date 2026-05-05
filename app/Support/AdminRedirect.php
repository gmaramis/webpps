<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\RedirectResponse;

final class AdminRedirect
{
    /**
     * Redirect ke rute indeks admin sambil mempertahankan ?page= dari permintaan (GET atau field tersembunyi POST).
     *
     * @param  array<string, mixed>  $parameters  Parameter rute tambahan (mis. binding model), jarang untuk indeks.
     */
    public static function toIndexRoute(string $name, array $parameters = []): RedirectResponse
    {
        $page = request()->input('page');
        if ($page !== null && $page !== '') {
            $parameters['page'] = $page;
        }

        return redirect()->route($name, $parameters);
    }
}
